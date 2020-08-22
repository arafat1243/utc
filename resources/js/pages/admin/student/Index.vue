<template>
    <Layout title="Users">
        <v-data-table :headers="headers" class="elevation-1" :items="studentsOnly" :search="search" hide-default-footer multi-sort>
          <template v-slot:top>
            <v-toolbar flat color="white">
              <v-toolbar-title>Students Managemant</v-toolbar-title>
              <v-divider class="mx-4" inset vertical></v-divider>
              <v-spacer></v-spacer>
              <v-text-field v-model="search" ppend-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
            </v-toolbar>
          </template>
          <template v-slot:item.actions="{ item }">
            <div class="d-flex">
              <div v-for="(ui,i) in uiManage" :key="i">
                <v-tooltip bottom v-if="ui.can"> 
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon small class="mr-2" :color="ui.color"
                     @click="ui.text === 'Preview' ? preview(item) : ui.text === 'Edit' ? editItem(item) : deleteItem(item)" v-bind="attrs" v-on="on">
                      {{ui.icon}}
                    </v-icon>
                  </template>
                  <span>{{ui.text}}</span>
                </v-tooltip>
              </div>
            </div>
          </template>
        </v-data-table>
        <Pagination class="mt-4" :links="students"/>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Pagination from '@/shared/admin/components/Pagination'
import Auth from '@/auth'
export default {
    data: ()=>({
      headers: [
        {text: 'Name',value: 'name',align: 'left'},
        {text: 'Avatar',value: 'avatar',sortable: false},
        {text: 'E-mail',value: 'email'},
        {text: 'Phone Number',value: 'number'},
        {text: 'Emergency Number',value: 'emergency_number'},
        {text: 'Actions',value: 'actions', sortable: false},
      ],
      studentsOnly: [],
      search: '',
      uiManage:[]
    }),
    props: ['students'],
    mounted(){
      this.init();
    },
    methods:{
      init(){
        let role  = new Auth(this.$page.auth.roles);
        this.uiManage = [
            {text: 'Preview',icon: 'mdi-eye',color: 'primary',can: role.can('student_view')},
            {text: 'Edit',icon: 'mdi-eye', color: 'success',can: role.can('student_update')},
            {text: 'Delete',icon: 'mdi-delete',color: 'error',can: role.can('student_delete')}
        ]
      },
      preview(item){

      },
      editItem(item){

      },
      deleteItem(item){

      }
    },
    components:{
        Layout,Pagination
    }
}
</script>
<style lang="scss" scoped>
  .norenepss{
    background-color: #f1f3f6 !important; 
    padding: 10px !important;
    border: none;
    border-radius: 10px !important;
    box-shadow: 3px 3px 3px rgba(55, 84, 170, 0.1), -3px -3px 3px rgb(255, 255, 255);
  }
</style>