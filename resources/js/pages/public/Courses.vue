<template>
    <Layout title="Courses - UTC">
        <div>
            <v-card class="mx-auto">
                <v-img class="white--text align-end" height="200px" :src="courses[0] ? $page.baseUrl+courses[0].banner_img : ''">   
                    <v-overlay absolute ><v-card-title>OUR COURSES</v-card-title></v-overlay> 
                </v-img>
            </v-card>
            <v-container>
                <v-row :justify="courses.length <= 0 ? 'center' : 'start'">
                    <v-col cols="12" md="6" v-if="courses.length <= 0">
                        <v-card height="200">
                            <v-alert type="info">
                                No Course Found!
                            </v-alert>
                            <v-card-title class="text-justify">
                                The courses you are looking for are not available right <br> now.
                                Please look at the others course
                            </v-card-title>
                        </v-card>
                    </v-col>
                    <v-col v-else cols="12" sm="6" md="4" v-for="(course, i) in courses" :key="i">
                        <v-card color="'white'" class="'ma-3'">
                            <v-img height="280px" :src="$page.baseUrl+course.banner_img"></v-img>
                            <v-card-title class="font-weight-bold">{{course.title}}</v-card-title>
                            <v-card-text class="text-truncate" v-html="course.details"></v-card-text>
                            <v-card-actions class="justify-center">
                               <v-btn color="primary" @click="showDetails(course)">Details</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </div>
        <div class="w-100 transform-0 white-dark py-10">
            <div class="text-h5 text-md-h4 ftco-headline justify-center white--text">Our <div>other</div>courses</div>
            <div class="d-flex justify-center">
                <div class="ftco-for-headline double-line-bottom-theme-colored-2"></div>
            </div>
            <div class="tex-subtitle-2 text-center text-uppercase grey--text">check out our other courses</div>
            <carousel :items="1" :dots="false" :autoplay="true" :autoplayHoverPause="true" :autoWidth="true"  :center="true" :nav="false" :loop="true"  class="mt-4">
                <div v-for="(course,i) in allCourse" :key="i">
                    <v-avatar size="150" class="mx-10">
                        <v-img :src="$page.baseUrl+course.banner_img"></v-img>
                    </v-avatar>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <div v-bind="attrs" v-on="on" class="white--text text-center mt-2 text-truncate" style="max-width: 200px;">{{course.title}}</div>
                        </template>
                        <span>{{course.title}}</span>
                    </v-tooltip>
                </div>
            </carousel>
        </div>
        <v-dialog v-model="detailsDialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card flat :elevation="0">
                <v-toolbar dark color="primary">
                <v-btn icon dark @click="detailsDialog = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Course Deatils</v-toolbar-title>
                <v-spacer></v-spacer>
                </v-toolbar>
                <v-container>
                    <v-row>
                        <v-col cols="12" md="9">
                            <v-img height="400" class="elevation-5" :src="$page.baseUrl+deatils.banner_img">
                            </v-img>
                            <v-card-title>{{deatils.title}}</v-card-title>
                            <v-card-subtitle>{{deatils.category ? deatils.category.title : ''}}</v-card-subtitle>
                            <v-card-text class="my-4" v-html="deatils.details"></v-card-text>
                            <v-card>
                                <v-alert class="transparent text-h5">Course Content</v-alert>
                                <v-expansion-panels v-if="deatils.content"  multiple hover accordion focusable>
                                <v-expansion-panel v-for="(item,i) in contentMaker(deatils.content)" :key="i">
                                    <v-expansion-panel-header class="text-capitalize text-h6">{{item.header}}</v-expansion-panel-header>
                                    <v-expansion-panel-content>
                                        <v-list>
                                            <v-list-item v-for="(item,i) in item.items" :key="i">
                                                <v-list-item-title class="red--text text--accent-3 text-capitalize">{{i+1}}. {{item}}</v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-expansion-panels>
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="3">
                            <div :style="$vuetify.breakpoint.mdAndUp ? 'position: fixed;' : 'position: unset'">
                                <v-card flat :elevation="18">
                                <v-container>
                                    <v-row justify="center" class="text-capitalize">
                                        <v-col cols="12" :class="{'text-center' :$vuetify.breakpoint.mdAndUp}">
                                            <v-btn color="primary" rounded>Apply</v-btn>
                                        </v-col>
                                        <v-col cols="$vuetify.breakpoint.mdAndUp ? '12' : '6'">
                                            <v-chip color="info"><v-icon left>mdi-currency-bdt</v-icon>Course Fees: {{deatils.fees}} tk</v-chip>
                                        </v-col>
                                        <v-col cols="$vuetify.breakpoint.mdAndUp ? '12' : '6'">
                                            <v-chip color="primary"><v-icon left>mdi-clock</v-icon>Course Duration: {{deatils.course_duration}}</v-chip>
                                        </v-col>
                                        <v-col cols="$vuetify.breakpoint.mdAndUp ? '12' : '6'">
                                            <v-chip color="error"><v-icon left>mdi-clock</v-icon>class Duration: {{deatils.class_duration}}</v-chip>
                                        </v-col>
                                        <v-col cols="$vuetify.breakpoint.mdAndUp ? '12' : '6'">
                                            <v-chip color="teal"><v-icon left>mdi-sigma</v-icon>Total Class: {{deatils.class_count}}</v-chip>
                                        </v-col>
                                        <v-col cols="$vuetify.breakpoint.mdAndUp ? '12' : '6'">
                                            <v-chip color="success"><v-icon left>mdi-trophy</v-icon>Certificate of Completion</v-chip>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card>
                            </div>
                            
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-dialog>
    </Layout>
</template>
<script>
import Layout from '@/shared/public/Layout';
import Pagination from '@/shared/admin/components/Pagination'
import carousel from 'vue-owl-carousel';
export default {
    data: ()=>({
        detailsDialog: false,
        deatils: [],
    }),
    props:['courses','allCourse'],
    components: {carousel,Layout},
    methods:{
        showDetails(course){
            this.detailsDialog = true;
            this.deatils = course;
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
}
</script>