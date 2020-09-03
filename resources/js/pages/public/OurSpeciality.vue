<template>
    <About title="About UTC">
        <v-card>
            <v-img :src="banner"></v-img>
            <v-card-title><v-icon left color="primary" large>mdi-trophy-award</v-icon> OUR SPECIALITY</v-card-title>
            <v-card-text>
                Software Development, Android App Development, Web Solution, Automation, Security Solution and Networking Solution.
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