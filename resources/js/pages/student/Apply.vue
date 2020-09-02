<template>
    <Layout :nav='true' title="Apply - UTC">
        <v-container>
            <v-row justify="center">
                <v-col cols="12" md="8">
                    <v-card>
                        <v-card-title>Payment Info</v-card-title>
                        <v-card-text>
                            <div class="d-flex">
                                <v-text-field outlined :value="course.title" required label="Course Name" readonly></v-text-field>
                                <v-spacer></v-spacer>
                                <v-text-field outlined required :value="course.fees" label="Course Fees" readonly></v-text-field>
                            </div>
                            <div class="d-flex">
                                <v-text-field :value="pay" outlined required label="Pay Amount" readonly></v-text-field>
                                <v-spacer></v-spacer>
                                <v-text-field :value="due" outlined required label="Due Amount" readonly></v-text-field>
                            </div>
                            <v-checkbox :rules="[v=> !!v || '']" v-model="terms" label="Terms and Conditions"></v-checkbox>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn outlined color="error">Cancle</v-btn>
                            <v-btn outlined color="success" @click="submit" :disabled="!terms">Submit</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-dialog v-model="dialog" persistent max-width="290">
            <v-card>
               <v-card-title class="headline">Terms and Conditions.</v-card-title>
                    <v-card-text>
                        Please confirm your admission contact with our organization and pay your course fee by bkash or rocket.<br>
                        (দয়া করে আপনার ভর্তি নিশ্চিত করতে আমাদের প্রতিষ্ঠানের সাথে যোগাযোগ করুন এবং বিকাশ বা রকেট এর মাধ্যমে আপনার কোর্স ফি প্রদান করুন।)
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" text @click="()=>{dialog = false;terms = false}">Disagree</v-btn>
                        <v-btn color="green darken-1" text @click="()=>{dialog = false;terms = true}">Agree</v-btn>
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
    data: vm=>({
        dialog: false,
        terms: false,
        snackbar: false,
        pay:  vm.course.fees >=3000 && vm.course.fees <= 7000 ?  Math.round(vm.course.fees / 2) : vm.course.fees >=8000 && vm.course.fees <= 15000 ?  Math.round(vm.course.fees / 4) : Math.round(vm.course.fees / 5),
        due: Math.abs(vm.course.fees - (vm.course.fees >=3000 && vm.course.fees <= 7000 ?  Math.round(vm.course.fees / 2) : vm.course.fees >=8000 && vm.course.fees <= 15000 ?  Math.round(vm.course.fees / 4) : Math.round(vm.course.fees / 5)))
    }),
    watch:{
        terms(val){
            this.dialog = val
        }
    },
    props:['course','student_id','batch_id'],
    methods: {
        submit(){
            const formData = new FormData();
            formData.append('course_id',this.course.id);
            formData.append('student_id',this.student_id.id);
            formData.append('fees',this.course.fees);
            this.batch_id ? formData.append('batch_id',this.batch_id) : '';
            this.$inertia.post(this.$route('student.store'),formData)
                .then(()=>{
                    this.snackbar = true;
                }).catch(err => console.dir(err))
        }
    },
    components:{Layout}
}
</script>
