<template>
    <v-app>
        <v-main>
            <Header/>
                <slot/>
            <Footer/>
        </v-main>
    </v-app>
</template>
<script>
import Header from './components/Header'
import Footer from './components/Footer'
export default {
    components:{
        Header,
        Footer
    },
    props: {
      title: String,
    },
    watch: {
      title: {
        immediate: true,
        handler(title) {
          document.title = title  
        },
      },
    },
}
</script>
