<template>
    <Layout title="Course - UTC">
        <v-data-table
            :search="search"
            :headers="headers"
            :items="courses.data"
            item-key="id"
            hide-default-footer
            class="elevation-1"
        >
            <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>Courses List</v-toolbar-title>
                <v-divider
                class="mx-4"
                inset
                vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
                <v-spacer></v-spacer>
                    <inertia-link :href="$route('courses.create')" v-if="role.can('course_create')" class="v-btn v-btn--active v-btn--flat v-btn--router theme--light v-size--default primary">
                       <span class="v-btn__content">Add New Course</span>
                   </inertia-link>
            </v-toolbar>
            </template>
            <template v-slot:item.banner_img="{item}">
                <v-avatar size="80">
                    <v-img :src="item.banner_img"></v-img>
                </v-avatar>
            </template>
            <template v-slot:item.fees="{ item }">
                <v-chip color="success">{{ item.fees }} tk</v-chip> 
            </template>
            <template v-slot:item.actions="{ item }">
                <div class="d-flex">
                        <div v-for="(ui,i) in uiManage" :key="i">
                        <v-tooltip bottom v-if="ui.can">
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon v-if="!ui.herf" small class="mr-2" :color="ui.color"
                                v-bind="attrs" v-on="on" @click="preview(item)">
                                {{ui.icon}}
                            </v-icon>
                            <inertia-link v-if="ui.herf" style="font-size:16px" :href="$route('courses.edit',item.id)" v-bind="attrs" v-on="on" class="v-icon notranslate mr-2 v-icon--link mdi mdi-pencil theme--light success--text"></inertia-link>
                            </template>
                            <span>{{ui.text}}</span>
                        </v-tooltip>
                        <v-chip color="warning" v-if="!ui.can && i==0">no Actions</v-chip>
                    </div>
                </div> 
            </template>
        </v-data-table>
        <Pagination class="mt-4" :links="this.courses"/>
        <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message}}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <v-dialog v-model="detailsDialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                <v-btn icon dark @click="detailsDialog = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Course Preview</v-toolbar-title>
                <v-spacer></v-spacer>
                </v-toolbar>
                <v-img height="400" class="elevation-5" :src="singleCourse.banner_img">
                </v-img>
                <v-card-title>{{singleCourse.title}}</v-card-title>
                <v-card-subtitle>{{singleCourse.category ? singleCourse.category.title : ''}}</v-card-subtitle>
                <v-container>
                    <v-row no-gutters justify="center" class="text-uppercase">
                        <v-col cols="6" sm="3">
                            <v-chip color="info">Course Fees: {{singleCourse.fees}} tk</v-chip>
                        </v-col>
                        <v-col cols="6" sm="3">
                            <v-chip color="primary">Course Duration: {{singleCourse.course_duration}}</v-chip>
                        </v-col>
                        <v-col cols="6" sm="3">
                            <v-chip color="error">class Duration: {{singleCourse.class_duration}}</v-chip>
                        </v-col>
                        <v-col cols="6" sm="3">
                            <v-chip class="teal white--text">Total Class: {{singleCourse.class_count}}</v-chip>
                        </v-col>
                    </v-row>
                </v-container>
                <v-card-text class="my-4" v-html="singleCourse.details"></v-card-text>
                <v-expansion-panels v-if="singleCourse.content"  multiple hover inset focusable>
                    <v-expansion-panel v-for="(item,i) in contentMaker(singleCourse.content)" :key="i">
                        <v-expansion-panel-header class="grey--text text--darken-2 font-18 text-capitalize font-weight-bold">{{item.header}}</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-list>
                                <v-list-item v-for="(item,i) in item.items" :key="i">
                                    <v-list-item-title class="grey--text text--darken-3 text-body-1 text-capitalize">{{i+1}}. {{item}}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-card>
        </v-dialog>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Pagination from '@/shared/admin/components/Pagination'
import Auth from '@/auth'
export default {
    data: vm => ({
      role:  new Auth(vm.$page.auth.roles),
      search: '',
      snackbar: false,
      dialog: false,
      detailsDialog: false,
      singleCourse: [],
      content: [],
      headers: [
        {
          text: 'Course Name',
          align: 'start',
          sortable: false,
          value: 'title',
          width: 200
        },
        { text: 'Category', value: 'category.title',sortable: false },
        { text: 'Fees', value: 'fees',sortable: false },
        { text: 'Banner Img', value: 'banner_img',sortable: false },
        { text: 'Course Duration', value: 'course_duration',sortable: false},
        { text: 'Class Duration', value: 'class_duration',sortable: false},
        { text: 'Total Class', value: 'class_count',width: 50,sortable: false},
        { text: 'Total Batch', value: '60',width: 50,sortable: false },
        { text: 'Actions', value: 'actions', sortable: false, align: 'center'},
      ],
      deleteItem: {},
      uiManage:[]
    }),
    props:['courses'],
    mounted(){
        this.snackbar = this.$page.successMessage.success;
        this.uiManage = [
            {text: 'Preview',icon: 'mdi-eye',color: 'primary',can: this.role.can('course_view')},
            {text: 'Edit',herf: 'mdi-eye', color: 'success',can: this.role.can('course_update')},
        ]
    },
    methods: {
      preview (item) {
        this.singleCourse = item;
        this.detailsDialog = true
      },
      contentMaker(content){
          let contents = content.split('!hc!');
          let finalContent = [];
            contents.forEach(co => {
                let conts = co.split('!h!');
                let header = conts[0];
                let cont = conts[1].split('!c!'),cots = [];
                cont.forEach(cot =>{
                    cots.push(cot);
                });
                finalContent.push({
                    header:header,
                    items: cots
                });
            })
        return finalContent
      }
    },
    components:{
        Layout,Pagination
    }
}
</script>