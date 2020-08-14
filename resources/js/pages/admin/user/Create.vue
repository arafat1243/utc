<template>
    <v-app>
        <v-main>
            <v-app-bar app color="white" elevation="1">
                <v-toolbar-title>
                    <div class="brand-text ml--3" style="font-size: 1.3em !important">Universal Technology Corporation</div>
                </v-toolbar-title>
            </v-app-bar>
            <v-container>
                <v-row justify="center">
                    <v-col cols="8">
                        <v-card>
                            <v-alert type="info">Welcome. Please complete your profile</v-alert>
                            <v-card-title class="title font-weight-regular justify-space-between">
                            <span>{{ currentTitle }}</span>
                            <v-avatar
                                color="primary lighten-2"
                                class="subheading white--text"
                                size="24"
                                v-text="step"
                            ></v-avatar>
                            </v-card-title>
                            <v-form ref="form" method="post" @submit.prevent="submit" enctype="multipart/form-data">
                            <v-window v-model="step">
                            <v-window-item :value="1">
                                <v-card-text>
                                    <v-text-field label="Full Name"  required :rules="[v => !!v || 'Name is required', v=> (v && v.length < 50) || 'Name must be at most 50 characters long']" outlined prepend-icon="mdi-account" v-model="name"></v-text-field>
                                    <v-text-field disabled label="E-mail" prepend-icon="mdi-email" outlined :value="$page.auth.email"></v-text-field>
                                    <v-text-field label="Contact Number" @keypress="onlyNumber" :rules="numberRules" prepend-icon="mdi-phone" outlined v-model="number"></v-text-field>
                                    <v-file-input label="CV" outlined accept="application/pdf" required v-model="cover_Letter" :rules="[v => !!v || 'CV is required',value => (value && value.size < 10000000) || 'CV size should be less than 10 MB!']"></v-file-input>
                                </v-card-text>
                            </v-window-item>

                            <v-window-item :value="2">
                                <v-card-text>
                                <v-text-field outlined prepend-icon="mdi-lock" label="Password" type="password"  v-model="password" required :rules="passwordRules"></v-text-field>
                                <v-text-field prepend-icon="mdi-lock-reset" outlined @input="matchPassword" label="Confirm Password" :error-messages="passwordConfirm" type="password"  v-model="confirm_password"></v-text-field>
                                <span class="caption grey--text text--darken-1">
                                    Please enter a password for your account
                                </span>
                                </v-card-text>
                            </v-window-item>

                            <v-window-item :value="3">
                                <div class="pa-4 text-center">
                                <v-avatar size="150">
                                    <v-img class="mb-4" contain height="128" :src="setImg"></v-img>
                                </v-avatar>
                                <v-file-input label="Avatar" @change="setPreview" show-size counter accept="image/*" :rules="[v => !!v || 'Avatar is required',value => (value && value.size < 5000000) || 'Avatar size should be less than 5 MB!']" prepend-icon="mdi-camera" outlined v-model="avatar"></v-file-input>
                                </div>
                            </v-window-item>
                            </v-window>

                            <v-divider></v-divider>

                            <v-card-actions>
                            <v-btn :disabled="step === 1" text @click="step--">
                                Back
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" depressed v-if="step !== 3" @click="step++">
                                Next
                            </v-btn>
                            <v-btn color="primary" depressed v-else type="submit">
                                Submit
                            </v-btn>
                            
                            </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-col>
                </v-row>
                <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
                    {{$page.successMessage.message || error}}
                    <template v-slot:action="{ attrs }">
                        <v-btn text v-bind="attrs" @click="snackbar = false">
                        Close
                        </v-btn>
                    </template>
                </v-snackbar>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
export default {
    data: ()=>({
        title: 'Welcome',
        step: 1,
        name: '',
        password: '',
        confirm_password: '',
        avatar: [],
        number: '01',
        cover_Letter: [],
        setImg: '',
        error: '',
        snackbar: false,
        numberRules: [
            v => !!v || 'Contact Number is required',
            v => /^[01][0-9]{10}$/.test(v) || 'Number is not valid'
        ],
        passwordConfirm:[],
        passwordRules: [
            v=> !!v || 'Password is required', 
            v=> (v && v.length >= 6) || 'Password must have at least 6 letters.',
            v => /[!@#$%^&*]/.test(v) || 'Password must have a special character.',
            v => /[a-z]/.test(v) || 'Password must have a lowercase letter.',
            v => /[A-Z]/.test(v) || 'Password must have a uppercase letter.',
            v => /[0-9]/.test(v) || 'Password must have a number.',
        ]
    }),
    computed: {
      currentTitle () {
        switch (this.step) {
          case 1: return 'Setup profile'
          case 2: return 'Create a new password'
          default: return 'Choose a avatar'
        }
      },
    },
    mounted(){
        this.name = this.$page.auth.name,
        this.setImg = this.$page.baseUrl + this.$page.auth.avatar,
        this.snackbar = $page.successMessage.success;
    },
    watch: {
      title: {
        immediate: true,
        handler(title) {
          document.title = title  
        },
      }
    },
    methods:{
        onlyNumber ($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if (keyCode < 48 || keyCode > 57) { // 46 is dot
                $event.preventDefault();
            }
        },
        submit(){
            if(this.$refs.form.validate()){
                let formData = new FormData();
                formData.append('name',this.name),
                formData.append('number',this.number),
                formData.append('password',this.password),
                formData.append('cover_Letter',this.cover_Letter),
                formData.append('avatar',this.avatar);
                this.$inertia.post(this.$route('users.store'), formData, {
                replace: false,
                preserveState: true,
                preserveScroll: false,
                only: [],
              }).then(re => {
                      this.snackbar = true;
                      if(this.$page.successMessage.success){
                          this.$refs.form.reset()
                      }
                      if(this.$page.errors.number){
                        this.error = this.$page.errors.number[0];
                        this.snackbar = true
                      }
                  })
                  .catch(err => console.log(err));
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
            }else{
                this.setImg = this.$page.baseUrl + this.$page.auth.avatar;
            }
        },
        matchPassword(value){
            if(this.password !== value){
                this.passwordConfirm.push('password are not identical');
            }else{
                this.passwordConfirm = [];
            }
        }
    }
}
</script>