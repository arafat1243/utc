<template>
    <Layout title="Profile Manager">
        <v-container>
            <v-row no-gutters justify="center">
                <v-col cols="12" md="8">
                    <v-card >
                        <v-card-title>
                            <v-avatar tile size="150">
                            <v-img :src="user.avatar"></v-img>
                        </v-avatar>
                        </v-card-title>
                        <v-card-text>
                            <v-form ref="profileForm">
                            <v-text-field outlined @keypress="onlyChar" autocomplete="off" :readonly="!read"
                            :rules="[v => !!v || 'Name is required.',v => (v && (v.length >= 10 && v.length <= 40)) || 'Name must be between 10-40 characters']"
                             label="Full Name" required v-model="form.name"></v-text-field>
                            <v-text-field outlined @keypress="onlyChar" autocomplete="off" :readonly="!read"
                            :rules="[v => !!v || 'Mother\'s Name is required.',v => (v && (v.length >= 10 && v.length <= 40)) || 'Mother\'s Name must be between 10-40 characters']"
                             label="Mother's Name" required v-model="form.mother_name"></v-text-field>
                            <v-text-field outlined @keypress="onlyChar" autocomplete="off" :readonly="!read"
                            :rules="[v => !!v || 'Father\'s Name is required.',v => (v && (v.length >= 10 && v.length <= 40)) || 'Father\'s Name must be between 10-40 characters']"
                             label="Father's Name" required v-model="form.father_name"></v-text-field>
                            <div class="d-flex">
                                <v-menu ref="menu" v-model="menu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="290px">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field :rules="dobRules" v-model="form.dob" label="Date of Birth"
                                            readonly outlined  v-bind="attrs" v-on="on" ></v-text-field>
                                    </template>
                                    <v-date-picker ref="picker" :readonly="!read" v-model="form.dob" :max="new Date().toISOString().substr(0, 10)"
                                        min="1950-01-01" @change="save"></v-date-picker>
                                </v-menu>
                                <v-spacer></v-spacer>
                                <v-text-field :readonly="!read" v-model="form.nationality" autocomplete="off" @keypress="onlyChar" :rules="[v=> !!v || 'Nationality is required.']" outlined label="Nationality" required></v-text-field>
                            </div>
                            <div class="d-flex">
                                <v-radio-group readonly v-model="user.student.gender" :rules="[v => !!v || 'Gender is requried.']" row>
                                    <label class="mr-5 grey--text text--darken-1">Gender</label>
                                    <v-radio label="Male" value="male"></v-radio>
                                    <v-radio label="Female" value="female"></v-radio>
                                </v-radio-group>
                                <v-spacer></v-spacer>
                                <v-radio-group :readonly="!read" v-model="form.marital_status" :rules="[v => !!v || 'Marital Status is requried.']" row>
                                    <label class="mr-5 grey--text text--darken-1">Marital Status</label>
                                    <v-radio label="Single" value="single"></v-radio>
                                    <v-radio label="Married" value="married"></v-radio>
                                </v-radio-group>
                            </div>
                            <v-text-field :readonly="!read" autocomplete="off" @input="(va)=>{
                                this.form.number = va.replace(/[0-9]{12}$/,'')
                                .replace(/^(\d{5})(\d{6})/g,'$1-$2')
                                .substr(0,12);
                                }" v-model="form.number" outlined label="Contact Number" 
                                @keypress="($event)=>{let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                                    if (keyCode < 48 || keyCode > 57) { // 46 is dot
                                        $event.preventDefault();
                                    }}" :rules="[v=>!!v|| 'Number is requried.', v => (v && (v.length <= 12 && v.length >= 12)) || 'Number is not valid']"
                            required></v-text-field>
                            <v-text-field :readonly="!read" autocomplete="off" @input="num" v-model="form.emergency_number" @keypress="($event)=>{let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                                if (keyCode < 48 || keyCode > 57) { // 46 is dot
                                    $event.preventDefault();
                                }}"
                            :rules="emergency_numberRules" outlined label="Emergency Contact Number" required></v-text-field>
                            <v-textarea :readonly="!read" v-model="form.permanent_address" :rules="[v=>!!v|| 'Address is requried.']"  outlined label="Permanent Address *" no-resize required></v-textarea>
                            <div style="width: 150px">
                                <v-checkbox :readonly="!read" v-model="same"  label="Both are same"></v-checkbox>
                            </div>
                            <v-textarea :readonly="same" v-model="form.present_address" :rules="[v=>!!v|| 'Address is requried.']" outlined label="Present Address" no-resize required></v-textarea>
                            <v-text-field :readonly="!read" v-model="form.institute_name" @keypress="onlyChar" :rules="[v=>!!v|| 'Institute name is requried.']" outlined label="Institute Name" required></v-text-field>
                            <v-text-field :readonly="!read" v-model="form.profession" @keypress="onlyChar" :rules="[v=>!!v|| 'Profession/Occupation is requried.']" outlined label="Profession/Occupation" required></v-text-field>
                            <v-text-field :readonly="!read" v-model="form.academic_status" :rules="[v=>!!v|| 'Academic Status is requried.']" outlined label="Academic Status" required></v-text-field>
                            <v-select :readonly="!read" v-model="form.blood_group" :items="bloodGroups" clearable outlined label="Select Blood Group"></v-select>
                            <v-divider></v-divider>
                            <v-text-field class="mt-5" outlined readonly prepend-icon="mdi-email" label="E-mail" type="email"  v-model="form.email"></v-text-field>
                            <v-text-field :readonly="!read" outlined prepend-icon="mdi-lock" label="Password" type="password" @input="pass" :rules="passwordRules" v-model="form.password"></v-text-field>
                            <v-text-field :readonly="!read" prepend-icon="mdi-lock-reset" outlined label="Confirm Password" type="password" :rules="confirm_passRules"  v-model="confirm_pass"></v-text-field>
                            <span class="caption grey--text text--darken-1">
                                Note: If you don't want to change password leave password filde empty.
                            </span>
                            <div style="width: 170px">
                                <v-checkbox v-model="read"  label="I want to Change"></v-checkbox>
                            </div>
                        </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="primary" :disabled="!read" @click="save">Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message || error}}
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
    data: vm =>({
        form:{
            name: vm.user.name,
            email: vm.user.email,
            mother_name: vm.user.student.mother_name,
            father_name: vm.user.student.father_name,
            nationality: vm.user.student.nationality,
            marital_status:  vm.user.student.marital_status,
            dob: vm.user.student.dob,
            number:  vm.user.student.number,
            emergency_number: vm.user.student.emergency_number,
            present_address: vm.user.student.present_address,
            permanent_address: vm.user.student.permanent_address,
            profession: vm.user.student.profession,
            academic_status: vm.user.student.academic_status,
            blood_group: vm.user.student.blood_group,
            institute_name: vm.user.student.institute_name,
            password: ''
        },
        passwordRules: [],
        dobRules: [],
        confirm_pass: '',
        confirm_passRules: [],
        emergency_numberRules: [],
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
        read: false,
        snackbar: false,
        error: '',
        same: vm.user.student.present_address == vm.user.student.permanent_address ? true : false,
        menu: false
    }),
    props:['user'],
    watch:{
        menu (val) {
            val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
        },
        confirm_pass(val){
            this.confirm_passRules = [
                val => val == this.form.password || 'password are not identical'
            ]
        }
    },
    mounted(){
        console.log();
    },
    methods:{
        onlyChar($event){
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                if ((keyCode < 65 || keyCode > 90 && keyCode < 97 || keyCode > 122) && keyCode !== 32) { // 46 is dot
                    $event.preventDefault();
                }
        },
        num(val){
            this.form.emergency_number = val.replace(/[0-9]{12}$/,'')
                                .replace(/^(\d{5})(\d{6})/g,'$1-$2')
                                .substr(0,12);
            this.emergency_numberRules =  [
                v=>!!v|| 'Emergency Number is requried.',
                v => (v && (v.length <= 12 && v.length >= 12)) || 'Number is not valid',
                v =>  {
                    if(v === this.form.number){
                        return 'Both Number are same';
                    }else{
                        return true
                    }
                }
            ]
        },
        save (date) {
            this.$refs.menu.save(date)
             this.dobRules = [
                v => !!v || 'Date of Birth is requried.',
                v => {
                    let curentDate = new Date().toISOString().substr(0, 10), dob = this.form.dob;
                    curentDate = curentDate.replace(/-/g,'');
                    dob = dob.replace(/-/g,'');
                    return Math.abs(curentDate - dob) > 140000 || 'you age must be 14+'
                }
            ]
        },
        pass(value){
            if(value){
                this.passwordRules = [
                    value=> (value && value.length >= 6) || 'Password must have at least 6 letters.',
                    value => /[!@#$%^&*]/.test(value) || 'Password must have a special character.',
                    value => /[a-z]/.test(value) || 'Password must have a lowercase letter.',
                    value => /[A-Z]/.test(value) || 'Password must have a uppercase letter.',
                    value => /[0-9]/.test(value) || 'Password must have a number.',
                ]
            }else{
                this.passwordRules = []
                this.confirm_passRules = []
            }
        },
        save(){
            if(this.$refs.profileForm.validate()){
                let formData = new FormData();
                for (let data in this.form) {
                    formData.append(data,this.form[data]);
                }
                this.$inertia.post(this.$route('student.update',this.$page.auth.id),formData, {
                preserveState: true,
                preserveScroll: true,
                }).then(re => {
                      this.snackbar = true;
                      if(this.$page.successMessage.success){
                          this.read = false
                      }
                      if(this.$page.errors.number){
                        this.error = this.$page.errors.number[0];
                      }
                  })
                  .catch(err => console.log(err));
            }
        }

    },
    components:{Layout}
}
</script>