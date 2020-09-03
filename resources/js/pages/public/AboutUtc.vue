<template>
    <About title="About UTC">
        <v-card>
            <v-img :src="banner"></v-img>
            <v-card-title><v-icon left color="primary" large>mdi-trophy-award</v-icon> ABOUT UTC</v-card-title>
            <v-card-text>
                Universal Technology Corporation (UTC) is the pioneer software company to setup a bench mark in the industry. Our key principles includes maintaining the leading software solution provider, ensuring benefit of our clients, shareholders and employees, developing, disseminating and exploiting the remarkable experience, expertise and knowledge of all of our people and developing a distinctive competence in process development and project management. Very simply, providing the best service possible at all times while guaranteeing a quality product is our number one goal. 
<br/>
Universal Technology Corporation (UTC) is always with you to provide all kinds of IT Solutions.
            </v-card-text>
        </v-card>
    </About>
</template>
<script>
import About from '@/shared/public/components/About'
export default {
    props:['banner'],
    components:{About}
}
</script>