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
            :src="slide.src"
            >
            <v-overlay absolute>
                <div class="d-flex justify-center align-center flex-column">
                    <div class="text-h4 text-md-h1 d-flex" v-html="slide.headline"></div>
                    <p class="ftco-paragraph text-caption text-md-subtitle-1" v-html="slide.paragraph"></p>
                    <div>
                        <v-btn text class="slide-btn mr-2">Our Services</v-btn>
                        <v-btn text class="slide-btn">Our Courses</v-btn>
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
            <CourseCarousael :batch="false"/>
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
            <CourseCarousael :batch="true"/>
        </div>
        <div class="w-100 white-dark py-10">
            <div class="text-h5 text-md-h3 ftco-headline justify-center white--text">What <div>people</div> say?</div>
            <div class="d-flex justify-center">
                <div class="ftco-for-headline double-line-bottom-theme-colored-2"></div>
            </div>
            <div class="tex-subtitle-2 text-center text-uppercase grey--text">STUDENT AND client OPINION</div>
            <carousel :items="1" :dots="false" :autoplay="true" :autoWidth="$vuetify.breakpoint.smAndDown ? false : true"  :nav="false" class="mt-4" :autoplayHoverPause="true">
                <v-card class="ma-3" color="white-dark-2" max-width="605" dark v-for="(review,j) in reviews" :key="j">
                    <v-card-title>
                        <v-list-item class="grow">
                            <v-list-item-avatar color="grey darken-3">
                                <v-img class="elevation-6" :src="review.img"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-title>{{review.name}}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-card-title>
                    <v-card-text class="headline font-weight-bold">
                        "{{review.context}}"
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
                                <v-col cols="4" sm="3" v-for="(image,i) in images" :key="i">
                                    <v-img @click="showLightbox(image.name)" style="cursor: pointer;" :src="image.name" aspect-ratio='1'></v-img>
                                </v-col>
                            </v-row>
                            <lightbox id="mylightbox"
                                ref="lightbox"
                                :images="images"
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
import CourseCarousael from '@/shared/public/components/CourseCarousael';
export default {
    data: () => ({
        show: false,
        overlay: true,
        images: [
            {name: 'storage/images/courses/web.jpg'},
            {name: 'storage/images/courses/web.jpg'},
            {name: 'storage/images/courses/web.jpg'},
            {name: 'storage/images/courses/web.jpg'},
            {name: 'storage/images/courses/web.jpg'},
            {name: 'storage/images/courses/web.jpg'},
            {name: 'storage/images/courses/web.jpg'},
            {name: 'storage/images/courses/web.jpg'},
        ],
        slides: [
          {src: 'storage/images/gallery/banner.jpg', headline: 'Welcome to <div class="brand-text primary--text ml-3"> UTC</div>', paragraph: 'Learn, Be skilled, Know Your strength'},
          {src: 'storage/images/gallery/slider-2.jpg', headline: 'Welcome to <div class="brand-text primary--text ml-3"> UTC</div>', paragraph: 'sentences developing a central idea, called the topic. Paragraphs add one idea at a time to your broader argument.'},
          {src: 'storage/images/gallery/slider-3.png', headline: 'Welcome to <div class="brand-text primary--text ml-3"> UTC</div>', paragraph: 'sentences developing a central idea, called the topic. Paragraphs add one idea at a time to your broader argument.'}
        ],
        events: [
            {date: [28,'feb'], title: 'Admission Fair Spring 2017', time: '5.00 pm - 7.30 pm',location: '25 Newyork City.', path: '#'},
            {date: [28,'feb'], title: 'Admission Fair Spring 2017', time: '5.00 pm - 7.30 pm',location: '25 Newyork City.', path: '#'},
            {date: [28,'feb'], title: 'Admission Fair Spring 2017', time: '5.00 pm - 7.30 pm',location: '25 Newyork City.', path: '#'},
        ],
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
        reviews: [
            {img: '', name: 'Yeasir Arafat', context: 'Turns out semicolon-less style is easier and safer in TS because most gotcha edge cases are type invalid as well.'},
            {img: '', name: 'Yeasir Arafat', context: 'Turns out semicolon-less style is easier and safer in TS because most gotcha edge cases are type invalid as well.'},
            {img: '', name: 'Yeasir Arafat', context: 'Turns out semicolon-less style is easier and safer in TS because most gotcha edge cases are type invalid as well.'},
            {img: '', name: 'Yeasir Arafat', context: 'Turns out semicolon-less style is easier and safer in TS because most gotcha edge cases are type invalid as well.'},
            {img: '', name: 'Yeasir Arafat', context: 'Turns out semicolon-less style is easier and safer in TS because most gotcha edge cases are type invalid as well.'},
        ]
      }),
      components: {Lightbox,carousel,Layout,ClientCarousael,CourseCarousael},
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