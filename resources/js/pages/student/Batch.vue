<template>
    <Layout title="Student Management">
        <v-container>
            <v-row>
                <v-col cols="12" sm="6" md="4" v-for="(batch, i) in batches" :key="i">
                    <v-card color="'white">
                        <v-img height="280px" :src="$page.baseUrl+batch.course.banner_img"></v-img>
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
                </v-col>
            </v-row>
        </v-container>
    </Layout>
</template>
<script>
import Layout from './Layout'
export default {
    props:['batches'],
    components:{Layout}
}
</script>
