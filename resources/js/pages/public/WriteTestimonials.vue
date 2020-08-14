<template>
    <Pages title="Write Testimonials - UTC">
        <v-form ref="from" @submit.prevent="submit" method="post" enctype="multipart/form-data">
            <v-row justify="center">
                <v-col cols="12" md="9">
                    <v-file-input outlined accept="image/*" show-size counter :rules="imageRules" v-model="avatar" label="Upload Image"></v-file-input>
                </v-col>
                <v-col cols="12" md="9">
                    <v-text-field v-model="name" required :error-messages="nameErrors" :counter="50"
                    @input="$v.name.$touch()" @blur="$v.name.$touch()" outlined label="Name"></v-text-field>
                </v-col>
                <v-col cols="12" md="9">
                    <v-text-field v-model="email" :error-messages="emailErrors" required
                    @input="$v.email.$touch()" @blur="$v.email.$touch()" outlined type="email" label="Email"></v-text-field>
                </v-col>
                <v-col cols="12" md="9">
                    <v-text-field v-model="number" @keypress="onlyNumber" :error-messages="numberErrors" required
                    @input="$v.number.$touch()" @blur="$v.number.$touch()" :counter="11" autocomplete="off" type="tel" outlined label="Number"></v-text-field>
                </v-col>
                <v-col cols="12" md="9">
                    <v-textarea outlined v-model="content" :error-messages="contentErrors" required
                    @input="$v.content.$touch()" @blur="$v.content.$touch()" :counter="250" no-resize label="Write you'r Testimonial"></v-textarea>
                </v-col>
                <v-col cols="8" md="9">
                    <v-btn color="primary" type="submit">Submit</v-btn>
                </v-col>
            </v-row>
        </v-form>
        <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message || error}}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                Close
                </v-btn>
            </template>
        </v-snackbar>
    </Pages>
</template>
<script>
import Pages from '@/shared/public/components/Pages'
import { validationMixin } from 'vuelidate'
import { required, maxLength, minLength, email } from 'vuelidate/lib/validators'
export default {
    mixins: [validationMixin],

    validations: {
      name: { required, maxLength: maxLength(50) },
      number: { required, minLength: minLength(11),maxLength: maxLength(11) },
      email: { required, email },
      content: { required, maxLength: maxLength(250) },
    },
    data: ()=>({
        name: '',
        email: '',
        number: '',
        content: '',
        error: '',
        avatar: [],
        snackbar: false,
        imageRules: [
        value => (value && value.size < 2000000) || 'Avatar size should be less than 2 MB!',
      ],
    }),
    computed: {
      nameErrors () {
        const errors = []
        if (!this.$v.name.$dirty) return errors
        !this.$v.name.maxLength && errors.push('Name must be at most 50 characters long')
        !this.$v.name.required && errors.push('Name is required.')
        return errors
      },
      contentErrors () {
        const errors = []
        if (!this.$v.content.$dirty) return errors
        !this.$v.content.maxLength && errors.push('Testimonial must be at most 250 characters long')
        !this.$v.content.required && errors.push('Testimonial is required.')
        return errors
      },
      numberErrors () {
        const errors = []
        if (!this.$v.number.$dirty) return errors
        !this.$v.number.minLength && errors.push('You must be input a valid Number')
        !this.$v.number.maxLength && errors.push('You must be input a valid Number')
        !this.$v.number.required && errors.push('Number is required.')
        return errors
      },
      emailErrors () {
        const errors = []
        if (!this.$v.email.$dirty) return errors
        !this.$v.email.email && errors.push('Must be valid e-mail')
        !this.$v.email.required && errors.push('E-mail is required')
        return errors
      },
    },
    methods:{
        onlyNumber ($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if (keyCode < 48 || keyCode > 57) { // 46 is dot
                $event.preventDefault();
            }
        },
        submit () { 
            this.$v.$touch()
            if(!this.$v.$invalid && this.$refs.from.validate()){
                let formData = new FormData();
                formData.append('name',this.name)
                formData.append('email',this.email)
                formData.append('number',this.number)
                formData.append('avatar',this.avatar)
                formData.append('review',this.content)
                this.$inertia.post(this.$route('public.review.store'), formData)
                    .then((re)=>{
                        
                        if(this.$page.successMessage.success){
                            this.snackbar = true
                            this.$refs.from.reset()
                            this.$v.$reset()
                            this.name = ''
                            this.email = ''
                            this.number = ''
                            this.content = ''
                            this.avatar = []
                            
                        }
                        if(this.$page.errors){ 
                                if(this.$page.errors.email && this.$page.errors.number){
                                    this.error = this.$page.errors.email[0] +'\n'+ this.$page.errors.number[0];
                                }else if(this.$page.errors.email){
                                    this.error = this.$page.errors.email[0]
                                }else{
                                    this.error = this.$page.errors.number[0];
                                }
                                
                                this.snackbar = true
                        }   
                    })
                    .catch(err => console.log(err))
            }
        },
    },
    components:{Pages},
}
</script>