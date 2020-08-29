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
                            <v-text-field label="Maximum Sit" autocomplete="off"
                            @keypress="($event)=>{let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                                        if (keyCode < 48 || keyCode > 57) { // 46 is dot
                                            $event.preventDefault();
                                        }}"
                             v-model="formData.capacity" :rules="[v=>!!v || 'Number of Sit is required']" outlined required></v-text-field>
                          </v-col>     
                          <v-col cols="12">
                            <v-menu v-model="menu" :close-on-content-click="false" transition="scale-transition"
                              offset-y max-width="290px" min-width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="last_date"
                                  label="Last Date of Admission"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                  outlined
                                  :rules="[v => !!v || 'Date is requried']"
                                ></v-text-field>
                              </template>
                              <v-date-picker :min="new Date(new Date().getTime()+(5*24*60*60*1000)).toISOString().substr(0, 10)" v-model="last_date" no-title @input="menu = false"></v-date-picker>
                            </v-menu>
                          </v-col>   
                          <v-col cols="12">
                            <v-menu v-model="menu2" :close-on-content-click="false" transition="scale-transition"
                              offset-y max-width="290px" min-width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="formData.start_at"
                                  label="Course start Date"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                  outlined
                                  :rules="[v => !!v || 'Date is requried']"
                                ></v-text-field>
                              </template>
                              <v-date-picker :min="selectDate" v-model="formData.start_at" no-title @input="menu2 = false"></v-date-picker>
                            </v-menu>
                          </v-col>   
                        </v-row>
                    </v-container>
                   </v-card-text>
                    <v-card-actions class="white">
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="()=>{close();$refs.batchForm.reset();}">cancle</v-btn>
                      <v-btn text color="primary" type="submint">Save</v-btn>
                    </v-card-actions>
                    </v-card>
                  </v-form>
                
              </v-dialog>
            </v-toolbar>
          </template>
          <template v-slot:item.actions="{ item }">
            <div class="d-flex">
              <div>
                <v-tooltip bottom v-if="role.can('batch_update')"> 
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon small class="mr-2" color="success"
                     @click="editedItem(item)" v-bind="attrs" v-on="on">
                      mdi-pencil
                    </v-icon>
                  </template>
                  <span>Edit</span>
                </v-tooltip>
              </div>
              <div>
                <v-tooltip bottom v-if="role.can('batch_delete') && (item.capacity == item.status)"> 
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon small class="mr-2" color="error"
                     @click="deleteItem(item)" v-bind="attrs" v-on="on">
                      mdi-delete
                    </v-icon>
                  </template>
                  <span>Delete</span>
                </v-tooltip>
              </div>
            </div>
          </template>
          <template v-slot:item.status="{ item }">
            <v-chip :color="getColor(item.status)">{{item.status}}</v-chip>
          </template>
          <template v-slot:item.batch_no="{ item }">
            <v-chip color="primary">{{item.batch_no}}</v-chip>
          </template>
          <template v-slot:item.capacity="{ item }">
            <v-chip color="info">{{item.capacity}}</v-chip>
          </template>
        </v-data-table>
        <v-dialog v-model="editDialog"  max-width="500">
          <v-form ref="editForm" @submit.prevent="update">
            <v-card>
              <v-card-title>Edit Batch</v-card-title>
              <v-card-text>
                            <v-text-field label="Maximum Sit" autocomplete="off"
                            @keypress="($event)=>{let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                                        if (keyCode < 48 || keyCode > 57) { // 46 is dot
                                            $event.preventDefault();
                                        }}"
                             v-model="editItem.capacity" :rules="[v=>!!v || 'Number of Sit is required']" outlined required></v-text-field>   
                            <v-menu v-model="menu" :close-on-content-click="false" transition="scale-transition"
                              offset-y max-width="290px" min-width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="editlast_date"
                                  label="Last Date of Admission"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                  outlined
                                  :rules="[v => !!v || 'Date is requried']"
                                ></v-text-field>
                              </template>
                              <v-date-picker :min="editItem.last_at ? new Date(editItem.last_at).toISOString().substr(0, 10) : new Date().toISOString().substr(0, 10)" v-model="editlast_date" no-title @input="menu = false"></v-date-picker>
                            </v-menu>
                            <v-menu v-model="menu2" :close-on-content-click="false" transition="scale-transition"
                              offset-y max-width="290px" min-width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="editItem.start_at"
                                  label="Course start Date"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                  outlined
                                  :rules="[v => !!v || 'Date is requried']"
                                ></v-text-field>
                              </template>
                              <v-date-picker :min="selectDate" v-model="editItem.start_at" no-title @input="menu2 = false"></v-date-picker>
                            </v-menu>
              </v-card-text>
              <v-card-actions class="white">
                <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="()=>{close();$refs.editForm.reset();}">cancle</v-btn>
                  <v-btn text color="primary" type="submint">Save</v-btn>
                </v-card-actions>
              </v-card>
          </v-form>
        </v-dialog>
        <v-dialog v-model="deleteDialog" persistent max-width="290">
            <v-card color="warning white--text">
                <v-card-title class="headline">Delete Batch?</v-card-title>
                <v-card-text class="white--text">
                  Are you sure you want to delete this Batch?
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="white darken-1" text @click="close">No</v-btn>
                <v-btn color="white darken-1" text @click="deleteBatch">Yes</v-btn>
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
        <Pagination class="mt-4" :links="this.batches"/>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Auth from '@/auth'
