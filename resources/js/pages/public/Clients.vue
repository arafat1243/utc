<template>
    <Layout title="Portfolio - UTC">
        <div>
            <v-container>
            <v-row>
                <v-col cols="12" sm="4" md="3">
                    <v-card>
                        <v-card-title class="primary--text font-weight-bold">Our Client Portfolio</v-card-title>
                         <div class="double-line-bottom-theme-colored-2"></div>
                         <v-list>
                             <inertia-link v-for="(item,i) in $page.menu[3].items" :key="i" :href="item.path" :class="$inertia.page.url.match(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light' : 'v-list-item v-list-item--link theme--light'"
                                >
                                <div class="v-list-item__icon">
                                    <v-icon class="nav-deawer-item-icon">mdi-square-medium-outline</v-icon>
                                </div>
                                <div class="v-list-item__content text-capitalize">
                                    <div class="v-list-item__title nav-deawer-item-title">{{ item.text }}</div>
                                </div>
                            </inertia-link>
                         </v-list>
                    </v-card>
                </v-col>
                <v-col cols="12" sm="7" md="8">
                    <v-row :justify="clients.length <= 0 ? 'center' : 'start'">
                        <v-col cols="12" v-if="clients.length <= 0">
                            <v-card height="200">
                                <v-alert type="info">
                                    No Client Found!
                                </v-alert>
                                <v-card-title class="text-justify">
                                    There is no Client available right now.
                                </v-card-title>
                            </v-card>
                        </v-col>
                        <v-col v-else cols="12" sm="4" v-for="(client,i) in clients" :key="i">
                            <v-hover>
                                <template v-slot:default="{ hover }">
                                    <v-card min-height="200" max-height="250">
                                        <v-img max-height="250" :src="client.avatar"></v-img>
                                        <v-fade-transition>
                                        <v-overlay v-if="hover" z-index="2" absolute color="#036358">
                                            <v-btn color="primary" @click="moreInfo(client)">See more info</v-btn>
                                        </v-overlay>
                                        </v-fade-transition>
                                    </v-card>
                                </template>
                            </v-hover>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
        </div>
        <div class="w-100 transform-0 white-dark py-10">
             <ClientCarousael/>
        </div>
        <v-dialog persistent v-model="dialog" max-width="700">
            <v-card max-height="400">
                <v-card-title> 
                    <v-spacer></v-spacer>
                    <v-btn icon fab small @click="dialog = false"><v-icon>mdi-close-circle</v-icon></v-btn>
                </v-card-title>
                <v-row no-gutters justify="center" class="pa-2">
                    <v-col cols="12" md="4"><v-img max-height="150" max-width="150" :src="viewMore.avatar"></v-img></v-col>
                    <v-col cols="12" md="8">
                        <v-card-title>
                                <v-icon left>mdi-domain</v-icon>
                               {{viewMore.title}}
                            </v-card-title>
                            <v-card-text v-html="viewMore.details"></v-card-text>
                    </v-col>
                </v-row>
            </v-card>
        </v-dialog>
    </Layout>
</template>
<script>
import Layout from '@/shared/public/Layout';
import ClientCarousael from '@/shared/public/components/ClientCarousael';
export default {
    data: ()=>({
        overlay: false,
        dialog: false,
        viewMore: [],
    }),
    props: ['clients'],
    components: {ClientCarousael,Layout},
    methods:{
        moreInfo(item){
            this.viewMore = item;
            this.dialog = true;
        }
    }
}
</script>
<style lang="scss" scoped>

</style>