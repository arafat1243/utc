<template>
    <div class="client-carousael">
        <div class="text-h5 text-md-h4 ftco-headline justify-center white--text">Our <div>clients</div></div>
        <div class="d-flex justify-center">
            <div class="ftco-for-headline double-line-bottom-theme-colored-2"></div>
        </div>
        <carousel :items="5" :dots="false" :autoplay="clients.length > 4 ? true : false" :autoWidth="true"  :center="true" :nav="false" :loop="clients.length > 4 ? true : false"  class="mt-4">
            <v-avatar size="150" class="mx-10" v-for="(client,i) in clients" :key="i">
                <v-img :src="client.avatar"></v-img>
            </v-avatar>
        </carousel>
    </div>
</template>
<script>
import carousel from 'vue-owl-carousel';
import axios from 'axios'
export default {
    data: () =>({
        clients: [
            {avatar: 'storage/images/web.jpg'},
        ],
    }),
    components:{carousel},
    methods:{
        initialize(){
           axios.get(this.$route('public.clientForCarasul'))
            .then((res)=>{
                this.clients = res.data.client;    
            })
            .catch(err => console.log(err))
        }
    },
    created(){
        this.initialize();
    }
}
</script>