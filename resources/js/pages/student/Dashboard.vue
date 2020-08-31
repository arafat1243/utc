<template>
    <Layout title="Student Management">
        <v-container>
            <v-row justify="center">
                <v-col cols="12" md="8">
                    <v-card>
                        <v-expansion-panels accordion>
                            <v-expansion-panel v-for="(item,i) in courses" :key="i">
                            <v-expansion-panel-header><span>{{item.title}}</span> <v-spacer></v-spacer> <span class="chip" :class="chipColor(item.status)">{{item.status}}</span></v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-timeline dense>
                                <v-timeline-item v-for="(payment,n) in item.payment" :key="n" small>
                                    <v-card class="elevation-2">
                                        <v-card-title class="headline">{{payment.created_at}} <v-spacer></v-spacer>  <v-chip :color="payment.approve ? 'success' : 'error'">{{payment.approve ? 'Approved' : 'Panding'}}</v-chip></v-card-title>
                                        <v-card-text>
                                            Payment amount : {{payment.amount}} tk<br>
                                            Due amount : {{payment.due}} tk
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn v-if="!payment.approve" @click="cancle(payment.id)" color="error" outlined>cancel</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-timeline-item>
                                </v-timeline>
                                <v-card-actions>
                                        <a href="#" target="_blank" v-if="item.status == 'compleate'" rel="noopener noreferrer">Download Certificate</a>
                                        <v-spacer></v-spacer>
                                        <v-btn color="primary" v-if="item.can" @click="payment(item)">Payment</v-btn>
                                </v-card-actions>
                            </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-dialog persistent v-model="paymentDialog" max-width="500">
            <v-card>
                <v-card-title>Payment Request</v-card-title>
                <v-card-text>
                    <v-text-field outlined v-model="editItem.amount" readonly label="Pay Amount"></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn outlined @click="()=>{paymentDialog = false;editItem = {}}" color="error">cancel</v-btn>
                    <v-btn outlined @click="save" color="success">Request</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message}}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </Layout>
</template>
<script>
import Layout from './Layout'
export default {
    data: ()=>({
        paymentDialog: false,
        snackbar: false,
        editItem: {}
    }),
    methods:{
        chipColor(status){
        if(status == 'panding'){
          return 'error'
        }else if(status == 'ongoing'){
          return 'warning'
        }else{
          return 'success'
        }
      },
      payment(item){
          this.editItem = {
              'id': item.id,
              'course_id': item.course_id,
              'amount': this.calculation(item.fees)
          }
          this.paymentDialog = true
      },
      save(){
          if(this.editItem.id){
              let formData = new FormData();
              for(let data in this.editItem){
                  formData.append(data,this.editItem[data]);
              }
              this.$inertia.post(this.$route('student.payment'),formData, {
                preserveState: true,
                preserveScroll: true,
                }).then(re => {
                    this.snackbar = true;
                    this.paymentDialog = false
                })
                .catch(err => console.log(err));
          }
      },
      cancle(id){
          let y = confirm('Are you sure? you want to cancle this request.');
          if(y){
              this.$inertia.delete(this.$route('student.delete',id), {
                preserveState: true,
                preserveScroll: true,
            }).then(re => {
                this.snackbar = true;
                this.paymentDialog = false
            })
            .catch(err => console.log(err));
          }
      },
      calculation(fees){
          if(fees >= 3000 && fees <= 5000){
              return Math.round(fees / 2);
          }else if(fees >= 8000 && fees <= 15000){
              return Math.round(fees / 4);
          }else{
              return Math.round(fees / 5);
          }
      }
    },
    props:['courses'],
    components:{Layout}
}
</script>
<style lang="scss" scoped>
    .chip{
        padding: 8px 0;
        text-transform: capitalize;;
        border-radius: 20px;
        border: none;
        text-align: center;
        color: #f1f3f6 !important;
    }
</style>