<template>
    <Layout title="Student Management">
        <v-container>
            <v-row justify="center">
                <v-col cols="8">
                    <v-card>
                        <v-expansion-panels accordion>
                            <v-expansion-panel v-for="(item,i) in 5" :key="i">
                            <v-expansion-panel-header><span>Java</span> <v-spacer></v-spacer> <span class="chip" :class="chipColor('ongoing')">ongoing</span></v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-timeline dense>
                                <v-timeline-item v-for="n in 3" :key="n" small>
                                    <v-card class="elevation-2">
                                    <v-card-title class="headline">Lorem ipsum</v-card-title>
                                    <v-card-text>
                                        Lorem ipsum dolor sit amet, no nam oblique veritus. Commune scaevola imperdiet nec ut, sed euismod convenire principes at. Est et nobis iisque percipit, an vim zril disputando voluptatibus, vix an salutandi sententiae.
                                    </v-card-text>
                                    </v-card>
                                </v-timeline-item>
                                </v-timeline>
                            </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </Layout>
</template>
<script>
import Layout from './Layout'
export default {
    methods:{
        chipColor(status){
        if(status == 'panding'){
          return 'error'
        }else if(status == 'ongoing'){
          return 'warning'
        }else{
          return 'success'
        }
      },
    },
    components:{Layout}
}
</script>
<style lang="scss" scoped>
    .chip{
        padding: 8px 0;
        text-transform: capitalize;;
        border-radius: 20px;
        border: none;
        text-align: center;
        color: #f1f3f6 !important;
    }
</style>