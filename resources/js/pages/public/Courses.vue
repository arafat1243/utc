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
                               <inertia-link :href="$route('public.details',course.id)" class="my-4 v-btn v-btn--contained theme--light v-size--default primary">
                            <span class="v-btn__content">Details</span>
                        </inertia-link>
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
    </Layout>
</template>
<script>
import Layout from '@/shared/public/Layout';
import Pagination from '@/shared/admin/components/Pagination'
import carousel from 'vue-owl-carousel';
export default {
    props:['courses','allCourse'],
    components: {carousel,Layout},
}
</script>