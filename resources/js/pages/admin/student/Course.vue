<template>
    <Layout title="Course request" :nav="true">
        <v-card>
            <v-container>
              <v-row no-gutters >
                <v-col cols="8">
                  <ul class="text-body text-capitalize norenepss ftco-list">
                    <li><span>Name :</span><span>{{student.user ? student.user.name : ''}}</span></li>
                    <li><span>E-mail :</span> <span style="text-transform: none !important;">{{student.user ? student.user.email : ''}}</span></li>
                    <li><span>Contact Number :</span> <span>{{student.number ? student.number : ''}}</span></li>
                    <li><span>Registar At :</span> <span>{{student.created_at ? student.created_at : ''}}</span></li>
                  </ul>
                </v-col>
                <v-col cols="4">
                    <div class="ml-7">
                    <div class="norenepss" style="width: 200px">
                        <v-img height="200px" width="200px" :src="student.user ? student.user.avatar : ''"></v-img>
                    </div>
                    </div>
                </v-col>
                <v-col cols="8">
                    <ul class="mt-5 text-capitalize norenepss ftco-list" >
                    <div class="text-body text-center">Request Course</div>
                    <v-img max-height="150px" max-width="100%" :src="student.courses ? student.courses.course.banner_img : ''"></v-img>
                    <li style="border-bottom: none;"><span>Name :</span><span>{{student.courses ? student.courses.course.title : ''}}</span></li>
                    <li style="border-bottom: none;"><span>Fees :</span><span>{{student.courses ? student.courses.fees+' tk' : ''}}</span></li>
                    <li style="border-bottom: none;">
                        <span>Need to Pay :</span>
                        <span style="margin-top: -25px">
                            <v-text-field @input="number" @keypress="($event)=>{let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                                        if (keyCode < 48 || keyCode > 57) { // 46 is dot
                                            $event.preventDefault();
                                        }}" :readonly="!change" v-model="fees" :error-messages="error"></v-text-field>
                        </span>
                    </li>
                    <v-checkbox v-model="change" label="Change Amount"></v-checkbox>
                  </ul>
                </v-col>
              </v-row>
            </v-container>
            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn v-if="role.can('student_delete')" outlined color="warning" @click="deleteDialog = true">
                    <v-icon left>mdi-close-circle</v-icon>
                    Delete
                </v-btn>
                <v-btn v-if="role.can('student_update')" outlined :loading="loding" color="success" @click="submit">
                    <v-icon left>mdi-check-circle</v-icon>
                    ok
                </v-btn>
            </v-card-actions>
          </v-card>
          <v-dialog v-model="deleteDialog" persistent max-width="290">
            <v-card color="warning white--text">
                <v-card-title class="headline">Delete Course Request?</v-card-title>
                <v-card-text class="white--text">
                  Are you sure you want to delete this Course Request?
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="white darken-1" text @click="deleteDialog = false">No</v-btn>
                <v-btn color="white darken-1" text @click="deleteStudent">Yes</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Auth from '@/auth'
export default {
    data: vm=>({
        fees: '',
        change: false,
        deleteDialog: false,
        loding: false,
        error: '',
        role: new Auth(vm.$page.auth.roles),
    }),
    props:['student','pay_id'],
    watch:{
        change(){
            this.fees = this.student.courses.fees >=3000 && this.student.courses.fees <= 7000 ?  Math.round(this.student.courses.fees / 2) : this.student.courses.fees >=8000 && this.student.courses.fees <= 15000 ?  Math.round(this.student.courses.fees / 4) : Math.round(this.student.courses.fees / 5),
            this.error = ''
        }
    },
    mounted(){
        this.fees = this.student.courses.fees >=3000 && this.student.courses.fees <= 7000 ?  Math.round(this.student.courses.fees / 2) : this.student.courses.fees >=8000 && this.student.courses.fees <= 15000 ?  Math.round(this.student.courses.fees / 4) : Math.round(this.student.courses.fees / 5);
    },
    methods:{
        number(){
            this.fees = this.fees.replace(/[^0-9]/g,'');
            if(this.fees > this.student.courses.fees){
                this.error = 'Pay amount should be less than '+this.student.courses.fees+' tk' ;
            }else if(this.fees == null || this.fees == 0){
                this.error = 'Pay Amount is requried'
            }else{
              this.error = ''
            }
        },
        deleteStudent(){
            this.$inertia.delete(this.$route('students.destroy',this.student.id)+'?has_id='+this.student.courses.id)
           .then(()=>{
                  this.deleteDialog = false;           
            }).catch(err => {console.log(err)})
        },
        submit(){
          if(!this.error){
            this.loding = true
            const formData = new FormData();
            formData.append('fees',this.fees);
            formData.append('has_id',this.student.courses.id);
            this.pay_id ? formData.append('pay_id',this.pay_id) : '';
            this.$inertia.post(this.$route('students.update',this.student.id),formData)
           .then(()=>{
                  this.loding = false;           
            }).catch(err => {console.log(err)})
          }
        }
    },
    components:{
        Layout
    }
}
</script>
<style lang="scss" scoped>
  .norenepss{
    background-color: #f1f3f6 !important; 
    padding: 10px !important;
    border: none;
    border-radius: 10px !important;
    box-shadow: 3px 3px 3px rgba(55, 84, 170, 0.1), -3px -3px 3px rgb(255, 255, 255);
  }
  .ftco-list{
    li{
      padding: 5px 10px;
      border-bottom: 1px dotted #6f7172;
      position: relative;
      display: flex;
      flex-flow: row wrap;
      justify-content: space-between;
      span{
        &:nth-child(1){
          position: absolute;
          left: 32px;
          color: #6f7172;
        }
        color: #404242;
      }
    }
  }
  .chip{
    padding: 8px 0;
    text-transform: capitalize;;
    border-radius: 20px;
    border: none;
    text-align: center;
    color: #f1f3f6 !important;
  }
</style>