import Pagination from '@/shared/admin/components/Pagination'
export default {
    data: vm=>({
      last_date: '',
      editlast_date: '',
      date3: '',
        batchesOnly: [],
        headers:[
            {text: 'Course Name',value: 'name',align: 'left'},
            {text: 'Batch No',value: 'batch_no'},
            {text: 'Last Date',value: 'last_at'},
            {text: 'Stat Date',value: 'start_at'},
            {text: 'Total Sit',value: 'capacity'},
            {text: 'Available Sit',value: 'status'},
            {text: 'Actions',value: 'actions', sortable: false},
        ],
        uiManage:[],
        role: new Auth(vm.$page.auth.roles),
        search: '',
        menu: false,
        menu2: false,
        menu3: false,
        deleteDialog: false,
        snackbar: false,
        selectDate: new Date().toISOString().substr(0, 10),
        formData : {},
        editItem : {},
        category: '',
        courses: [],
        dialog: false,
        editDialog: false,
    }),
    props:['batches','categorys'],
    watch: {
      last_date(val){
        if(val){
          this.selectDate = new Date(new Date(val).getTime()+(2*24*60*60*1000)).toISOString().substr(0, 10);
          this.formData.last_at = val;
        }
      },
      editlast_date(val){
        if(val){
          this.selectDate = new Date(new Date(val).getTime()+(2*24*60*60*1000)).toISOString().substr(0, 10);
          this.editItem.last_at = val;
        }
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
        },
      close(){
        this.dialog = false
        this.editDialog = false
        this.deleteDialog = false
        this.$nextTick(() => {
          this.formData = {};
          this.editItem = {};
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
      update(){
        if(this.$refs.editForm.validate()){
          let formData = new FormData();
          for(const data in this.editItem){
            formData.append(data,this.editItem[data])
          }
          this.$inertia.post(this.$route('batches.update',this.editItem.id), formData)
            .then(()=>{
              this.snackbar = true;
              if(this.$page.successMessage.success){
                this.init();
                this.close();
                this.$refs.editForm.reset();
              }
            }).catch(err => console.log(err));
        }
      },
      editedItem(item){
        this.editItem = {}
        this.editItem.id = item.id
        this.editItem.capacity = item.capacity
        this.editItem.start_at = item.start_at
        this.editlast_date = item.last_at
        this.editDialog = true
      },
      deleteItem(item){
        this.editItem.id = item.id
        this.deleteDialog = true
      },
      deleteBatch(){
        if(this.editItem.id){
          this.$inertia.delete(this.$route('batches.destroy',this.editItem.id))
           .then(()=>{
                  this.snackbar = true;
                  this.close();
                  this.init();               
            }).catch(err => {console.log(err)})
        }
      },
      getColor(status){
        if(status < 5 || status == 0){
          return 'success';
        }else if(status < 10){
          return 'warning'
        }else{
          return 'error'
        }
      }
    },
    components:{Layout,Pagination}
}
</script>