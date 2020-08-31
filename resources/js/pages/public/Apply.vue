<template>
    <Layout title="Apply - UTC">
        <v-container>
            <v-row justify="center">
                <v-col cols="12" md="8">
                    <v-card id="scroll-target">
                    <v-card-title v-if="step !== 4" class="title font-weight-regular justify-space-between">
                        <span>{{ currentTitle }}</span>
                            <v-avatar color="primary lighten-2" class="subheading white--text" size="24" v-text="step"></v-avatar>
                    </v-card-title>
                    <v-window v-model="step">
                        <v-window-item :value="1">
                            <v-form ref="personalInfo">
                                <v-card-text>
                                <div class="d-flex">
                                    <!-- :rules="[v => !!v || 'Avatar is required',value => (value && value.size < 4000000) || 'Avatar size should be less than 2 MB!',]" -->
                                    <v-file-input accept="image/*" @change="setPreview" prepend-icon="mdi-camera"
                                    :rules="[ value => !value || value.size < 4000000 || 'Avatar size should be less than 4 MB!']"
                                    outlined required v-model="form.avatar" label="Choose a avatar *"></v-file-input>
                                    <!-- <v-spacer></v-spacer> -->
                                    <div class="ml-6 mb-5">
                                        <v-img height="150" width="150" :src="setImg"></v-img>
                                    </div>
                                </div>
                                <!-- :rules="[v => !!v || 'Name is required',v=> v => (v && v.length >= 10) || 'You Must input Minimum 10 characters']" -->
                                <v-text-field v-model="form.name" @keypress="onlyChar" counter :rules="[v => !!v || 'Name is required.',v => (v && (v.length >= 10 && v.length <= 40)) || 'Name must be between 10-40 characters']"  outlined label="Full Name *" required></v-text-field>
                                <div class="d-flex">
                                    <v-menu
                                    ref="menu"
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="form.dob"
                                        label="Date of Birth *"
                                        :rules="[v => !!v || 'Date of Birth is requried.']"
                                        readonly
                                        outlined
                                        :error-messages="error.dob"
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                    </template>
                                    <v-date-picker
                                    ref="picker"
                                    v-model="form.dob"
                                    :max="new Date().toISOString().substr(0, 10)"
                                    min="1950-01-01"
                                    @change="save"
                                    ></v-date-picker>
                                </v-menu>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="form.nationality" @keypress="onlyChar" :rules="[v=> !!v || 'Nationality is required.']" outlined label="Nationality *" required></v-text-field>
                                </div>
                                <div class="d-flex">
                                    <v-radio-group v-model="form.gender" :rules="[v => !!v || 'Gender is requried.']" row>
                                        <label class="mr-5 grey--text text--darken-1">Gender *</label>
                                        <v-radio label="Male" value="male"></v-radio>
                                        <v-radio label="Female" value="female"></v-radio>
                                    </v-radio-group>
                                    <v-spacer></v-spacer>
                                    <v-radio-group v-model="form.marital_status" :rules="[v => !!v || 'Marital Status is requried.']" row>
                                        <label class="mr-5 grey--text text--darken-1">Marital Status *</label>
                                        <v-radio label="Single" value="single"></v-radio>
                                        <v-radio label="Married" value="married"></v-radio>
                                    </v-radio-group>
                                </div>
                                    <v-text-field autocomplete="off" v-model="form.number" @input="onlyNumber" outlined label="Contact Number *" 
                                    @keypress="($event)=>{let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                                        if (keyCode < 48 || keyCode > 57) { // 46 is dot
                                            $event.preventDefault();
                                        }}" :rules="[v=>!!v|| 'Number is requried.', v => (v && (v.length <= 12 && v.length >= 12)) || 'Number is not valid']"
                                    required></v-text-field>
                                    <v-text-field v-model="form.mother_name" @keypress="onlyChar"
                                        :rules="[v => !!v || 'Mother\'s Name is required.',v => (v && (v.length >= 10 && v.length <= 40)) || 'Mother\'s Name must be between 10-40 characters']"
                                      outlined label="Mother's Name *" required></v-text-field>
                                    <v-text-field v-model="form.father_name" @keypress="onlyChar"
                                    :rules="[v => !!v || 'Father\'s Name is required.',v => (v && (v.length >= 10 && v.length <= 40)) || 'Father\'s Name must be between 10-40 characters']"
                                     outlined label="Father's Name *" required></v-text-field>
                                    <v-text-field autocomplete="off" v-model="form.emergency_number" @input="emNumber" @keypress="($event)=>{let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                                        if (keyCode < 48 || keyCode > 57) { // 46 is dot
                                            $event.preventDefault();
                                        }}" :error-messages="error.sameNum" :rules="[v=>!!v|| 'Number is requried.', v => (v && (v.length <= 12 && v.length >= 12)) || 'Number is not valid']" outlined label="Emergency Contact Number *" required></v-text-field>
                                    <v-textarea v-model="form.permanent_address" :rules="[v=>!!v|| 'Address is requried.']"  outlined label="Permanent Address *" no-resize required></v-textarea>
                                    <div style="width: 150px">
                                        <v-checkbox v-model="same"  label="Both are same"></v-checkbox>
                                    </div>
                                    <v-textarea :readonly="same" v-model="form.present_address" :rules="[v=>!!v|| 'Address is requried.']" outlined label="Present Address *" no-resize required></v-textarea>
                                    <v-text-field v-model="form.institute_name" @keypress="onlyChar" :rules="[v=>!!v|| 'Institute name is requried.']" outlined label="Institute Name *" required></v-text-field>
                                    <v-text-field v-model="form.profession" @keypress="onlyChar" :rules="[v=>!!v|| 'Profession/Occupation is requried.']" outlined label="Profession/Occupation *" required></v-text-field>
                                    <v-text-field v-model="form.academic_status" :rules="[v=>!!v|| 'Academic Status is requried.']" outlined label="Academic Status *" required></v-text-field>
                                    <v-select v-model="form.blood_group" :items="bloodGroups" clearable outlined label="Select Blood Group"></v-select>
                                </v-card-text>      
                            <v-card-subtitle class="info--text">* mark field must be required</v-card-subtitle>
                        </v-form>
                        </v-window-item>

                            <v-window-item :value="2">
                                <v-form ref="accountInfo">
                                    <v-card-text>
                                    <v-text-field v-model="form.email" label="E-mail *" 
                                    :rules="[v => !!v || 'E-mail is required', v => /.+@.+\..+/.test(v) || 'E-mail must be valid',]"
                                     required prepend-icon="mdi-email" outlined></v-text-field>
                                    <v-text-field outlined prepend-icon="mdi-lock" label="Password *" type="password"  v-model="form.password" required :rules="passwordRules"></v-text-field>
                                    <v-text-field prepend-icon="mdi-lock-reset" outlined @input="matchPassword" label="Confirm Password *" :error-messages="passwordConfirm" type="password"  v-model="confirm_password"></v-text-field>
                                </v-card-text>
                                </v-form>
                                <v-card-subtitle class="info--text">* mark field must be required</v-card-subtitle>
                            </v-window-item>

                            <v-window-item :value="3">
                                <v-form ref="courseInfo">
                                    <v-card-text>
                                        <v-text-field :value="course.title" readonly outlined label="Course Name"></v-text-field>
                                    <div class="d-flex">
                                        <v-text-field :value="course.course_duration" readonly outlined label="Course Duration"></v-text-field>
                                        <v-spacer></v-spacer>
                                        <v-text-field :value="course.class_duration" readonly outlined label="Class Duration"></v-text-field>
                                    </div>
                                    <div class="d-flex">
                                        <v-text-field :value="course.class_count" readonly outlined label="Total Class"></v-text-field>
                                        <v-spacer></v-spacer>
                                        <v-text-field :value="course.fees" readonly outlined label="Course Fees"></v-text-field>
                                    </div>
                                    <div class="d-flex">
                                        <v-text-field :value="form.pay_amount" readonly outlined label="Pay Amount"></v-text-field>
                                        <v-spacer></v-spacer>
                                        <v-text-field :value="form.due_amount" readonly outlined label="Due Amount"></v-text-field>
                                    </div>
                                    <v-checkbox :rules="[v=> !!v || '']" v-model="terms" label="Terms and Conditions"></v-checkbox>
                                </v-card-text>
                                </v-form>
                                <v-card-subtitle class="info--text">* mark field must be required</v-card-subtitle>
                            </v-window-item>
                            <v-window-item :value="4">
                                    <v-row justify="center">
                                        <v-col cols="12">
                                            <v-img :src="success"></v-img>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-card-text class="text-center">
                                                Thanks for taking the admission.<br>
                                                You have successfully taken admission in this course.<br>
                                                Please contact this number <strong>01812-669757</strong> to confirm your admission. 
                                                <br>You will be notified by e-mail when your course will confirm.
                                                <p class="text-right">Thank you.</p>
                                            </v-card-text>
                                        </v-col>
                                    </v-row>
                            </v-window-item>
                        </v-window>
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
                        <v-divider></v-divider>

                        <v-card-actions v-if="step !== 4">
                            <v-btn
                                :disabled="step === 1"
                                text
                                @click="step--"
                            >
                                Back
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" :disabled="disabled" depressed @click="submit">
                                {{step === 3 ? 'Submit' : 'Next'}}
                            </v-btn>
                        </v-card-actions>
                </v-card>
                </v-col>
            </v-row>
            <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message || errorMessage}}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                Close
                </v-btn>
            </template>
        </v-snackbar>
        </v-container>
    </Layout>
