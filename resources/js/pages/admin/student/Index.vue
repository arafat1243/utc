<template>
    <Layout title="Student Managemant">
        <v-data-table :headers="headers" class="elevation-1" :items="studentsOnly" :search="search" hide-default-footer multi-sort>
          <template v-slot:top>
            <v-toolbar flat color="white">
              <v-toolbar-title>Students Managemant</v-toolbar-title>
              <v-divider class="mx-4" inset vertical></v-divider>
              <v-spacer></v-spacer>
              <v-text-field v-model="search" ppend-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
              <v-spacer></v-spacer>
              <v-dialog scrollable v-model="requestDialog" max-width="600">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
                    <v-badge color="error" bottom left :content="requestStudent.length" :value="requestStudent.length">
                      Request List
                    </v-badge>
                  </v-btn>
                </template>
                <v-card>
                <v-tabs v-model="tab" fixed-tabs background-color="primary" dark >
                  <v-tab> New Student </v-tab>
                  <v-tab> Course Request </v-tab>
                </v-tabs>

                <v-tabs-items v-model="tab">
                  <v-tab-item>
                    <v-card flat>
                      <v-card-text class="overflow-y-auto" style="max-height: 300px;">
                        <div v-if="requestStudent.length == 0" class="text-h6 text-center">No Student available</div>
                        <div class="d-flex border-bottom align-center" v-else v-for="(student,i) in requestStudent" :key="i">
                          <v-avatar size="50px">
                            <v-img :src="student.user.avatar"></v-img>
                          </v-avatar>
                          <div class="ml-3 font-weight-bold">
                            <a :href="$route('students.edit',student.id)" class="text-h6">{{student.user.name}}</a>
                            <div>E-mail : {{student.user.email}}<br/>
                            Number : {{student.number}}
                            </div>
                          </div>
                        </div>
                      </v-card-text>
                    </v-card>
                  </v-tab-item>
                  <v-tab-item>
                    <v-card flat>
                      <v-card-text>hi</v-card-text>
                    </v-card>
                  </v-tab-item>
                </v-tabs-items>
              </v-card>
              </v-dialog>
            </v-toolbar>
          </template>
          <template v-slot:item.user.avatar="{item}">
            <v-avatar size="80px">
              <img :src="item.user.avatar">
            </v-avatar>
          </template>
          <template v-slot:item.actions="{ item }">
            <div class="d-flex">
              <div v-for="(ui,i) in uiManage" :key="i">
                <v-tooltip bottom v-if="ui.can"> 
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon small class="mr-2" :color="ui.color"
                     @click="ui.text === 'Details' ? preview(item) : editItem(item)" v-bind="attrs" v-on="on">
                      {{ui.icon}}
                    </v-icon>
                  </template>
                  <span>{{ui.text}}</span>
                </v-tooltip>
              </div>
            </div>
          </template>
        </v-data-table>
        <v-dialog v-model="editDialog" max-width="500px">
          <v-card>
            <v-card-title><span class="headline text-capitalize">attach course certificate</span></v-card-title>
                  <v-form @submit.prevent="save" ref="studentForm" method="post" enctype="multipart/form-data">
                    <v-card-text>
                    <v-container>
                        <v-row>
                          <v-col cols="12">
                            <v-select label="Ongoing Course List" :items="ongoingCourse" v-model="editStudent.course_id"
                            :rules="[v => !!v || 'Select a Course']"
                             required  outlined ></v-select>
                          </v-col>
                          <v-col cols="12">
                            <v-file-input label="Attachment" v-model="editStudent.attachment"
                            :rules="[v => !!v || 'Attachment is required.' ,v => (v && v.size < 4000000) || 'Attachment size should be less than 4 MB!']"
                            single-line accept="application/msword,application/pdf, image/*" required outlined></v-file-input>
                          </v-col>
                        </v-row>
                    </v-container>
                    </v-card-text>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click.stop="close">Cancel</v-btn>
                    <v-btn color="blue darken-1" :loading="loading" text type="submit">Save</v-btn>
                    </v-card-actions>
                  </v-form>
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
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
          <v-card>
            <v-toolbar dark color="primary">
              <v-btn icon dark @click="dialog = false">
                <v-icon>mdi-close</v-icon>
              </v-btn>
              <v-toolbar-title>Student Details</v-toolbar-title>
            </v-toolbar>
            <v-container>
              <v-row no-gutters justify="center">
                <v-col cols="8">
                  <ul class="text-body text-capitalize norenepss ftco-list">
                    <li><span>Name :</span><span>{{singleStudent.user ? singleStudent.user.name : ''}}</span></li>
                    <li><span>Mother's Name :</span> <span>{{singleStudent.mother_name ? singleStudent.mother_name : ''}}</span></li>
                    <li><span>Father's Name :</span> <span>{{singleStudent.father_name ? singleStudent.father_name : ''}}</span></li>
                    <li><span>Contact Number :</span> <span>{{singleStudent.number ? singleStudent.number : ''}}</span></li>
                    <li><span>Emergency Number :</span> <span>{{singleStudent.emergency_number ? singleStudent.emergency_number : ''}}</span></li>
                    <li><span>Permanent Address :</span> <span style="max-width: 350px">{{singleStudent.permanent_address ? singleStudent.permanent_address : ''}}</span></li>
                    <li><span>Present Address :</span> <span style="max-width: 350px">{{singleStudent.present_address ? singleStudent.present_address : ''}}</span></li>
                    <li><span>Gender :</span> <span>{{singleStudent.gender ? singleStudent.gender : ''}}</span></li>
                    <li><span>Marital status :</span> <span>{{singleStudent.marital_status ? singleStudent.marital_status : ''}}</span></li>
                    <li><span>Institute Name :</span> <span>{{singleStudent.institute_name ? singleStudent.institute_name : ''}}</span></li>
                    <li><span>Academic Status :</span> <span>{{singleStudent.academic_status ? singleStudent.academic_status : ''}}</span></li>
                    <li><span>Blood Group :</span> <span>{{singleStudent.blood_group == 0 ? 'N/A' : singleStudent.blood_group}}</span></li>
                    <li><span>Registar At :</span> <span>{{singleStudent.registar_at ? singleStudent.registar_at : ''}}</span></li>
                  </ul>
                </v-col>
                <v-col cols="4">
                <div class="ml-7">
                  <div class="norenepss" style="width: 200px">
                    <v-img height="200px" width="200px" :src="singleStudent.user ? singleStudent.user.avatar : ''"></v-img>
                  </div>
                  <ul class="mt-5 text-capitalize norenepss ftco-list" v-if="singleStudent.courses">
                    <div class="text-body text-center">Course List</div>
                    <v-expansion-panels accordion flat  hover multiple>
                      <v-expansion-panel style="background-color: #f1f3f6 !important;" v-for="(item,i) in singleStudent.courses" :key="i">
                        <v-expansion-panel-header>
                          <span>{{item.course.title}}</span> <v-spacer></v-spacer> <span class="chip" :class="chipColor(item.status)">{{item.status}}</span>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content v-if="item.status != 'panding'">
                          <table border="0" style="width: 100%; border-collapse: collapse;">
                            <thead class="text-left">
                              <th>Date</th>
                              <th>Amount</th>
                            </thead>
                            <tbody>
                              <tr v-for="(payment,i) in item.payment" :key="i">
                                <td>{{payment.created_at}}</td>
                                <td>{{payment.amount}} tk</td>
                              </tr>
                              <tr style="border-top: 1px solid #6f7172">
                                <td>Pay Amount</td>
                                <td>{{totalPay}} tk</td>
                              </tr>
                              <tr>
                                <td>Course Fees</td>
                                <td>(-){{item.course.fees}} tk</td>
                              </tr>
                              <tr style="border-top: 1px solid #6f7172">
                                <td>Deu Amount</td>
                                <td>{{ Math.abs(totalPay - item.course.fees) }} tk</td>
                              </tr>
                            </tbody>
                          </table>
                        </v-expansion-panel-content>
                      </v-expansion-panel>
                    </v-expansion-panels>
                  </ul>
                </div>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-dialog>
        <Pagination class="mt-4" :links="students"/>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Pagination from '@/shared/admin/components/Pagination'
