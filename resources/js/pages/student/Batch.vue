<template>
    <Layout title="Student Management">
        <v-container v-if="batches.length > 0">
            <v-alert border="top" colored-border type="info" elevation="2">
                New Batch are now available and you can be admitted if you want.
            </v-alert>
            <v-row>
                <v-col cols="12" sm="6" md="4" v-for="(batch, i) in batches" :key="i">
                    <v-card color="'white">
                        <v-img height="280px" :src="batch.course.banner_img"></v-img>
                        <v-card-title class="font-weight-bold">{{batch.course.title}}</v-card-title>
                        <div class="text-subtitle-1 text-capitalize ml-4">starting date: {{batch.start_at}}</div>
                        <div class="text-subtitle-1 text-capitalize ml-4">last date of admission: {{batch.last_at}}</div>
                        <v-card-text class="text-truncate" v-html="batch.course.details"></v-card-text>
                        <v-card-actions class="justify-center">
                            <inertia-link :href="$route('student.details',batch.course.id)+'?batch_id='+batch.id" class="my-4 v-btn v-btn--contained theme--light v-size--default primary">
                                <span class="v-btn__content">Details</span>
                            </inertia-link>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-container v-if="courses.length > 0">
            <v-alert border="top" colored-border type="info" elevation="2">
                You can join our other courses if you want.
            </v-alert>
             <v-row>
                <v-col cols="12" sm="6" md="4" v-for="(course, i) in courses" :key="i">
                    <v-card color="'white" width="400">
                        <v-img height="280px" :src="course.banner_img"></v-img>
                        <v-card-title class="font-weight-bold">{{course.title}}</v-card-title>
                        <v-card-subtitle class="font-weight-bold">{{course.category}}</v-card-subtitle>
                        <v-card-text class="text-truncate" v-html="course.details"></v-card-text>
                        <div class="details-small">
                            <div class="month">
                                <h4>{{course.course_duration}}</h4>
                                <samp>course duration</samp>
                            </div>
                            <div class="class-count">
                                <h4>{{course.class_count}}</h4>
                                <samp>Total class</samp>
                            </div>
                            <div class="class-duration">
                                <h4>{{course.class_duration}}</h4>
                                <samp>class duration</samp>
                            </div>
                        </div>
                        <v-card-actions class="justify-center">
                            <inertia-link :href="$route('student.details',course.id)" class="my-4 v-btn v-btn--contained theme--light v-size--default primary">
                                <span class="v-btn__content">Details</span>
                            </inertia-link>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </Layout>
</template>
<script>
import Layout from './Layout'
export default {
    props:['batches','courses'],
    components:{Layout}
}
</script>
