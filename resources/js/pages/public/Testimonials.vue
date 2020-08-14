<template>
    <Pages title="Testimonials - UTC">
        <v-row>
            <v-col cols="12" sm="4" v-for="(review,i) in reviews" :key="i">
                <v-card>
                    <v-img max-height="200" :src="$page.baseUrl+review.avatar"></v-img>
                    <v-card-title>{{review.name}}</v-card-title>
                    <v-card-subtitle>
                        {{review.review}}
                    </v-card-subtitle>
                </v-card>
            </v-col>
        </v-row>
    </Pages>
</template>
<script>
import Pages from '@/shared/public/components/Pages'
export default {
    props:{
        reviews: Array
    },
    components:{Pages},
}
</script>