import Auth from '@/auth'
export default {
    data: ()=>({
      headers: [
        {text: 'Name',value: 'user.name',align: 'left'},
        {text: 'Avatar',value: 'user.avatar',sortable: false},
        {text: 'E-mail',value: 'user.email'},
        {text: 'Phone Number',value: 'number'},
        {text: 'Emergency Number',value: 'emergency_number'},
        {text: 'Registar At',value: 'registar_at'},
        {text: 'Actions',value: 'actions', sortable: false},
      ],
      studentsOnly: [],
      search: '',
      tab: '',
      uiManage:[],
      dialog: false,
      requestDialog: false,
      snackbar: false,
      editDialog: false,
      singleStudent: [],
      loading: false,
      totalPay: 0,
      editStudent: {
        course_id: 0,
        student_id: 0,
        attachment: []
      },
      ongoingCourse: [],
      requestStudent: []
    }),
    props: ['students'],
    mounted(){
      this.init();
    },
    methods:{
      init(){
        this.studentsOnly = [];
        this.students.data.forEach(student =>{
          if(student.user.status){
            this.studentsOnly.push(student);
          }else{
            this.requestStudent.push(student);
          }
        });
        let role  = new Auth(this.$page.auth.roles);
        this.uiManage = [
            {text: 'Details',icon: 'mdi-eye',color: 'primary',can: role.can('student_view')},
            {text: 'Edit',icon: 'mdi-pencil', color: 'success',can: role.can('student_update')},
        ]
      },
      preview(item){
        this.singleStudent = item
        item['courses'].forEach(course =>{
          this.totalPay = 0
          course['payment'].forEach(pay => {
            this.totalPay += pay.amount
          })
        })
        this.dialog = true
      },
      editItem(item){
        this.ongoingCourse = item['courses'].map(course => {
          let totalPay = 0
          course['payment'].forEach(pay => {
            totalPay += pay.amount
          })
          if(course.status == 'ongoing' && course.fees === totalPay){
            return {
              text: course.course.title,
              value: course.course_id
            }
          }else{
            return 'No data available'
          }
        })
        this.editStudent.student_id = item.id
        this.editDialog = true
      },
      chipColor(status){
        if(status == 'panding'){
          return 'error'
        }else if(status == 'ongoing'){
          return 'warning'
        }else{
          return 'success'
        }
      },
      save(){
        if(this.$refs.studentForm.validate()){
          this.loading = true
          let formData = new FormData();
            formData.append('course_id',this.editStudent.course_id);
            formData.append('student_id',this.editStudent.student_id);
            formData.append('attachment',this.editStudent.attachment);
            this.$inertia.post(this.$route('students.course'), formData, {
                replace: false,
                preserveState: true,
                preserveScroll: false,
                only: [],
              }).then(re => {
                      this.snackbar = true;
                      this.loading = false;
                      if(this.$page.successMessage.success){
                          this.init();
                          this.editDialog = false
                            this.editStudent = {
                              course_id: 0,
                              student_id: 0,
                              attachment: []
                            }
                            this.$refs.studentForm.reset();
                      }
                  })
                  .catch(err => console.log(err));
        }
      },
      close(){
        this.editDialog = false
        this.$nextTick(() => {
          this.editStudent = {
            course_id: 0,
            student_id: 0,
            attachment: []
          }
          this.$refs.studentForm.reset();
        })
      }
    },
    components:{
        Layout,Pagination
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
  .border-bottom{
    border-bottom: 1px solid #6f7172;
  }
</style>