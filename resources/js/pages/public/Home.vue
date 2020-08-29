<template>
    <Layout title="UTC">
        <!-- <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay> -->
        <v-carousel
            cycle
            hide-delimiters
            show-arrows-on-hover
            progress
            progress-color="primary"
        >
            <v-carousel-item
            v-for="(slide, i) in slides"
            :key="i"
            :src="$page.baseUrl+slide.path"
            >
            <v-overlay absolute>
                <div class="d-flex justify-center align-center flex-column">
                    <div class="text-h4 text-md-h1 d-flex"   v-html="slide.header.length > 4 ? slide.header : ''"></div>
                    <p class="ftco-paragraph text-caption text-md-subtitle-1" v-html="slide.paragraph.length > 4 ? slide.paragraph : ''"></p>
                    <div>
                        <!-- <v-btn text class="slide-btn mr-2">Our Services</v-btn> -->
                        <inertia-link :href="$route('public.services')" class="slide-btn mr-2 v-btn v-btn--flat v-btn--text theme--dark v-size--default">
                            <span class="v-btn__content">Our Services</span>
                        </inertia-link>
                        <inertia-link :href="$route('public.courses')" class="slide-btn v-btn v-btn--flat v-btn--text theme--dark v-size--default">
                            <span class="v-btn__content">Our Courses</span>
                        </inertia-link>
                        <!-- <v-btn text class="slide-btn">Our Courses</v-btn> -->
                    </div>
                </div>
                <!-- <div class="d-flex align-center font-weight-bold ftco-overlay-text" v-html="slide.headline"></div> -->    
            </v-overlay>
            </v-carousel-item>
        </v-carousel>
        <div  class="w-100">
            <v-container>
                <v-row>
                    <v-col cols="12" md="4" v-for="(item,i) in inrtoItems" :key="i">
                        <v-card :class="item.color +' white--text'">
                            <v-icon class="card-icon">{{item.icon}}</v-icon>
                            <v-card-title class="text-center text-uppercase justify-center font-weight-bold">
                                {{item.title}}
                            </v-card-title>
                            <v-card-subtitle class="white--text text-center">
                                {{item.text}}
                            </v-card-subtitle>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row class="mt-5">
                    <v-col cols="12" md="6">
                        <v-card flat>
                            <v-card-title class="text-h5 text-md-h3 ftco-headline">About <div>UTC</div></v-card-title>
                            <div class="double-line-bottom-theme-colored-2"></div>
                            <v-card-subtitle style="font-size:1em">
                                Lorem ipsum dolor sit amet, consectetur adpisici elit. Obcaec atdistinc tio quaerat iusto, accusa ntumeos nsect eturpers piciatis minima tempore!
                            </v-card-subtitle>
                            <v-card-text class="ftco-list">
                                <li>We achieved most leading universtiy award 2017</li>
                                <li>We achieved most leading universtiy award 2017</li>
                                <li>We achieved most leading universtiy award 2017</li>
                                <li>We achieved most leading universtiy award 2017</li>
                                <li>We achieved most leading universtiy award 2017</li>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn class="primary text-capitalize">Read More</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-card flat>
                            <v-card-title class="text-h5 text-md-h3 ftco-headline">Opening <div>Hours</div></v-card-title>
                            <div class="double-line-bottom-theme-colored-2"></div>
                            <v-card-text>
                                <ul class="open-list">
                                <li> <samp class="text-start">Mon-Tues:</samp><samp class="text-end">10:00am-5:00pm</samp></li>
                                <li> <samp class="text-start">Wednes-Thurs:</samp> <samp class="text-end">10:00am-5:00pm</samp></li>
                                <li> <samp class="text-start">Fri</samp> <v-chip label color="primary">Close</v-chip></li>
                                <li> <samp class="text-start">Sun</samp> <samp class="text-end">10:00am-5:00pm</samp></li>
                                </ul>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </div>
        <div class="w-100 white-yellow py-10">
            <div class="text-h5 text-md-h3 ftco-headline justify-center">popular <div>course</div></div>
            <div class="d-flex justify-center">
                <div class="ftco-for-headline double-line-bottom-theme-colored-2"></div>
            </div>
            <div class="tex-subtitle-2 text-center text-uppercase grey--text">choose your desired course</div>
            <carousel :dots="false" :autoplay="true" :center="true" :autoWidth="true" :nav="false" :loop="popularCourse.length > 2 ? true : false"  class="mt-4" :autoplayHoverPause="true">
                <v-card color="'white" width="400" :class="$vuetify.breakpoint.smAndDown ? 'ma-3 pa-3': 'ma-3'" v-for="(course, i) in popularCourse" :key="i">
                    <v-img height="280px" :src="course.course.banner_img"></v-img>
                    <v-card-title class="font-weight-bold">{{course.course.title}}</v-card-title>
                    <v-card-text class="text-truncate" v-html="course.course.details"></v-card-text>
                    <div class="details-small">
                        <div class="month">
                            <h4>{{course.course.course_duration}}</h4>
                            <samp>course duration</samp>
                        </div>
                        <div class="class-count">
                            <h4>{{course.course.class_count}}</h4>
                            <samp>Total class</samp>
                        </div>
                        <div class="class-duration">
                            <h4>{{course.course.class_duration}}</h4>
                            <samp>class duration</samp>
                        </div>
                    </div>
                    <v-card-actions class="justify-center">
                        <inertia-link :href="$route('public.details',course.course_id)" class="my-4 v-btn v-btn--contained theme--light v-size--default primary">
                            <span class="v-btn__content">Details</span>
                        </inertia-link>
                    </v-card-actions>
                </v-card>
            </carousel>
        </div>
        <div class="w-100 white-dark pa-10">
            <v-container>
                <v-row justify="center">
                    <v-col cols="12" sm="6" md="3" v-for="(counte , i) in counter" :key="i">
                        <v-card class="white-dark-2" height="200">
                            <div class="counter d-flex flex-column justify-center align-center white--text">
                                <h1 class="mt-7">{{counte.number}}+</h1>
                                <div class="ftco-for-headline double-line-bottom-theme-colored-2 my-7"></div>
                                <div class="text-h6 text-capitalize">{{counte.text}}</div>
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </div>
        <div class="w-100 white-yellow py-10">
            <div class="text-h5 text-md-h3 ftco-headline justify-center">upcoming <div>batches</div></div>
            <div class="d-flex justify-center">
                <div class="ftco-for-headline double-line-bottom-theme-colored-2"></div>
            </div>
            <div class="tex-subtitle-2 text-center text-uppercase grey--text">check out upcoming new batches</div>
            <carousel :dots="false" :autoplay="true" :center="true" :autoWidth="true" :nav="false" :loop="batches.length > 3 ? true : false"  class="mt-4" :autoplayHoverPause="true">
                <v-card color="'white" width="400" :class="$vuetify.breakpoint.smAndDown ? 'ma-3 pa-3': 'ma-3'" v-for="(batch, i) in batches" :key="i">
                    <v-img height="280px" :src="batch.course.banner_img"></v-img>
                    <v-card-title class="font-weight-bold">{{batch.course.title}}</v-card-title>
                    <div class="text-subtitle-1 text-capitalize ml-4">starting date: {{batch.start_at}}</div>
                    <div class="text-subtitle-1 text-capitalize ml-4">last date of admission: {{batch.last_at}}</div>
                    <v-card-text class="text-truncate" v-html="batch.course.details"></v-card-text>
                    <v-card-actions class="justify-center">
                        <inertia-link :href="$route('public.details',batch.course.id)+'?batch_id='+batch.id" class="my-4 v-btn v-btn--contained theme--light v-size--default primary">
                            <span class="v-btn__content">Details</span>
                        </inertia-link>
                    </v-card-actions>
                </v-card>
            </carousel>
        </div>
        <div class="w-100 white-dark py-10">
            <div class="text-h5 text-md-h3 ftco-headline justify-center white--text">What <div>people</div> say?</div>
            <div class="d-flex justify-center">
                <div class="ftco-for-headline double-line-bottom-theme-colored-2"></div>
            </div>
            <div class="tex-subtitle-2 text-center text-uppercase grey--text">STUDENT AND client OPINION</div>
            <carousel :items="1" :center="reviews.length < 2 ? true : false" :dots="false" :autoplay="true" :autoWidth="$vuetify.breakpoint.smAndDown ? false : true"  :nav="false" class="mt-4" :autoplayHoverPause="true">
                <v-card class="ma-3" color="white-dark-2" min-width="450" max-width="605" dark v-for="(review,j) in reviews" :key="j">
                    <v-card-title>
                        <v-list-item class="grow">
                            <v-list-item-avatar color="grey darken-3">
                                <v-img class="elevation-6" :src="$page.baseUrl+review.avatar"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-title class="text-left">{{review.name}}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-card-title>
                    <v-card-text class="headline font-weight-bold">
                        "{{review.review}}"
                    </v-card-text>
                </v-card>
            </carousel>
        </div>
        <div class="w-100 white-yellow py-10">
            <v-container>
                <v-row class="mt-5">
                    <v-col cols="12" md="8">
                        <v-card flat class="pa-2">
                            <v-card-title class="text-h5 text-md-h3 ftco-headline">SEE OUR<div>GALLERY</div></v-card-title>
                            <div class="double-line-bottom-theme-colored-2"></div>
                            <v-row no-gutters>
                                <v-col cols="4" sm="3" v-for="(image,i) in gallery" :key="i">
                                    <v-img @click="showLightbox(image.name)" style="cursor: pointer;" :src="image.name" aspect-ratio='1'></v-img>
                                </v-col>
                            </v-row>
                            <lightbox id="mylightbox"
                                ref="lightbox"
                                :images="gallery"
                                :timeoutDuration="5000"
                            ></lightbox>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-card flat>
                            <v-card-title class="text-h5 text-md-h3 ftco-headline primary--text">Contact us</v-card-title>
                            <div class="double-line-bottom-theme-colored-2"></div>
                            <v-list>
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon large="">mdi-map-search</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <div class="text-h6">Our Office Location</div>
                                        <div class="text-subtitle-2 grey--text">207, Doctor Para, Feni, BD</div>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon large="">mdi-phone</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <div class="text-h6">Contact Number</div>
                                        <div class="text-subtitle-2 grey--text">+880 1812-669757</div>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon large="">mdi-email</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <div class="text-h6">Email Address</div>
                                        <div class="text-subtitle-2 grey--text">supporte@utc.net</div>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>  
        </div>
        <div class="w-100  white-dark py-10">
            <ClientCarousael/>
        </div>
        <div class="w-100 transform-0">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.381221356331!2d91.39452861431411!3d23.009770884959583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x37536830f4bcf3f3%3A0x151efc17e414077b!2sUTC!5e0!3m2!1sen!2sbd!4v1593076295542!5m2!1sen!2sbd" width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
    </Layout>
