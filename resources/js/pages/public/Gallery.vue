<template>
    <Pages title="Gallery - UTC">
        <v-row>
            <v-col cols="12" sm="4" v-for="(image) in galleries" :key="image.id">
                <v-img @click="showLightbox(image.name)" style="cursor: pointer;" :src="image.name" aspect-ratio='1'></v-img>
            </v-col>
            <lightbox id="mylightbox" ref="lightbox" :images="galleries" :timeoutDuration="5000"></lightbox>
        </v-row>
    </Pages>
</template>
<script>
import Pages from '@/shared/public/components/Pages'
import Lightbox from 'vue-my-photos';
export default {
    props:{
        galleries: Array
    },
    components:{Pages,Lightbox},
    methods:{
       showLightbox: function(imageName) {
                this.$refs.lightbox.show(imageName);
        }
    }
}
</script>