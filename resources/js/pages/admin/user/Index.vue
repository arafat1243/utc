<template>
    <Layout title="Users">
        <v-data-table
            :headers="headers"
            :items="usersOnly"
            :search="search"
            multi-sort
            hide-default-footer
            class="elevation-1"
        >
            <template v-slot:item.courses.length="{ item }">
                <v-chip :color="getColor(item.courses.length)" dark>{{ item.courses.length }}</v-chip>
            </template>
            <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>User Managemant</v-toolbar-title>
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
                <v-dialog v-model="dialog" max-width="500px">
                  <template v-slot:activator="{ on, attrs }">
                      <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">Add User</v-btn>
                  </template>
                <v-card>
                    <v-card-title>
                    <span class="headline">{{formTitle}}</span>
                    </v-card-title>
                  <v-form @submit.prevent="save" ref="userForm" method="post">
                    <v-card-text>
                    <v-container>
                        <v-row>
                          <v-col cols="12" v-if="editedIndex === -1">
                            <v-text-field
                              v-model="editedItem.name"
                              :rules="[v => !!v || 'Name is required',v => (v && v.length > 5 || 'Name Must more than 5 characters')]"
                              label="Full Name"
                              required
                              outlined
                            ></v-text-field>
                          </v-col>
                          <v-col cols="12" v-if="editedIndex === -1">
                            <v-text-field
                              v-model="editedItem.email"
                              :rules="emailRules"
                              label="E-mail"
                              required
                              outlined
                            ></v-text-field>
                          </v-col>
                            <v-col cols="12">
                              <v-select
                                v-model="editedItem.role_id"
                                :items="roles"
                                label="Select Roles"
                                multiple
                                chips
                                outlined
                              ></v-select>
                            </v-col>
                        </v-row>
                    </v-container>
                    </v-card-text>

                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                    <v-btn color="blue darken-1" :loading="loading" text type="submit">Save</v-btn>
                    </v-card-actions>
                  </v-form>
                </v-card>
                </v-dialog>
            </v-toolbar>
            </template>
            <template v-slot:item.roles='{ item }'>
              <v-sheet max-width="200" color="transparent">
                <v-chip-group multiple>
                  <v-chip  class="ma-1" v-for="(role,i) in item.roles" :color="(i%2) == 0 ? 'primary' : 'error'" :key="i">{{role.title}}</v-chip>
                </v-chip-group>
              </v-sheet>
            </template>
            <template v-slot:item.status='{ item }'>
                <v-chip  class="ma-1" :color="item.status ? 'primary' : 'error'">{{item.status ? 'Active' : 'Deactive'}}</v-chip>
            </template>
            <template v-slot:item.avatar='{ item }'>
              <v-avatar size="50">
                <v-img :src="item.avatar"></v-img>
              </v-avatar>
            </template>
            <template v-slot:item.actions="{ item }">
              <v-tooltip bottom>
                    <template v-if="item.status" v-slot:activator="{ on, attrs }">
                        <v-icon small class="mr-2" color="info" @click="preview(item)" v-bind="attrs" v-on="on">
                            mdi-eye
                        </v-icon>
                    </template>
                    <span>Details</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon small class="mr-2" color="success" @click="editItem(item)" v-bind="attrs" v-on="on">
                            mdi-pencil
                        </v-icon>
                    </template>
                    <span>Edit</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon small class="mr-2" color="error" @click="showDelete(item.id)" v-bind="attrs" v-on="on">
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
            {{$page.successMessage.message || error}}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <v-dialog v-model="deleteDialog" persistent max-width="290">
          <v-card color="warning white--text">
              <v-card-title class="headline">Delete User?</v-card-title>
              <v-card-text class="white--text">
                Are you sure you want to delete this User?
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="white darken-1" text @click="()=>{this.deleteDialog = false;this.editedItem = this.defaultItem;}">No</v-btn>
                <v-btn color="white darken-1" text @click="deleteItem">Yes</v-btn>
              </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="detailsDialog" fullscreen hide-overlay transition="dialog-bottom-transition"> 
                <v-card>
                <v-toolbar dark color="primary">
                <v-btn icon dark @click="()=>{detailsDialog = false;singleUser = {}}">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>User Details</v-toolbar-title>
                <v-spacer></v-spacer>
                </v-toolbar>
                <v-container>
                  <v-row justify="center">
                    <v-col cols="10">
                      <v-row>
                        <v-col cols="4" class="norenepss">
                            <v-img height="250" :src="singleUser.avatar"></v-img>
                        </v-col>
                        <v-col cols="8">
                          <v-card-title class="norenepss ma-3">Full Name : {{singleUser.name}}</v-card-title>
                          <v-card-title class="norenepss ma-3">E-mail : {{singleUser.email}}</v-card-title>
                          <v-card-title class="norenepss ma-3">Contact Number : {{singleUser.employe ? singleUser.employe.number : ''}}</v-card-title>
                        </v-col>
                      </v-row>
                      <embed width="100%" height="500" :src="singleUser.employe ? singleUser.employe.cv : ''" type="application/pdf">
                 </v-col>
            </v-row>
          </v-container>
            </v-card>
        </v-dialog>
        <Pagination class="mt-4" :links="this.users"/>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Pagination from '@/shared/admin/components/Pagination'
