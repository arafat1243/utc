<template>
    <Layout title="Role">
        <v-data-table
            :headers="headers"
            :items="rolesOnly"
            :search="search"
            item-key="id"
            multi-sort
            hide-default-footer
            class="elevation-1"
        >
            <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>Manage Roles</v-toolbar-title>
                <v-divider
                class="mx-4"
                inset
                vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
                <v-spacer></v-spacer>
                <inertia-link :href="$route('roles.create')"  class="v-btn v-btn--active v-btn--flat v-btn--router theme--light v-size--default primary">
                       <span class="v-btn__content">Add New Role</span>
                </inertia-link>
            </v-toolbar>
            </template>
            <template v-slot:item.permissions='{ item }'>
              <v-sheet max-width="500" color="transparent">
                <v-chip-group multiple>
                  <v-chip  class="ma-1" v-for="(permission,i) in item.permissions" :color="(i%2) == 0 ? 'primary' : 'error'" :key="i">{{permission}}</v-chip>
                </v-chip-group>
              </v-sheet>
            </template>
            <template v-slot:item.actions="{ item }">
                 <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                     <inertia-link style="font-size:16px" :href="$route('roles.edit',item.id)" v-bind="attrs" v-on="on" class="v-icon notranslate mr-2 v-icon--link mdi mdi-pencil theme--light success--text"></inertia-link>
                    </template>
                    <span>Edit</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon small class="mr-2" color="error" v-bind="attrs" v-on="on" @click="showDelete(item.id)" >
                        mdi-delete
                    </v-icon>
                    </template>
                    <span>Delete</span>
                </v-tooltip>
            </template>
            <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
            </template>
        </v-data-table>
        <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message}}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <v-dialog v-model="deleteDialog" persistent max-width="290">
            <v-card color="warning white--text">
                <v-card-title class="headline">Delete Role?</v-card-title>
                <v-card-text class="white--text">
                  Are you sure you want to delete this Role?
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="white darken-1" text @click="()=>{this.deleteDialog = false;this.editedItem = this.defaultItem;}">No</v-btn>
                <v-btn color="white darken-1" text @click="deleteItem">Yes</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <Pagination class="mt-4" :links="this.roles"/>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Pagination from '@/shared/admin/components/Pagination'
export default {
   data: () => ({
      dialog: false,
      snackbar: false,
      deleteDialog: false,
      search: '',
      headers: [
        {
          text: 'Title',
          align: 'start',
          value: 'title',
        },
        { text: 'Permissions', value: 'permissions', width: 500,align: 'center' ,sortable: false},
        { text: 'Created At', value: 'created_at' },
        { text: 'Updated At', value: 'updated_at' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      rolesOnly: [],
      editedIndex: -1,
      editedItem: {
        id: 0,
        title: '',
        permissions: []
      },
      defaultItem: {
        id: 0,
        title: '',
        permissions: []
      },
    }),
    props:['roles'],
    mounted(){
      this.snackbar = this.$page.successMessage.success;
        this.initialize()
    },
    methods: {
      initialize () {
        this.rolesOnly = this.roles.data;
    },
      showDelete(id){
        this.editedItem.id = id;
        this.deleteDialog = true;
      },
      deleteItem () {
        if(this.editedItem.id){
           this.$inertia.delete(this.$route('roles.destroy',this.editedItem.id))
           .then(()=>{
                  this.deleteDialog = false;
                  this.editedItem = this.defaultItem;
                  this.snackbar = true;
                  this.initialize();            
                }).catch(err => {console.log(err)})
        }
      },
    },
    components:{
        Layout,Pagination
    }
}
</script>