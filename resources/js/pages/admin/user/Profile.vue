<template>
    <Layout title="Profile">
        <v-container>
            <v-row justify="center">
                <v-col cols="10">
                    <v-card>
                        <v-row justify="center">
                            <v-col cols="7">
                                <v-form ref="form" @submit.prevent="submit" method="post" enctype="multipart/form-data">
                                    <v-card-text>
                                    <v-file-input label="Avatar" @change="setPreview" show-size counter accept="image/*" prepend-icon="mdi-camera" outlined v-model="avatar"></v-file-input>
                                    <v-text-field label="Full Name" outlined v-model="name" required :rules="[v=> !!v || 'Name is required',v=> (v && v.length < 50) || 'Name must be at most 50 characters long']" prepend-icon="mdi-account"></v-text-field>
                                    <v-text-field label="E-mail" outlined v-model="email" disabled prepend-icon="mdi-email"></v-text-field>
                                    <v-text-field label="Contact Number" v-if="user.employe" @keypress="onlyNumber" required :rules="numberRules" prepend-icon="mdi-phone" outlined v-model="number"></v-text-field>
                                    <v-text-field outlined prepend-icon="mdi-lock" label="Password" type="password" @input="pass"  v-model="password" :rules="passwordRules"></v-text-field>
                                    <v-text-field prepend-icon="mdi-lock-reset" outlined @input="matchPassword" label="Confirm Password" :error-messages="passwordConfirm" type="password"  v-model="confirm_password"></v-text-field>
                                    <span class="caption grey--text text--darken-1">
                                        Note: If you don't want to change password leave password filde empty.
                                    </span>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn color="primary" type="submit">Save</v-btn>
                                    </v-card-actions>
                                </v-form>
                            </v-col>
                            <v-col cols="4">
                                <v-avatar tile size="150">
                                    <v-img :src="setAvatar"></v-img>
                                </v-avatar>
                                <v-col cols="12">
                                    <v-card-title>Roles & Permissions</v-card-title>
                                    <ol>
                                        <li v-for="(role,i) in roles" :key="i">
                                            <samp>{{title(role.title)}}</samp><br>
                                            <span style="margin-left: 10px" v-for="(permission,i) in role.permissions" :key="i">{{i+1}}.{{permissions(permission)}}</span>
                                        </li>
                                    </ol>
                                </v-col>
                            </v-col>
                            <v-col cols="12" v-if="user.employe">
                                <embed width="100%" height="500" :src=" $page.baseUrl+user.employe.cv" type="application/pdf">
                            </v-col>
                        </v-row>
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
import Layout from '@/shared/admin/Layout'
export default {
    data: ()=>({
        setAvatar: '',
        avatar: [],
        snackbar: false,
        name: '',
        email: '',
        password: '',
        number: '',
        error: '',
        confirm_password: '',
        passwordConfirm: [],
        numberRules: [
            v => !!v || 'Contact Number is required',
            v => /^[01][0-9]{10}$/.test(v) || 'Number is not valid'
        ],
        passwordRules: [],
        roles : {}
    }),
    mounted(){
        this.init();
    },
    props: ['user'],
    methods:{
        init(){
            this.setAvatar = this.$page.baseUrl+this.user.avatar;
            this.name = this.user.name;
            this.roles = this.user.roles;
            this.email = this.user.email;
            this.snackbar = this.$page.successMessage.success;
            this.user.employe ? this.number = this.user.employe.number : '';
        },
        setPreview(file){
            if (file) {
                console.log(file);
                var reader = new FileReader();
                const _this = this; 
                reader.addEventListener('load',function(){
                    _this.setAvatar = this.result
                })
                reader.readAsDataURL(file); // convert to base64 string
            }else{
                this.setAvatar = this.$page.baseUrl + this.$page.auth.avatar;
            }
        },
         onlyNumber ($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if (keyCode < 48 || keyCode > 57) { // 46 is dot
                $event.preventDefault();
            }
        },
        matchPassword(value){
            if(this.password !== value){
                this.passwordConfirm.push('password are not identical');
            }else{
                this.passwordConfirm = [];
            }
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
            }
        },
        title(title){
            return title.replace('_',' ').toUpperCase()
        },
        permissions(permission){
            return permission.replace('_',' ').toLowerCase()
        },
        submit(){
            if(this.$refs.form.validate()){
                let formData = new FormData();
                formData.append('name',this.name),
                formData.append('number',this.number || ''),
                formData.append('avatar',this.avatar || ''),
                formData.append('password',this.password || ''),
                this.$inertia.post(this.$route('users.update',this.$page.auth.id),formData, {
                replace: false,
                preserveState: true,
                preserveScroll: false,
                only: [],
              }).then(re => {
                      this.snackbar = true;
                      if(this.$page.successMessage.success){
                        //   this.$refs.form.reset()
                          this.init();
                      }
                      if(this.$page.errors.number){
                        this.error = this.$page.errors.number[0];
                        this.snackbar = true
                      }
                  })
                  .catch(err => console.log(err));
            }
        }
    },
    components:{Layout}
}
</script>