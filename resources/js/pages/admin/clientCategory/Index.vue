<template>
    <Layout title="Client Category">
        <v-data-table
            :headers="headers"
            :items="categoriesOnly"
            :search="search"
            multi-sort
            hide-default-footer
            class="elevation-1"
        >
            <template v-slot:item.clients_count="{ item }">
                <v-chip :color="getColor(item.clients_count)" dark>{{ item.clients_count }}</v-chip>
            </template>
            <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>Client Categories</v-toolbar-title>
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
                <template v-if="role.can('client_cate_create')" v-slot:activator="{ on, attrs }">
                    <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">New Item</v-btn>
                </template>
                <v-card>
                    <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                    </v-card-title>
                  <v-form ref="form" @submit.prevent="save">
                    <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field required v-model="editedItem.title" :rules="[v => !!v || 'Title is required',v => (v && v.length > 5) || 'You Must input Minimum 5 characters']"
                                    label="Category Title" outlined></v-text-field>     
                            </v-col>
                        </v-row>
                    </v-container>
                    </v-card-text>

                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                    <v-btn color="blue darken-1" text type="submit">Save</v-btn>
                    </v-card-actions>
                  </v-form>
                </v-card>
                </v-dialog>
            </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
                <div class="d-flex">
                  <div v-for="(ui,i) in uiManager" :key="i">
                  <v-tooltip bottom  v-if="ui.can">
                      <template v-slot:activator="{ on, attrs }">
                          <v-icon small class="mr-2" :color="ui.color" @click="ui.text === 'Edit' ? editItem(item) : showDelete(item.id)" v-bind="attrs" v-on="on">
                              {{ui.icon}}
                          </v-icon>
                      </template>
                      <span>{{ui.text}}</span>
                  </v-tooltip>
                  <v-chip color="warning" v-if="!ui.can && i==0">no Actions</v-chip>
                </div>
                </div>
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
            <v-card-title class="headline">Delete Category?</v-card-title>
            <v-card-text class="white--text">
              Are you sure you want to delete this category?
              if you delete this category all Clients will be delete
              under this category.
            </v-card-text>
            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="white darken-1" text @click="()=>{this.deleteDialog = false;this.editedItem = this.defaultItem;}">No</v-btn>
            <v-btn color="white darken-1" text @click="deleteItem">Yes</v-btn>
            </v-card-actions>
        </v-card>
        </v-dialog>
        <Pagination class="mt-4" :links="this.clientCategories"/>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Pagination from '@/shared/admin/components/Pagination'
import Auth from '@/auth'
export default {
     data: () => ({
       role: '',
      dialog: false,
      snackbar: false,
      deleteDialog: false,
      search: '',
      headers: [
        {
          text: 'Title',
          align: 'start',
          sortable: false,
          value: 'title',
        },
        { text: 'Slug', value: 'slug' },
        { text: 'Total Clients', value: 'clients_count', align: 'center'},
        { text: 'Created At', value: 'created_at' },
        { text: 'Updated At', value: 'updated_at' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      categoriesOnly: [],
      editedIndex: -1,
      editedItem: {
        id: 0,
        title: ''
      },
      defaultItem: {
        id: 0,
        title: ''
      },
      uiManager: []
    }),
    props:['clientCategories'],
    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Add Category' : 'Edit Category'
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
        this.role = new Auth(this.$page.auth.roles);
        this.uiManager = [
          {text: 'Edit', icon: 'mdi-pencil', color: 'primay', can: this.role.can('client_cate_update')},
          {text: 'Delete', icon: 'mdi-delete', color: 'error', can: this.role.can('client_cate_delete')},
        ];
        this.categoriesOnly = this.clientCategories.data;
      },

      editItem (item) {
        this.editedIndex = this.categoriesOnly.indexOf(item)
        this.editedItem.title = item.title
        this.editedItem.id = item.id
        this.dialog = true
      },
      showDelete(id){
        this.editedItem.id = id;
        this.deleteDialog = true;
      },
      deleteItem () {
        if(this.editedItem.id){
           this.$inertia.delete(this.$route('clientCategories.destroy',this.editedItem.id))
           .then(()=>{
                  this.deleteDialog = false;
                  this.snackbar = true;
                  this.editedItem = this.defaultItem;
                  this.initialize();               
                }).catch(err => {console.log(err)})
        }
      },
      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.$refs.form.reset();
          this.editedItem = this.defaultItem;
          this.editedIndex = -1
        })
      },

      save () {
        if(this.$refs.form.validate()){
        if (this.editedIndex > -1) {
            let formData = new FormData();
            formData.append('title',this.editedItem.title);
            this.$inertia.post(this.$route('clientCategories.update',this.editedItem.id), formData, {
              replace: false,
              preserveState: true,
              preserveScroll: false,
              only: [],
            }).then(re => {
                    this.snackbar = true;
                    if(this.$page.successMessage.success){
                        this.$refs.form.reset();
                        this.editedItem = this.defaultItem;
                        this.initialize();
                    }
                })
                .catch(err => console.log(err));
          
        } else {
                let formData = new FormData();
                formData.append('title',this.editedItem.title);
                this.$inertia.post(this.$route('clientCategories.store'), formData)
                .then(re => {
                    this.snackbar = true;
                    if(this.$page.successMessage.success){
                        this.$refs.form.reset();
                        this.editedItem = this.defaultItem;
                        this.initialize();
                    }
                })
                .catch(err => console.log(err));
            }
            this.close()
        }
        
      },
      getColor (total) {
        if (total < 3) return 'red'
        else if (total < 5) return 'orange'
        else return 'primary'
      },
    },
    components:{
        Layout,Pagination
    }
}
</script>