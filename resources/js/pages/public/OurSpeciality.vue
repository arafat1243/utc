<template>
    <About title="About UTC">
        <v-card>
            <v-img :src="banner"></v-img>
            <v-card-title><v-icon left color="primary" large>mdi-trophy-award</v-icon> OUR SPECIALITY</v-card-title>
            <v-card-text>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium hic delectus reprehenderit quis et ex inventore sed minus illo earum omnis dolores natus optio blanditiis soluta accusamus dignissimos a cum cumque quas vel praesentium, itaque dolorem. Nobis nesciunt, perspiciatis magnam deserunt labore aliquid saepe aliquam assumenda nihil, libero suscipit inventore beatae culpa minus laboriosam officiis enim voluptate quos non sunt?
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