export default {
      remember: {
        data: ['loading'],
      },
     data: () => ({
      dialog: false,
      snackbar: false,
      loading: false,
      search: '',
      error: '',
      singleUser: {},
      detailsDialog: false,
      deleteDialog: false,
      headers: [
        {
          text: 'Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Avatar', value: 'avatar' },
        { text: 'E-mail', value: 'email'},
        { text: 'Roles', value: 'roles',align:'center'},
        { text: 'Status', value: 'status',align:'center'},
        { text: 'Created At', value: 'created_at' },
        { text: 'Updated At', value: 'updated_at' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
      usersOnly: [],
      editedIndex: -1,
      editedItem: {
        id: 0,
        name: '',
        email: '',
        role_id: [],
      },
      defaultItem: {
        id: 0,
        name: '',
        email: '',
        role_id: [],
      },
    }),
    props:['users','roles'],
    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Add New User' : 'Give User Roels'
      },
    },
    watch: {
      dialog (val) {
        val || this.close()
      },
    },
    mounted(){
        this.initialize()
        this.snackbar = this.$page.successMessage.success;
    },
    methods: {
      initialize () {
        this.usersOnly = this.users.data;
      },
      preview (item) {
        this.singleUser = item;
        this.detailsDialog = true
      },
      editItem (item) {
        this.editedIndex = this.usersOnly.indexOf(item)
        item.roles.forEach(id => {
          this.editedItem.role_id.push(id.id)
        })
        this.editedItem.status = item.status;
        this.editedItem.id = item.id;
        this.dialog = true
      },
      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = this.defaultItem;
          this.editedIndex = -1
        })
      },
      showDelete(id){
        this.editedItem.id = id;
        this.deleteDialog = true;
      },
      deleteItem () {
        if(this.editedItem.id){
           this.$inertia.delete(this.$route('users.destroy',this.editedItem.id))
           .then(()=>{
                  this.deleteDialog = false;
                  this.snackbar = true;
                  this.editedItem = this.defaultItem;
                  this.initialize();               
                }).catch(err => {console.log(err)})
        }
      },
      save () {
        if(this.$refs.userForm.validate()){
          this.loading = true
            if(this.editedIndex > -1){
              let formData = new FormData();
              formData.append('role_id',this.editedItem.role_id);
              formData.append('status',this.editedItem.status);
              this.$inertia.post(this.$route('users.addRole',this.editedItem.id), formData, {
                replace: false,
                preserveState: true,
                preserveScroll: false,
                only: [],
              }).then(re => {
                      this.snackbar = true;
                      this.loading = false;
                      if(this.$page.successMessage.success){
                        this.$refs.userForm.reset()
                          this.initialize();
                          this.close();
                      }
                  })
                  .catch(err => console.log(err));
            }
            else{
              let formData = new FormData();
              formData.append('role_id',this.editedItem.role_id);
              formData.append('name',this.editedItem.name);
              formData.append('email',this.editedItem.email);
              this.$inertia.post(this.$route('users.addUser'), formData, {
                replace: false,
                preserveState: true,
                preserveScroll: false,
                only: [],
              }).then(re => {
                      this.snackbar = true;
                      this.loading = false;
                      if(this.$page.successMessage.success){
                          this.$refs.userForm.reset()
                          this.editedItem = this.defaultItem;
                          this.initialize();
                      }
                      if(this.$page.errors.email){
                        this.error = this.$page.errors.email[0];
                        this.snackbar = true
                      }
                                
                  })
                  .catch(err => console.log(err));
            }
        }
      },
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