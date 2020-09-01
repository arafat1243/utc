<template>
    <Layout title="Dashboard - UTC" id="body">
        <v-container>
            <v-row>
              <v-col cols="12" md="6">
                    <v-card elevation="8" height="300px">
                      <v-sheet height="250px" class="v-sheet--offset mx-auto" style="top: -14px;" elevation="12" max-width="calc(100% - 32px)" >
                        <apexchart height="100%" type="donut" :options="totalOptions" :series="totals[1]"></apexchart>
                      </v-sheet>
                      <v-card-text class="pt-0">
                         <div class="title font-weight-light mb-2">Total Count</div>
                      </v-card-text>
                    </v-card> 
                </v-col>
                <v-col cols="12" md="6" class="mt-6 mt-md-0">
                    <v-card elevation="8" height="300px">
                      <v-sheet height="250px" class="v-sheet--offset mx-auto" style="top: -14px;" elevation="12" max-width="calc(100% - 32px)" >
                        <v-window v-model="onboarding" reverse v-if="slides.length > 0">
                          <v-window-item  v-for="(slide,n) in slides" :key="n" :value="n">
                            <v-img height="250px" :src="slide.path"></v-img>
                          </v-window-item>
                        </v-window>
                        <div v-else class="text-center center-vaticaly grey--text title font-weight-bold font-18">No Slide Active Now</div>
                      </v-sheet>
                      <v-card-text class="pt-0">
                         <div class="title font-weight-light mb-2">Active Slides</div>
                      </v-card-text>
                    </v-card> 
                </v-col>
            </v-row>
        </v-container>
        <v-container class="mt-6">
            <v-row justify="center">
                <v-col cols="12" md="6">
                    <v-card height="400px">
                        <v-sheet
                        class="v-sheet--offset mx-auto"
                        elevation="12"
                        max-width="calc(100% - 32px)"
                        height="310"
                        >
                        <apexchart v-if="studentCourse.length > 0" width="100%" height="100%" type="bar" :options="studentOptions" :series="series"></apexchart>
                        <div v-else class="text-center center-vaticaly grey--text title font-weight-bold font-18">No Student Registrations</div>
                        </v-sheet>

                        <v-card-text class="pt-0">
                        <div class="title font-weight-light mb-2">Student Registrations</div>
                        <div class="subheading font-weight-light grey--text">Last 3 month Performance</div>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="6" class="mt-10 mt-md-0">
                    <v-card height="400px">
                        <v-sheet
                        class="v-sheet--offset mx-auto"
                        elevation="12"
                        max-width="calc(100% - 32px)"
                        height="310"
                        >
                        <apexchart v-if="payments.series.length > 0" height="100%" type="donut" :options="paymentOptions" :series="payments.series"></apexchart>
                        <div v-else class="text-center center-vaticaly grey--text title font-weight-bold font-18">No payment accepted</div>
                        </v-sheet>

                        <v-card-text class="pt-0">
                        <div class="title text-capitalize font-weight-light mb-2">payment accepted</div>
                        <div class="subheading font-weight-light grey--text">This month Performance</div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            
                  
                  
            <v-menu v-if="role.can('student_update')" transition="slide-x-reverse-transition">
             
              <template v-slot:activator="{ on, attrs }">
                  <v-btn bottom color="primary" dark fab fixed right
                    v-bind="attrs" v-on="on" @click="request_menu = true">
                    <v-badge top left color="error" :content="paymentRequest.length" :value="paymentRequest.length">
                      <v-icon>mdi-currency-bdt</v-icon>
                    </v-badge>
                  </v-btn>
                
              </template>
              
              <v-card width="350px" class="ml-auto">
                        <v-card height="330px"  flat class="primary lighten-3 overflow-y-auto">
                            <v-card-text v-if="paymentRequest.length > 0">
                                <div  class="d-flex align-center"  v-for="(request,i) in paymentRequest" :key="i">
                                    <v-avatar size="50px">
                                        <v-img :src="request.avatar"></v-img>
                                    </v-avatar>
                                    <div class="ml-3 font-weight-bold">
                                        <a :href="$route('students.edit',request.id)" class="light-blue--text text--darken-3 text-h6">{{request.name}}</a>
                                        <div>
                                            Number : {{request.number}}<br/>
                                            Course Name : {{request.course[0]}} <br/>Pay Amount : {{request.amount}}tk
                                        </div>
                                        <v-divider class="my-3"></v-divider>
                                    </div>
                                </div>
                            </v-card-text>
                            <div v-else class="text-center center-vaticaly grey--text title font-weight-bold font-18">No payment request</div>
                        </v-card>
                        <v-card-text class="pt-2">
                        <div class="title font-weight-light mb-2">Payment Request</div>
                        <div class="subheading font-weight-light grey--text">This week Performance</div>
                        </v-card-text>
                    </v-card>
            </v-menu>
        </v-container>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout';
import VueApexCharts from 'vue-apexcharts'
import Auth from '@/auth'
export default {
    data: vm => ({
      request_menu: false,
      onboarding: 0,
      studentOptions: {
          chart: {
            id: 'student-registration'
          },
          xaxis: {
            type: 'category'
          },
      },
      series: [{
          name: 'Total',
          data: vm.studentCourse,
        }],
        paymentOptions: {
          labels: vm.payments.label,
          chart: {
            type: 'donut',
            id: 'payment-accepted'
          },
          plotOptions: {
          pie: {
            donut: {
              labels: {
                show: true,
                name: {
                  fontSize: '22px',
                },
                value: {
                  formatter: function(val) {
                    return val + ' tk';
                  },
                  fontSize: '16px',
                },
                total: {
                show: true,
                label: 'Total Amount',
                formatter: function (w) {
                  // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                  return vm.payments.series.reduce(function(a, b){
                      return a + b;
                  }, 0) + ' tk'
                }
              }
              }
            }
          }
        },
          dataLabels: {
            enabled: false,
          },
      },
      totalOptions: {
        labels: vm.totals[0],
          chart: {
            type: 'donut',
            id: 'total'
          },
          dataLabels: {
            enabled: false,
          },
          legend:{
            formatter: function(val) {
              return val.toUpperCase();
            },
            position: 'left'
          }
      },
      role: new Auth(vm.$page.auth.roles),
      timeOut: ''
    }),
    props:['slides','payments','studentCourse','totals','paymentRequest'],
    mounted(){
      this.slideer();
    },
    destroyed(){
      clearTimeout(this.timeOut);
    },
    methods:{
      slideer(){
        if(this.slides.length > 0){
         this.timeOut = setInterval(()=>{
          if(this.slides.length != this.onboarding){
            this.onboarding +=1;
          }
          else{
            this.onboarding  = 0;
          }
        },1500);
        }
      }
    },
    components:{Layout,'apexchart': VueApexCharts}
}
</script>
<style lang="scss" scoped>
  .v-sheet--offset {
    top: -24px;
    position: relative;
  }
  .center-vaticaly{
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    position: relative;
  }
</style>