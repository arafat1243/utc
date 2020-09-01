<template>
    <Layout title="Services - UTC">
        <div>
            <v-container>
            <v-row>
                <v-col cols="12" sm="4" md="3">
                    <v-card>
                        <v-card-title class="primary--text font-weight-bold">Our Services</v-card-title>
                         <div class="double-line-bottom-theme-colored-2"></div>
                         <v-list>
                             <inertia-link v-for="(item,i) in $page.menu[1].items" :key="i" :href="item.path" :class="$inertia.page.url.match(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light' : 'v-list-item v-list-item--link theme--light'"
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
                    <v-card>
                        <v-card-title class="primary white--text text-uppercase">{{service.title}}</v-card-title>
                        <v-img :src="service.banner_img" max-height="400"></v-img>
                        <v-card-text v-html="service.details"></v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        </div>
        <div class="w-100 transform-0 white-dark py-10">
             <ClientCarousael/>
        </div>
    </Layout>
</template>
<script>
import Layout from '@/shared/public/Layout';
import ClientCarousael from '@/shared/public/components/ClientCarousael';
export default {
    props:['service'],
    components: {ClientCarousael,Layout},
}
</script>
<style lang="scss" scoped>

</style>