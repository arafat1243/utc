<template>
    <Layout :title="title">
        <v-container>
            <v-row>
                <v-col cols="12" md="9">
                    <v-sheet elevation="5" rounded style="position: relative;">
                <v-img max-height="300px" :src="$page.baseUrl+course.banner_img"></v-img>
                <v-overlay absolute opacity=".4" z-index="1">
                    <v-chip class="ma-2 ma-md-1" color="success">Course Duration : {{course.course_duration}}</v-chip>
                    <v-chip class="ma-2 ma-md-1" color="warning">Class Duration : {{course.class_duration}}</v-chip>
                    <v-chip class="ma-2 ma-md-1" color="info">Total Class : {{course.class_count}}</v-chip>
                </v-overlay>
            </v-sheet>
            <v-card tile>
                <v-card-title class="font-weight-medium">{{course.title}}</v-card-title>
                <v-card-text class="font-18 font-weight-bold" v-html="course.details"></v-card-text>
                <v-expansion-panels v-if="course.content"  multiple hover accordion focusable>
                    <v-expansion-panel v-for="(item,i) in contentMaker(course.content)" :key="i">
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
                </v-col>
            </v-row>
            <div :class="$vuetify.breakpoint.mdAndUp ? 'right-content' :'buttom-content'">
                <v-sheet width="300" height="320" elevation="12">
                    <v-sheet style="position: relative;" height="80" class="white--text" color="primary" tile title>
                        <span class="center font-weight-bold text-h6">
                            <v-icon left large color="white">mdi-currency-bdt</v-icon>
                            {{course.fees}}
                        </span>
                    </v-sheet>
                    <inertia-link :href="$route('public.apply.create',[course.id,batch_id])" class="v-center white--text font-weight-bold px-15 py-6 text-h6 my-5 v-btn v-btn--contained v-btn--rounded theme--light v-size--default green">
                            <span class="v-btn__content">Apply</span>
                    </inertia-link>
                    <v-divider class="my-3"></v-divider>
                    <ul class="list-group grey--text text--darken-2 mt-3">
                        <li class="mb-2">INCLUDES</li>
                        <li><v-icon left>mdi-translate</v-icon> বাংলা</li>
                        <li><v-icon left>mdi-wrench</v-icon> Support</li>
                        <li><v-icon left>mdi-trophy</v-icon>Certificate of Completion</li>
                    </ul>
                </v-sheet>
            </div>
            <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message || errorMessage}}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        </v-container>
    </Layout>
</template>
<script>
import Layout from '@/shared/public/Layout'
export default {
    data: vm => ({
      snackbar: false,
      errorMessage: '',
      title: vm.course.title+' Deatils'
    }),
    props:['course','batch_id'],
    methods:{
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
    components:{Layout}
}
</script>
<style lang="scss" scoped>
    .right-content{
        top: 50%;
        right: 30px;
        transform: translateY(-50%);
        position: fixed;
        z-index: 5;
        transition: all ease-in-out .5s;
    }
    .center{
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            position: absolute;
        }
        .v-center{
            left: 50%;
            transform: translateX(-50%);
            position: relative;
        }
        .list-group{
            list-style: none;
            width: calc(100% - 50px);
            margin-left: auto;
            margin-right: auto;
            font-weight: 600;
            li{
                &:not(:nth-child(1)){
                    font-size: 14px;
                    margin: 5px 0;
                }
            }
        }
    .buttom-content{
        width: 300px;
        margin: 30px 0;
        margin-left: auto;
        margin-right: auto;
        transition: all ease-in-out .5s;
    }
</style>