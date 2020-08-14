<template>
    <Layout :title="title">
        <v-container>
            <v-row>
                <v-col cols="12"  md="3">
                    <v-card>
                        <v-card-title class="primary--text font-weight-bold">About Us</v-card-title>
                         <div class="double-line-bottom-theme-colored-2"></div>
                         <v-list>
                             <inertia-link v-for="(item,i) in this.$page.menu[5].items" :key="i" :href="item.path" :class="$inertia.page.url.match(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light' : 'v-list-item v-list-item--link theme--light'"
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
                <v-col cols="12" md="9">
                    <slot/>
                </v-col>
            </v-row>
        </v-container>
    </Layout>
</template>
<script>
import Layout from '../Layout'
export default {
    data: ()=>({
    }),
    props:['title'],
    components: {Layout},
}
</script>