</template>
<script>
import Lightbox from 'vue-my-photos';
import carousel from 'vue-owl-carousel';
import Layout from '@/shared/public/Layout';
import ClientCarousael from '@/shared/public/components/ClientCarousael';
export default {
    data: () => ({
        show: false,
        overlay: true,
        // events: [
        //     {date: [28,'feb'], title: 'Admission Fair Spring 2017', time: '5.00 pm - 7.30 pm',location: '25 Newyork City.', path: '#'},
        //     {date: [28,'feb'], title: 'Admission Fair Spring 2017', time: '5.00 pm - 7.30 pm',location: '25 Newyork City.', path: '#'},
        //     {date: [28,'feb'], title: 'Admission Fair Spring 2017', time: '5.00 pm - 7.30 pm',location: '25 Newyork City.', path: '#'},
        // ],
        inrtoItems: [
            {title:'OUR COURSES',text:'Lorem ipsum dolor sit amet, consectetur adpisici elit. Obcaec atdistinc tio quaerat iusto, accusa ntumeos nsect eturpers piciatis minima tempore!',color:'success',icon:'mdi-school'},
            {title:'OUR services',text:'Lorem ipsum dolor sit amet, consectetur adpisici elit. Obcaec atdistinc tio quaerat iusto, accusa ntumeos nsect eturpers piciatis minima tempore!',color:'primary',icon:'mdi-cog'},
            {title:'our Mission',text:'Lorem ipsum dolor sit amet, consectetur adpisici elit. Obcaec atdistinc tio quaerat iusto, accusa ntumeos nsect eturpers piciatis minima tempore!',color:'error',icon:'mdi-bullseye-arrow'},
        ],
        counter: [
            {number: 150,text: 'happy student'},
            {number: 30,text: 'courses'},
            {number: 100,text: 'client'},
            {number: 150,text: 'Complete Projects'},
        ],
      }),
      props:['slides','popularCourse','batches','reviews','gallery'],
      components: {Lightbox,carousel,Layout,ClientCarousael},
      methods:{
           showLightbox: function(imageName) {
                this.$refs.lightbox.show(imageName);
            }
      }
}
</script>
<style lang="scss" scoped>
    .ftco-overlay-text{
        font-size: 3em;
        word-spacing: 5px;
    }
    .ftco-paragraph{
        margin-top: 20px;
        width: 80%;
        padding: 0 30px;
        text-align: center;
        
    }
    .slide-btn{
        background-color: #5d9cec !important;
        &:not(:nth-child(1)){
            border: 1px solid #42b14b !important;
            background-color: transparent !important;
            &:hover{
                background-color: #42b14b !important;
            }
        }
    }
    .card-icon{
        font-size: 7em !important;
        color: #fff !important;
        position: relative;
        left: 50%;
        transform: translateX(-50%) !important;
    }
    .ftco-container{
        transform: translateY(-100px) !important;
    }
    .event-small{
        display: flex;
        flex-flow: wrap row;
        &:not(:nth-child(1)){
            margin: 30px 0;
        }
        .event-date{
            background: #5d9cec;
            color: #fff;
            padding: 0 20px;
            margin-right: 20px;
            li{
                text-align: center;
                list-style: none;
                padding: 5px 0;
                &:nth-child(1){
                    border-bottom: 1px solid #fff;
                    font-weight: 600;
                }
            }
        }
        .event-content{
            h5{
                font-size: 1rem;
                margin: 5px 0;
                a{
                    text-decoration: none;
                }
            }
        }
    }
    .counter{
        .ftco-for-headline{
            margin-left: -60px !important;
        }
    }
    .ftco-for-headline{
        margin-left: -25px !important;
        &::before{
            left: 20px !important;
        }
    }
    .open-list{
        max-width: 400px;
        li{
            list-style: none !important;
            display: flex !important;
            justify-content: space-between !important;
            &:not(:nth-child(1)){
                margin: 15px 0 !important;
            }
            samp{
                font-size: 1.3em !important;
            }
        }
    }
</style>