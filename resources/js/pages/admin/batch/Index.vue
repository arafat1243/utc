<template>
    <Layout title="Batch">
        <v-data-table :headers="headers" class="elevation-1" :search="search" :items="batchesOnly" hide-default-footer multi-sort>
            <template v-slot:top>
            <v-toolbar flat color="white">
              <v-toolbar-title>Students Managemant</v-toolbar-title>
              <v-divider class="mx-4" inset vertical></v-divider>
              <v-spacer></v-spacer>
              <v-text-field v-model="search" ppend-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
              <v-spacer></v-spacer>
              <v-dialog scrollable persistent v-model="dialog"  max-width="500">
                <template v-if="role.can('batch_create')" v-slot:activator="{ on, attrs }">
                    <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">New</v-btn>
                </template>
                <v-form ref="batchForm" @submit.prevent="save">
                <v-card style="max-hieght: 300px">
                  <v-card-title>Add New Batch</v-card-title>
                   <v-card-text>
                      <v-container>
                        <v-row justify="center">
                          <v-col cols="12">
                            <v-select label="Course Category" :items="categorys" v-model="category"  outlined required></v-select>
                          </v-col>   
                          <v-col cols="12">
                            <v-select label="Course Name" :items="courses" v-model="formData.course_id" :rules="[v=>!!v || 'Course Name is required']" outlined required></v-select>
                          </v-col>   
                          <v-col cols="12">
                            <v-text-field label="Batch No" @keypress="($event)=>{let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                                        if (keyCode < 48 || keyCode > 57) { // 46 is dot
                                            $event.preventDefault();
                                        }}" v-model="formData.batch_no" :rules="[v=> !!v || 'Batch No is requried']" outlined required></v-text-field>
                          </v-col>   
                          <v-col cols="12">
                            <v-menu v-model="menu" :close-on-content-click="false" transition="scale-transition"
                              offset-y max-width="290px" min-width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="date"
                                  label="Start Date"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                  outlined
                                  :rules="[v => !!v || 'Start Date is requried']"
                                ></v-text-field>
                              </template>
                              <v-date-picker :min="new Date().toISOString().substr(0, 10)" v-model="date" no-title @input="menu = false"></v-date-picker>
                            </v-menu>
                          </v-col>   
                          <v-col cols="12">
                            <v-menu v-model="menu2" :close-on-content-click="false" transition="scale-transition"
                              offset-y max-width="290px" min-width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="date2"
                                  label="End Date"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                  outlined
                                  :rules="[v => !!v || 'End Date is requried']"
                                ></v-text-field>
                              </template>
                              <v-date-picker :min="selectDate" v-model="date2" no-title @input="menu2 = false"></v-date-picker>
                            </v-menu>
                          </v-col>   
                        </v-row>
                    </v-container>
                   </v-card-text>
                    <v-card-actions class="white">
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="close">cancle</v-btn>
                      <v-btn text color="primary" type="submint">Save</v-btn>
                    </v-card-actions>
                    </v-card>
                  </v-form>
                
              </v-dialog>
            </v-toolbar>
          </template>
          <template v-slot:item.actions="{ item }">
            <div class="d-flex">
              <div v-for="(ui,i) in uiManage" :key="i">
                <v-tooltip bottom v-if="ui.can"> 
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon small class="mr-2" :color="ui.color"
                     @click="ui.text === 'Edit' ? editedItem(item) : deleteItem(item)" v-bind="attrs" v-on="on">
                      {{ui.icon}}
                    </v-icon>
                  </template>
                  <span>{{ui.text}}</span>
                </v-tooltip>
              </div>
            </div>
          </template>
          <template v-slot:item.status="{ item }">
            <v-chip :color="getColor(item.status)">{{getText(item.status)}}</v-chip>
          </template>
        </v-data-table>
        <v-dialog scrollable persistent v-model="editDialog"  max-width="500">
          <v-form ref="editForm" @submit.prevent="save">
            <v-card style="max-hieght: 300px">
              <v-card-title>Add New Batch</v-card-title>
              <v-card-text>
              </v-card-text>
              <v-card-actions class="white">
                <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="close">cancle</v-btn>
                  <v-btn text color="primary" type="submint">Save</v-btn>
                </v-card-actions>
              </v-card>
          </v-form>
        </v-dialog>
        <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message}}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <Pagination class="mt-4" :links="this.batches"/>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Auth from '@/auth'
import Pagination from '@/shared/admin/components/Pagination'
export default {
    data: vm=>({
      date: '',
      date2: '',
        batchesOnly: [],
        headers:[
            {text: 'Course Name',value: 'name',align: 'left'},
            {text: 'Batch No',value: 'batch_no',sortable: false},
            {text: 'Stated At',value: 'started_at'},
            {text: 'Ended At',value: 'ended_at'},
            {text: 'Status',value: 'status'},
            {text: 'Actions',value: 'actions', sortable: false},
        ],
        uiManage:[],
        role: new Auth(vm.$page.auth.roles),
        search: '',
        menu: false,
        menu2: false,
        snackbar: false,
        selectDate: new Date().toISOString().substr(0, 10),
        formData : {},
        category: '',
        courses: [],
        dialog: false,
        editDialog: false,
    }),
    props:['batches','categorys'],
    watch: {
      date (val) {
        let date = val.split('-');
        date = parseInt(date[2]) + 4;
        this.selectDate = val.slice(0, -2)+date;
        this.formData.stated_at = this.date
      },
      date2 (val) {
        this.formData.ended_at = this.date2
      },
      category(val){
        this.courses = []
        this.categorys.forEach(category => {
            if(category.value === val && category.courses){
              this.courses = category.courses;
            }
        });
      }
    },
    mounted(){
      this.snackbar = this.$page.successMessage.success;
        this.init()
    },
    methods:{
        init(){
            this.batchesOnly = this.batches.data
              this.uiManage = [
              {text: 'Edit',icon: 'mdi-pencil', color: 'success',can: this.role.can('batch_update')},
              {text: 'Delete',icon: 'mdi-delete', color: 'error',can: this.role.can('batch_delete')},
          ]
        },
      close(){
        this.dialog = false
        this.$nextTick(() => {
          this.formData = {};
          this.$refs.batchForm.reset();
          this.$refs.editForm.reset();
        })
      },
      save(){
        if(this.$refs.batchForm.validate()){
          let formData = new FormData();
          for(const data in this.formData){
            formData.append(data,this.formData[data])
          }
          this.$inertia.post(this.$route('batches.store'), formData)
            .then(()=>{
              this.snackbar = true;
              if(this.$page.successMessage.success){
                this.init();
                this.close();
              }
            }).catch(err => console.log(err));
        }
      },
      editedItem(item){
        
      },
      deleteItem(item){

      },
      getColor(status){
        const curentDate = new Date();
        curentDate.setDate(curentDate.getDate() + 1)
        const date = new Date(status);
        return date <= curentDate ? 'error' : 'success'
      },
      getText(status){
        const curentDate = new Date();
        curentDate.setDate(curentDate.getDate() + 1)
        const date = new Date(status);
        if(date <= curentDate){
          return 'Timesup';
        }else{
          let left =  Math.abs(date.getDate() - curentDate.getDate());
          return left > 1 ?  left+' days left' : left+' day left'
        }
      }
    },
    components:{Layout,Pagination}
}
</script>