</template>
<script>
import Layout from '@/shared/public/Layout'
export default {
    data: vm => ({
      step: 1,
      dialog: false,
      terms: false,
      snackbar: false,
      same: false,
      bloodGroups: [
          'a+',
          'a-',
          'b+',
          'ab-',
          'o+',
          'o-',
          'ab+',
          'ab-',
      ],
      form: {
          'courseId': vm.course.id,
          'fees': vm.course.fees,
          'pay_amount': vm.course.fees >=3000 && vm.course.fees <= 7000 ?  Math.round(vm.course.fees / 2) : vm.course.fees >=8000 && vm.course.fees <= 15000 ?  Math.round(vm.course.fees / 4) : Math.round(vm.course.fees / 5),
          'due_amount': Math.round(vm.course.fees - (vm.course.fees >=3000 && vm.course.fees <= 7000 ?  Math.round(vm.course.fees / 2) : vm.course.fees >=8000 && vm.course.fees <= 15000 ?  Math.round(vm.course.fees / 4) : Math.round(vm.course.fees / 5)))
      },
      courseInfo: {},
      confirm_password: '',
      passwordConfirm: '',
      passwordRules: [
            v=> !!v || 'Password is required', 
            v=> (v && v.length >= 6) || 'Password must have at least 6 letters.',
            v => /[!@#$%^&*]/.test(v) || 'Password must have a special character.',
            v => /[a-z]/.test(v) || 'Password must have a lowercase letter.',
            v => /[A-Z]/.test(v) || 'Password must have a uppercase letter.',
            v => /[0-9]/.test(v) || 'Password must have a number.',
        ],
      menu: false,
      setImg: '',
      error: {},
      success: '',
      errorMessage: ''
    }),
    props:['course','batch_id'],
    watch: {
      terms(){
          this.dialogShow()
      },
      same(val){
          if(val === true){
              this.form.present_address = this.form.permanent_address
          }else{
             this.form.present_address = '' 
          }
      },
      menu (val) {
        val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
      },
    },
    computed: {
      currentTitle () {
        switch (this.step) {
          case 1: return 'Personal Info'
          case 2: return 'Account create'
          default: return 'Course & Payment Info'
        }
      },
      disabled(){
          if(this.step === 1){
              if(this.form.avatar && this.form.name && this.form.dob && this.form.nationality &&
              this.form.gender && this.form.marital_status && this.form.number && this.form.mother_name &&
              this.form.father_name && this.form.emergency_number && this.form.permanent_address && this.form.present_address && 
              this.form.institute_name && this.form.profession && this.form.academic_status){
              return false
            }
          }
          else if(this.step === 2){
              if(this.form.email && this.form.password && this.confirm_password){
                  return false
              }
          }
          else if(this.step === 3){
              if(this.terms){
                  return false
              }
          }
        return true
      }
    },
    mounted(){
        this.setImg = this.$page.baseUrl+'storage/images/users/default.png';
        this.success = this.$page.baseUrl+'storage/images/others/check.svg';
    },
    methods: {
        onlyNumber(value){
            if(value){
                this.form.number = value.replace(/[0-9]{12}$/,'')
                                .replace(/^(\d{5})(\d{6})/g,'$1-$2')
                                .substr(0,12);
            }
        },
        emNumber(value){
            if(value){
                this.form.emergency_number = value.replace(/[0-9]{12}$/,'')
                                .replace(/^(\d{5})(\d{6})/g,'$1-$2')
                                .substr(0,12);
            this.form.emergency_number == this.form.number ? this.error.sameNum = 'Both Number are same. please Enter deferent Number.' : this.error.sameNum = ''
            }
        },
        onlyChar($event){
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                if ((keyCode < 65 || keyCode > 90 && keyCode < 97 || keyCode > 122) && keyCode !== 32) { // 46 is dot
                    $event.preventDefault();
                }
        },
        save (date) {
            this.$refs.menu.save(date)
            let curentDate = new Date().toISOString().substr(0, 10), dob = this.form.dob;
            curentDate = curentDate.replace(/-/g,'');
            dob = dob.replace(/-/g,'');
            Math.abs(curentDate - dob) < 140000 ? this.error.dob = 'you age must be 14+' : this.error.dob = ''
      },
        dialogShow(){
            if(this.terms){
                this.dialog = this.terms;
            }
        },
        setPreview(file){
            if (file) {
                var reader = new FileReader();
                const _this = this; 
                reader.addEventListener('load',function(){
                    _this.setImg = this.result
                })
                reader.readAsDataURL(file); // convert to base64 string
            } 
            else{
                this.setImg = this.$page.baseUrl+'storage/images/users/default.png';
            }  
        },
      matchPassword(value){
            if(this.form.password !== value){
                this.passwordConfirm = 'password are not identical';
            }else{
                this.passwordConfirm = '';
            }
        },
        submit(){
            if((this.step === 1 && this.$refs.personalInfo.validate() && this.error.dob === '' && this.error.sameNum === '')
             || (this.step === 2 && this.$refs.accountInfo.validate() && this.passwordConfirm== '') || (this.step === 3 && this.$refs.courseInfo.validate())) {
                this.$vuetify.goTo('#scroll-target', {duration: 200,offset: 300, easing: 'easeInOutCubic'});
                if(this.step !==3){
                    this.step++
                }else{
                    if(this.$refs.personalInfo.validate() && this.error.dob == '' && this.error.sameNum == '' &&
                     this.$refs.accountInfo.validate() && this.passwordConfirm == '' && this.$refs.courseInfo.validate()){
                        let formData = new FormData();
                    for(let data in this.form){
                        formData.append(data,this.form[data])
                    }
                    this.$inertia.post(this.$route('public.apply.store'),formData)
                        .then(()=>{
                            if(this.$page.successMessage.success){
                                this.form = {},
                                this.confirm_password = '',
                                this.passwordConfirm = [],
                                this.error = {},
                                this.errorMessage = '',
                                this.batch_id = '';
                                this.$refs.personalInfo.reset(),
                                this.$refs.accountInfo.reset(),
                                this.$refs.courseInfo.reset();
                                this.step = 4
                            }
                            else if(this.$page.errors != null){
                                if(this.$page.errors.email){
                                    this.errorMessage = this.$page.errors.email[0];
                                }
                                else if(this.$page.errors.number){
                                    this.errorMessage = this.$page.errors.number[0];
                                }
                                else{
                                    this.errorMessage = this.$page.errors.emergency_number[0];
                                }
                                
                                this.snackbar = true
                            }
                            else{
                                this.snackbar = true;
                            }
                        }).catch(err => console.dir(err))
                    }
                }
            }
        },
    },
    components:{Layout}
}
</script>