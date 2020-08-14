<template>
    <Layout title="Reviews">
        <v-data-table
            :headers="headers"
            :items="reviewsOnly"
            :search="search"
            multi-sort
            hide-default-footer
            class="elevation-1"
        >
            <template v-slot:item.approved="{ item }">
                <v-chip  class="ma-1" :color="item.approved ? 'primary' : 'error'">{{item.approved ? 'Active' : 'Deactive'}}</v-chip>
            </template>
            <template v-slot:item.avatar="{ item }">
                <v-avatar size="80">
                  <v-img :src="$page.baseUrl+item.avatar"></v-img>
                </v-avatar>
            </template>
            <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>Reviews</v-toolbar-title>
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
                <v-dialog v-model="dialog" max-width="500px">
                <v-card>
                    <v-card-title>
                    <span class="headline">Approve review</span>
                    </v-card-title>
                  <v-form ref="form" @submit.prevent="save" method="post">
                    <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                              <v-select
                                v-model="editedItem.approved"
                                :items="[{value: 1,text:'Active'},{value: 0,text:'Deactive'}]"
                                label="Approved status"
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
                          <v-icon small class="mr-2" :color="ui.color" @click="ui.text === 'Edit' ? editItem(item) : ui.text === 'Delete' ?  showDelete(item.id) : preview(item)" v-bind="attrs" v-on="on">
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
              <v-card-title class="headline">Delete Review?</v-card-title>
              <v-card-text class="white--text">
                Are you sure you want to delete this Review?
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="white darken-1" text @click="()=>{this.deleteDialog = false;this.editedItem = this.defaultItem;}">No</v-btn>
                <v-btn color="white darken-1" text @click="deleteItem">Yes</v-btn>
              </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="detailsDialog" persistent max-width="500">
          <v-card>
              <v-card-title class="headline">
                <v-spacer></v-spacer>
                <v-btn icon fab small @click="()=>{this.detailsDialog = false;this.singlereview = {};}">
                  <v-icon small>mdi-close</v-icon>
                </v-btn>
              </v-card-title>
                <v-container>
                  <v-row justify="center" no-gutters>
                    <v-col cols="12" md="4">
                      <v-avatar size="150">
                        <v-img :src="singlereview.avatar"></v-img>
                      </v-avatar>
                    </v-col>
                    <v-col cols="12" md="8">
                      <v-card-title>{{singlereview.name}}</v-card-title>
                      <v-card-subtitle>
                        {{singlereview.email}}
                        <br/>
                        {{singlereview.number}}
                      </v-card-subtitle>
                      <v-card-text>{{singlereview.review}}</v-card-text>
                    </v-col>
                  </v-row>
                </v-container>
          </v-card>
        </v-dialog>
        <Pagination class="mt-4" :links="this.reviews"/>
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
      detailsDialog: false,
      singlereview: {},
      search: '',
      headers: [
        {
          text: 'Name',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Avatar', value: 'avatar' },
        { text: 'E-mail', value: 'email'},
        { text: 'Phone Number', value: 'number' },
        { text: 'Approved Status', value: 'approved' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      reviewsOnly: [],
      editedItem: {
        id: 0,
        approved: false,
      },
      defaultItem: {
        id: 0,
        approved: false,
      },
      uiManager: []
    }),
    props:['reviews'],
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
            {text: 'Preview',icon: 'mdi-eye',color: 'primary',can: this.role.can('review_view')},
          {text: 'Edit', icon: 'mdi-pencil', color: 'primay', can: this.role.can('review_update')},
          {text: 'Delete', icon: 'mdi-delete', color: 'error', can: this.role.can('review_delete')},
        ];
        this.reviewsOnly = this.reviews.data;
      },
      preview(item){
        this.singlereview = {
          avatar: this.$page.baseUrl+item.avatar,
          email: item.email,
          number: item.number,
          review: item.review,
          name: item.name,
        };
        this.detailsDialog = true
      },
      editItem (item) {
        this.editedItem.id = item.id
        this.editedItem.approved = item.approved
        this.dialog = true
      },
      showDelete(id){
        this.editedItem.id = id;
        this.deleteDialog = true;
      },
      deleteItem () {
        if(this.editedItem.id){
           this.$inertia.delete(this.$route('review.destroy',this.editedItem.id))
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
        })
      },

      save () {
        if(this.$refs.form.validate()){
            let formData = new FormData();
            formData.append('approved',this.editedItem.approved);
            this.$inertia.post(this.$route('review.update',this.editedItem.id), formData, {
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
            this.close()
        }
        
      },
    },
    components:{
        Layout,Pagination
    }
}
</script>