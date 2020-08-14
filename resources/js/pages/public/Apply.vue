<template>
    <Layout title="Apply - UTC">
        <v-container>
            <v-row justify="center">
                <v-col cols="8">
                    <v-card>
                    <v-card-title class="title font-weight-regular justify-space-between">
                        <span>{{ currentTitle }}</span>
                            <v-avatar color="primary lighten-2" class="subheading white--text" size="24" v-text="step"></v-avatar>
                    </v-card-title>

                    <v-window v-model="step">
                        <v-window-item :value="1">
                            <v-card-text>
                                <div class="d-flex">
                                    <v-file-input accept="image/*" @change="setPreview" prepend-icon="mdi-camera" outlined required v-model="form.avatar" label="Choose a avatar"></v-file-input>
                                    <!-- <v-spacer></v-spacer> -->
                                    <div class="ml-6 mb-5">
                                        <v-img height="150" width="150" :src="setImg"></v-img>
                                    </div>
                                </div>
                                <v-text-field v-model="form.name" outlined label="Full Name" required></v-text-field>
                                <div class="d-flex">
                                    <v-menu v-model="menu2" :close-on-content-click="false" transition="scale-transition"
                                        offset-y max-width="290px" min-width="290px">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field v-model="computedDateFormatted" label="Date of Birth" hint="MM/DD/YYYY format"
                                                persistent-hint readonly outlined required v-bind="attrs" v-on="on">
                                            </v-text-field>
                                        </template>
                                        <v-date-picker v-model="form.dob" no-title @input="menu2 = false"></v-date-picker>
                                    </v-menu>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="form.nationality" outlined label="Nationality" required></v-text-field>
                                </div>
                                <div class="d-flex">
                                    <v-radio-group v-model="form.gender" row>
                                        <label class="mr-5 grey--text text--darken-1">Gender</label>
                                        <v-radio label="Male" value="male"></v-radio>
                                        <v-radio label="Female" value="female"></v-radio>
                                    </v-radio-group>
                                    <v-spacer></v-spacer>
                                    <v-radio-group v-model="form.marital_status" row>
                                        <label class="mr-5 grey--text text--darken-1">Marital Status</label>
                                        <v-radio label="Single" value="single"></v-radio>
                                        <v-radio label="Married" value="married"></v-radio>
                                    </v-radio-group>
                                </div>
                                    <v-text-field v-model="form.email" outlined label="E-mail Address" required></v-text-field>
                                    <v-text-field v-model="form.mother_name" outlined label="Mother's Name" required></v-text-field>
                                    <v-text-field v-model="form.father_name" outlined label="Father's Name" required></v-text-field>
                                    <v-text-field v-model="form.emergency_number" outlined label="Emergency Contact Number" required></v-text-field>
                                    <v-text-field v-model="form.institute_name" outlined label="Institute Name" required></v-text-field>
                                    <v-text-field v-model="form.profession" outlined label="Profession/Occupation" required></v-text-field>
                                    <v-text-field v-model="form.academic_status" outlined label="Academic Status" required></v-text-field>
                                </v-card-text>
                            </v-window-item>

                            <v-window-item :value="2">
                                <v-card-text>
                                    <v-text-field v-model="form.email" label="E-mail" required prepend-icon="mdi-email" outlined></v-text-field>
                                    <v-text-field outlined prepend-icon="mdi-lock" label="Password" type="password"  v-model="form.password" required :rules="passwordRules"></v-text-field>
                                    <v-text-field prepend-icon="mdi-lock-reset" outlined @input="matchPassword" label="Confirm Password" :error-messages="passwordConfirm" type="password"  v-model="confirm_password"></v-text-field>
                                </v-card-text>
                            </v-window-item>

                            <v-window-item :value="3">
                                <v-card-text>
                                    <v-select outlined label="Course Category" required :items="courseCats"></v-select>
                                    <v-select outlined label="Course Name" required :items="courses"></v-select>
                                    <v-text-field v-model="form.fees" disabled outlined label="Course Fees"></v-text-field>
                                    <!-- <v-text-field v-model="form.fees" disabled outlined label="Course Fees"></v-text-field> -->
                                </v-card-text>
                            </v-window-item>
                        </v-window>

                        <v-divider></v-divider>

                        <v-card-actions>
                            <v-btn
                                :disabled="step === 1"
                                text
                                @click="step--"
                            >
                                Back
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" depressed @click="submit">
                                {{step === 3 ? 'Submit' : 'Next'}}
                            </v-btn>
                        </v-card-actions>
                </v-card>
                </v-col>
            </v-row>
        </v-container>
    </Layout>
</template>
<script>
import Layout from '@/shared/public/Layout'
export default {
    remember: {
        data: ['step'],
    },
    data: vm => ({
      step: 1,
      form: {
          name: '',
          avatar: [],
          email: '',
          password: '',
          mother_name: '',
          father_name: '',
          dob: new Date().toISOString().substr(0, 10),
          gender: '',
          number: '',
          parents_number: '',
          address: '',
          profession: '',
          collage_name: '',
          nationality: '',
          marital_status: ''
      },
      confirm_password: '',
      passwordConfirm: [],
      passwordRules: [
            v=> !!v || 'Password is required', 
            v=> (v && v.length >= 6) || 'Password must have at least 6 letters.',
            v => /[!@#$%^&*]/.test(v) || 'Password must have a special character.',
            v => /[a-z]/.test(v) || 'Password must have a lowercase letter.',
            v => /[A-Z]/.test(v) || 'Password must have a uppercase letter.',
            v => /[0-9]/.test(v) || 'Password must have a number.',
        ],
      menu2: false,
      setImg: '',
      courseCats: [],
      courses: [],
      dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
    }),
    watch: {
      date (val) {
        this.dateFormatted = this.formatDate(this.form.dob)
      },
    },
    computed: {
        computedDateFormatted () {
        return this.formatDate(this.form.dob)
      },
      currentTitle () {
        switch (this.step) {
          case 1: return 'Personal Info'
          case 2: return 'Account create'
          default: return 'Course & Payment Info'
        }
      },
    },
    mounted(){
        this.setImg = this.$page.baseUrl+'storage/images/users/default.png';
    },
    methods: {
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
      formatDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${month}/${day}/${year}`
      },
      parseDate (date) {
        if (!date) return null

        const [month, day, year] = date.split('/')
        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
      },
      matchPassword(value){
            if(this.form.password !== value){
                this.passwordConfirm.push('password are not identical');
            }else{
                this.passwordConfirm = [];
            }
        },
        submit(){
            if(this.step !==3){
                this.step++
            }else{
                console.log('submit');
            }
        }
    },
    components:{Layout}
}
</script>