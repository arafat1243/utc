<template>
    <Layout title="Edit Course - UTC">
        <v-container>
            <v-row no-gutters justify="center">
                <v-col cols="10">
                    <v-card flat>
                       <v-alert class="grey lighten-4 primary--text text-h4">
                           <inertia-link :href="$route('courses.index')" class="mdi mdi-arrow-left"></inertia-link>
                           Edit Course
                           <v-icon large class="mr-3" color="primary">mdi-pencil</v-icon>
                        </v-alert>
                        <v-form ref="courseForm" @submit.prevent="submit" method="post" enctype="multipart/form-data">
                            <v-row no-gutters >
                                <v-col cols="12" md="5"><v-text-field outlined :rules="inputRules" v-model="form.title" label="Course Name"></v-text-field></v-col>
                                <v-spacer></v-spacer>
                                <v-col cols="12" md="5"><v-text-field v-model="form.fees" :rules="inputRules" @keypress="onlyNumber" outlined label="Course Fees"></v-text-field></v-col>
                                <v-col cols="12" md="5">
                                    <v-select :items="categories" v-model="form.cat_id" :rules="selectRules" label="Course Category" outlined></v-select>
                                </v-col>
                                <v-spacer></v-spacer>
                                <v-col cols="12" md="5">
                                    <v-text-field v-model="form.course_duration" :rules="inputRules" outlined label="Course Duration"></v-text-field>
                                </v-col>
                                <v-col cols="12" md="5">
                                    <v-text-field v-model="form.class_duration" :rules="inputRules" outlined label="Class Duration"></v-text-field>
                                </v-col>
                                <v-spacer></v-spacer>
                                <v-col cols="12" md="5">
                                    <v-text-field @keypress="onlyNumber" :rules="[v => !!v || 'Total calss count is required',v => (v && v.length >= 2) || (v && v >= 10) || 'You Must input 2 characters']" v-model="form.class_count" outlined label="Total Class"></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-img height="240" width="240" :src="imagePath"></v-img>
                                    <v-file-input @change="setPreview" show-size counter accept="image/*" v-model="form.banner_img" label="Choose banner image"></v-file-input>
                                </v-col>
                                <v-spacer></v-spacer>
                                <v-col cols="12" md="7">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-textarea v-model="form.content" :rules="contentRules" v-bind="attrs" v-on="on" outlined rows="10" label="Course Content" placeholder="ex:Header1!h!content 1!c!content 2!c!content 3!hc!Header 2!h!content 1!c!content 2"></v-textarea>
                                        </template>
                                        <samp>You Must to be use !h! between headline and content & use !c! between two content & use !hc! between two headline</samp>
                                    </v-tooltip> 
                                </v-col>
                                <v-col cols="12">
                                    <label>Course Details</label>
                                    <Editor :rules="inputRules" v-model="form.details" api-key="1cxlzarpttg73bds7kj5tgppkshscdcyqpdhdlbe6yl4im2n"
                                        :init="{
                                            height: 450,
                                            menubar: false,
                                            plugins: [
                                            'advlist autolink lists link charmap preview anchor',
                                            'searchreplace visualblocks code fullscreen',
                                            'insertdatetime media table paste code help wordcount'
                                            ],
                                            toolbar:
                                            'undo redo | formatselect | bold italic backcolor | \
                                            alignleft aligncenter alignright alignjustify | \
                                            bullist numlist outdent indent | removeformat | help'
                                        }"
                                        />
                                </v-col>
                                <v-col cols="4" class="mt-5">
                                    <v-btn color="primary" type="submit">Submit</v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-snackbar top v-model="snackbar" color="error">
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
import Layout from '@/shared/admin/Layout';
import Editor from '@tinymce/tinymce-vue';
export default {
    data: ()=>({
        imagePath: '',
        form:{
            title: '',
            fees: 0,
            details: null,
            content: '',
            course_duration: '',
            class_duration: '',
            banner_img: [],
            class_count: 0,
            cat_id: 0,
        },
        snackbar: false,
        inputRules:[
            v => !!v || 'Feild is required',
            v => (v && v.length >= 3) || (v && v >= 1000) || 'You Must input Minimum 3 characters'
        ],
        selectRules:[
            v =>  (v !== 0) || 'You must be select course category'
        ],
        contentRules:[
            v => !!v || 'Content is required',
            v => /.+!h!.+.!c!.+!hc!.+/.test(v) || 'You must write content according to the conditions',
            v => (v.lastIndexOf('!h!') !== v.length - 3 && v.lastIndexOf('!c!') !== v.length - 3 && v.lastIndexOf('!hc!') !== v.length - 4 ) || 'You must be Input oue word after spaical divider',
            v => (v && v.length >= 20) || 'You Must input Minimum 20 characters',
        ]
    }),
    props: {
        categories: Array,
        course: Object
    },
    methods:{
        initialize(){
            this.form.title = this.course.title,
            this.form.fees = this.course.fees,
            this.form.details = this.course.details,
            this.form.content = this.course.content,
            this.form.course_duration = this.course.course_duration,
            this.form.class_duration = this.course.class_duration,
            this.form.class_count = this.course.class_count,
            this.form.cat_id = this.course.cat_id,
            this.imagePath = this.$page.baseUrl+this.course.banner_img;
        },
        onlyNumber ($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if (keyCode < 48 || keyCode > 57) { // 46 is dot
                $event.preventDefault();
            }
        },
        submit(){
            if(this.$refs.courseForm.validate() && this.form.details){
                let formData = new FormData();
                formData.append('title',this.form.title);
                formData.append('fees',this.form.fees);
                formData.append('details',this.form.details);
                formData.append('content',this.form.content);
                formData.append('banner_img',this.form.banner_img);
                formData.append('course_duration',this.form.course_duration);
                formData.append('class_duration',this.form.class_duration);
                formData.append('class_count',this.form.class_count);
                formData.append('cat_id',this.form.cat_id);
              this.$inertia.post(this.$route('courses.update',this.course.id), formData,{
                replace: false,
                preserveState: true,
                preserveScroll: false,
                only: [],
                })
                .then(re => {
                    console.log(re);
                    
                    this.snackbar = true;
                    if(this.$page.successMessage.success){
                        this.$refs.courseForm.reset();
                        this.form.title = '',
                        this.form.fees = 0,
                        this.form.details = '',
                        this.form.content = '',
                        this.form.banner_img = [],
                        this.form.course_duration = '',
                        this.form.class_duration = '',
                        this.form.class_count = '',
                        this.form.cat_id = '';
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
                    _this.imagePath = this.result
                })
                reader.readAsDataURL(file); // convert to base64 string
            }   
        }
    },
    created(){
        this.initialize();
    },
    components:{Layout,Editor}
}
</script>
