<template>
    <Layout title="Gallery & Sliders">
        <v-data-table
            :headers="headers"
            :items="photosOnly"
            :search="search"
            multi-sort
            hide-default-footer
            class="elevation-1"
        >
            <template v-slot:item.slide="{ item }">
                <v-chip  class="ma-1" :color="item.slide ? 'primary' : 'error'">{{item.slide ? 'Active' : 'Deactive'}}</v-chip>
            </template>
            <template v-slot:item.path="{ item }">
                <v-avatar size="80">
                  <v-img :src="$page.baseUrl+item.path"></v-img>
                </v-avatar>
            </template>
            <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>Gallery & Sliders</v-toolbar-title>
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
                <template v-if="role.can('gallery_create')" v-slot:activator="{ on, attrs }">
                    <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">New Items</v-btn>
                </template>
                <v-card>
                    <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                    </v-card-title>
                  <v-form ref="form" @submit.prevent="save" method="post" enctype="multipart/form-data">
                    <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" v-if="editedIndex === -1">
                                <v-file-input v-model="editedItem.photos" multiple show-size counter accept="image/*"  label="Choose Images"></v-file-input>   
                            </v-col>
                            <v-col cols="12" v-if="editedIndex !== -1">
                                <v-text-field required v-model="editedItem.header" 
                                    label="Slider Headline" outlined></v-text-field>     
                            </v-col>
                            <v-col cols="12" v-if="editedIndex !== -1">
                              <v-select
                                v-model="editedItem.slide"
                                :items="[{value: 1,text:'Active'},{value: 0,text:'Deactive'}]"
                                label="Select status"
                                chips
                                outlined
                              ></v-select>
                            </v-col>
                            <v-col cols="12" v-if="editedIndex !== -1">
                                <v-textarea outlined v-model="editedItem.paragraph" label="Slider Paragraph" ></v-textarea>
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
              <v-card-title class="headline">Delete Photo?</v-card-title>
              <v-card-text class="white--text">
                Are you sure you want to delete this Photo?
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="white darken-1" text @click="()=>{this.deleteDialog = false;this.editedItem = this.defaultItem;}">No</v-btn>
                <v-btn color="white darken-1" text @click="deleteItem">Yes</v-btn>
              </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="detailsDialog" hide-overlay persistent max-width="500">
          <v-card>
              <v-card-title class="headline">
                <v-spacer></v-spacer>
                <v-btn icon fab small @click="()=>{this.detailsDialog = false;this.singlePhoto = {};}">
                  <v-icon small>mdi-close</v-icon>
                </v-btn>
              </v-card-title>
                <div style="position: relative;">
                  <v-img max-height="400" :src="singlePhoto.path"></v-img>
                  <v-overlay absolute :opacity=".0" v-if="singlePhoto.header || singlePhoto.paragraph">
                    <div class="d-flex justify-center align-center flex-column">
                      <div class="text-h4 d-flex" v-html="singlePhoto.header"></div>
                      <p class="ftco-paragraph text-caption text-md-subtitle-1" v-html="singlePhoto.paragraph"></p>
                    </div>
                  </v-overlay>
                </div>
          </v-card>
        </v-dialog>
        <Pagination class="mt-4" :links="this.photos"/>
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
      singlePhoto: {},
      search: '',
      headers: [
        {
          text: 'Image',
          align: 'start',
          sortable: false,
          value: 'path',
        },
        { text: 'Slider Status', value: 'slide' },
        { text: 'Headline', value: 'header'},
        { text: 'Paragraph', value: 'paragraph' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      photosOnly: [],
      editedIndex: -1,
      editedItem: {
        id: 0,
        photos: [],
        slide: false,
        header: '',
        paragraph: ''
      },
      defaultItem: {
        id: 0,
        photos: [],
        slide: false,
        header: '',
        paragraph: ''
      },
      uiManager: []
    }),
    props:['photos'],
    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Add Photos' : 'Edit Slider'
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
            {text: 'Preview',icon: 'mdi-eye',color: 'primary',can: this.role.can('gallery_view')},
          {text: 'Edit', icon: 'mdi-pencil', color: 'primay', can: this.role.can('gallery_update')},
          {text: 'Delete', icon: 'mdi-delete', color: 'error', can: this.role.can('gallery_delete')},
        ];
        this.photosOnly = this.photos.data;
      },
      preview(item){
        this.singlePhoto = {
          path: this.$page.baseUrl+item.path,
          header: item.header,
          paragraph: item.paragraph
        };
        this.detailsDialog = true
      },
      editItem (item) {
        this.editedIndex = this.photosOnly.indexOf(item)
        this.editedItem.header = item.header
        this.editedItem.id = item.id
        this.editedItem.paragraph = item.paragraph
        this.editedItem.slide = item.slide
        this.dialog = true
      },
      showDelete(id){
        this.editedItem.id = id;
        this.deleteDialog = true;
      },
      deleteItem () {
        if(this.editedItem.id){
           this.$inertia.delete(this.$route('gallery.destroy',this.editedItem.id))
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
            formData.append('header',this.editedItem.header);
            formData.append('paragraph',this.editedItem.paragraph);
            formData.append('slide',this.editedItem.slide);
            this.$inertia.post(this.$route('gallery.update',this.editedItem.id), formData, {
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
                for(let i = 0; i != this.editedItem.photos.length; i++){
                    formData.append('photos['+i+']',this.editedItem.photos[i]);
                } 
                this.$inertia.post(this.$route('gallery.store'), formData)
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
    },
    components:{
        Layout,Pagination
    }
}
</script>