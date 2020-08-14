<template>
    <carousel :dots="false" :autoplay="true" :center="true" :autoWidth="true" :nav="false" :loop="true"  class="mt-4" :autoplayHoverPause="true">
        <v-card color="'white" width="400" :class="$vuetify.breakpoint.smAndDown ? 'ma-3 pa-3': 'ma-3'" v-for="(course, i) in courses" :key="i">
            <v-img height="280px" :src="course.img"></v-img>
            <v-card-title class="font-weight-bold">{{course.title}}</v-card-title>
            <div v-if="batch" class="text-subtitle-1 text-capitalize ml-4">starting date: {{course.start}}</div>
            <div v-if="batch" class="text-subtitle-1 text-capitalize ml-4">last date of admission: {{course.end}}</div>
            <v-card-text class="text-truncate">{{course.details}}</v-card-text>
            <div class="details-small" v-if="!batch">
                <div class="month">
                    <h4>{{course.courseDuration}}</h4>
                    <samp>course duration</samp>
                </div>
                <div class="class-count">
                    <h4>{{course.classCount}}</h4>
                    <samp>class size</samp>
                </div>
                <div class="class-duration">
                    <h4>{{course.classDuration}}</h4>
                    <samp>class duration</samp>
                </div>
            </div>
            <v-card-actions class="justify-center">
                <v-btn color="primary" class="my-4">Details</v-btn>
            </v-card-actions>
        </v-card>
    </carousel>
</template>
<script>
import carousel from 'vue-owl-carousel';
export default {
    data: ()=>({
        courses: [
            {title: 'Web Debeloper',img: 'storage/images/courses/web.jpg', details: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit voluptate hic dolorem dicta consequuntur dignissimos exercitationem', courseDuration: '4 month', classCount: 40, classDuration: '1 houre',start: '2020-06-17', end: '2020-06-15'},
            {title: 'Web Debeloper',img: 'storage/images/courses/web.jpg', details: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit voluptate hic dolorem dicta consequuntur dignissimos exercitationem', courseDuration: '4 month', classCount: 40, classDuration: '1 houre',start: '2020-06-17', end: '2020-06-15'},
            {title: 'Web Debeloper',img: 'storage/images/courses/web.jpg', details: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit voluptate hic dolorem dicta consequuntur dignissimos exercitationem', courseDuration: '4 month', classCount: 40, classDuration: '1 houre',start: '2020-06-17', end: '2020-06-15'},
            {title: 'Web Debeloper',img: 'storage/images/courses/web.jpg', details: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit voluptate hic dolorem dicta consequuntur dignissimos exercitationem', courseDuration: '4 month', classCount: 40, classDuration: '1 houre',start: '2020-06-17', end: '2020-06-15'},
            {title: 'Web Debeloper',img: 'storage/images/courses/web.jpg', details: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit voluptate hic dolorem dicta consequuntur dignissimos exercitationem', courseDuration: '4 month', classCount: 40, classDuration: '1 houre',start: '2020-06-17', end: '2020-06-15'},
            {title: 'Web Debeloper',img: 'storage/images/courses/web.jpg', details: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit voluptate hic dolorem dicta consequuntur dignissimos exercitationem', courseDuration: '4 month', classCount: 40, classDuration: '1 houre',start: '2020-06-17', end: '2020-06-15'},
        ],
    }),
    props:{
        batch: Boolean
    },
    components: {carousel}
}
</script>