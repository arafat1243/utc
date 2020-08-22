
<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      :clipped="$vuetify.breakpoint.lgAndUp"
      app
      :mini-variant.sync="mini"
      permanent
    >
      <v-list dense>
                    <div v-for="(item,i) in items"
                        :key="i" class="nav-deawer-item">
                        <inertia-link v-if="!item.items && item.can"  :href="item.path" :class="isRoute(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light primary--text' : 'v-list-item v-list-item--link theme--light'"
                            >
                            <div class="v-list-item__icon">
                                <v-icon class="nav-deawer-item-icon">{{ item.icon }}</v-icon>
                            </div>
                            <div class="v-list-item__content">
                                <div class="v-list-item__title nav-deawer-item-title">{{ item.title }}</div>
                            </div>
                        </inertia-link>
                        <v-list-group v-if="item.items && item.can">
                            <v-icon slot="prependIcon">{{item.icon}}</v-icon>
                            <template v-slot:activator>
                                <v-list-item-content>
                                    <v-list-item-title class="nav-deawer-item-title">{{ item.title }}</v-list-item-title>
                                </v-list-item-content>
                            </template>
                            <div v-for="(item, i) in item.items" :key="i">
                                <inertia-link v-if="!item.items && item.can" :class="isRoute(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light primary--text' : 'v-list-item v-list-item--link theme--light'" :href="item.path" role="menuitem"
                                        >
                                        <div class="v-list-item__icon">
                                        </div>
                                        <div class="v-list-item__content">
                                            <div class="v-list-item__title nav-deawer-item-title">{{ item.title }}</div>
                                        </div>
                                </inertia-link>
                                <v-list-group v-if="item.items && item.can" no-action sub-group>
                                    <template v-slot:activator>
                                        <v-list-item-content>
                                            <v-list-item-title>{{item.title}}</v-list-item-title>
                                        </v-list-item-content>
                                    </template>
                                    <inertia-link v-for="(item, i) in item.items"  :key="i" :class="isRoute(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light primary--text' : 'v-list-item v-list-item--link theme--light'" :href="item.path" role="menuitem"
                                       v-if="item.can" >
                                        <div class="v-list-item__content">
                                            <div class="v-list-item__title nav-deawer-item-title">{{ item.title }}</div>
                                        </div>
                                    </inertia-link>
                                </v-list-group>
                            </div>   
                        </v-list-group>
                    </div>
                </v-list>
    </v-navigation-drawer>

    <v-app-bar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
    >
      <v-app-bar-nav-icon @click.stop="mini = !mini"></v-app-bar-nav-icon>
      <v-toolbar-title
        
        class="ml-0 pl-4"
      >
        <span class="hidden-sm-and-down">Universal Technology Corporation</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-menu v-model="notifaction" bottom left transition="scale-transition" origin="top right">
            <template v-slot:activator="{ on }">
               <v-btn icon v-on="on" class="mr-4">
                                <v-badge :content="1" :value="1" color="green">
                                    <v-icon>mdi-bell</v-icon>
                                </v-badge>
                            </v-btn>
                        </template>
                        <v-card width="300">
                        <v-list>
                            <v-list-item>
                            <v-list-item-avatar size="80px">
                                <v-img :src="$page.baseUrl+$page.auth.avatar"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-title>{{$page.auth.name}}</v-list-item-title>
                                <v-list-item-subtitle>{{$page.auth.email}}</v-list-item-subtitle>
                            </v-list-item-content>
                            <v-list-item-action>
                                <v-btn
                                icon
                                @click="notifaction = false"
                                >
                                <v-icon>mdi-close-circle</v-icon>
                                </v-btn>
                            </v-list-item-action>
                            </v-list-item>
                        </v-list>
                        <v-list>
                            <inertia-link class="v-list-item v-list-item--link theme--light primary--text" :href="$route('users.profile')" role="menuitem">
                                <div class="v-list-item__icon">
                                    <v-icon>mdi-face-profile</v-icon>
                                </div>
                                <div class="v-list-item__content">
                                    <div class="v-list-item__title nav-deawer-item-title">Profile</div>
                                </div>
                            </inertia-link>
                            <inertia-link class="v-list-item v-list-item--link theme--light primary--text" terget="_self" href="/logout" method="post" role="menuitem">
                                <div class="v-list-item__icon">
                                    <v-icon>mdi-exit-to-app</v-icon>
                                </div>
                                <div class="v-list-item__content">
                                    <div class="v-list-item__title nav-deawer-item-title">Logout</div>
                                </div>
                            </inertia-link>
                        </v-list>
                        </v-card>
                    </v-menu>
      <v-menu v-model="menu" bottom right transition="scale-transition" origin="top right">
            <template v-slot:activator="{ on }">
               <v-btn icon large v-on="on">
                                <v-avatar size="80px" item>
                                    <v-img :src="$page.baseUrl+$page.auth.avatar"></v-img>
                                </v-avatar>
                            </v-btn>
                        </template>
                        <v-card width="300">
                        <v-list>
                            <v-list-item>
                            <v-list-item-avatar size="80px">
                                <v-img :src="$page.baseUrl+$page.auth.avatar"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-title>{{$page.auth.name}}</v-list-item-title>
                                <v-list-item-subtitle>{{$page.auth.email}}</v-list-item-subtitle>
                            </v-list-item-content>
                            <v-list-item-action>
                                <v-btn
                                icon
                                @click="menu = false"
                                >
                                <v-icon>mdi-close-circle</v-icon>
                                </v-btn>
                            </v-list-item-action>
                            </v-list-item>
                        </v-list>
                        <v-list>
                            <inertia-link class="v-list-item v-list-item--link theme--light primary--text" :href="$route('users.profile')" role="menuitem">
                                <div class="v-list-item__icon">
                                    <v-icon>mdi-face-profile</v-icon>
                                </div>
                                <div class="v-list-item__content">
                                    <div class="v-list-item__title nav-deawer-item-title">Profile</div>
                                </div>
                            </inertia-link>
                            <inertia-link class="v-list-item v-list-item--link theme--light primary--text" terget="_self" href="/logout" method="post" role="menuitem">
                                <div class="v-list-item__icon">
                                    <v-icon>mdi-exit-to-app</v-icon>
                                </div>
                                <div class="v-list-item__content">
                                    <div class="v-list-item__title nav-deawer-item-title">Logout</div>
                                </div>
                            </inertia-link>
                        </v-list>
                        </v-card>
                    </v-menu>
    </v-app-bar>
    <v-main>
          <slot/>
    </v-main>
    <v-footer  class="font-weight-medium" >
             <v-col class="text-center" cols="12">
                Copyright ©{{new Date().getFullYear()}} <Strong>UTC</Strong>. All Rights Reserved.| Developed by <a href="https://www.facebook.com/Arafat1243" target="_blank">Yeasir Arafat</a>
            </v-col>
        </v-footer>
  </v-app>
</template>

<script>
import Auth from '@/auth'
  export default {
    data: () => ({
        dialog: false,
        mini: true,
        drawer: null,
        menu: false,
        role: '',
        items: [],
        notifaction: false
    }),
    props: {
      title: String,
    },
    watch: {
      title: {
        immediate: true,
        handler(title) {
          document.title = title  
        },
      }
    },
    created(){
        this.role = new Auth(this.$page.auth.roles)
        this.init()    
    },
    methods:{
        init(){
            this.items = [
                { title: 'Dashboard', icon: 'mdi-view-dashboard ', path: this.$route('admin'),can: true },
                { title: 'Users', icon: 'mdi-account-group', path: this.$route('users.index'), can: this.role.isAdmin() },
                { title: 'Roles', icon: 'mdi-account-cog',can: this.role.isAdmin(),
                    items:[
                        { title: 'Add Role', path: this.$route('roles.create'),can: this.role.isAdmin()},
                        { title: 'Manage Roles', path: this.$route('roles.index'),can: this.role.isAdmin() },
                    ]
                },
                { title: 'Courses&Categories', icon: 'mdi-school', can: this.role.can([
                        'course_create','course_view','course_update','course_delete',
                        'course_cate_delete','course_cate_view','course_cate_create','course_cate_update'
                    ]),
                    items:[
                        {title: 'Categories', path: this.$route('courseCategories.index'),can:this.role.can([
                            'course_cate_delete','course_cate_view','course_cate_create','course_cate_update'
                    ]), },
                        {title: 'Courses', can: this.role.can(['course_create','course_view','course_update','course_delete',]),
                            items:[
                                {title: 'Add Course', path: this.$route('courses.create'),can: this.role.can('course_create')},
                                {title: 'Manage Courses', path: this.$route('courses.index'),can: this.role.can('course_view')},
                            ]
                        },
                    ]
                },
                { title: 'Services', icon: 'mdi-cog', can: this.role.can(['service_create','service_view','service_update','service_delete']),
                    items:[
                        {title: 'Add Services', path: this.$route('services.create'), can: this.role.can('service_create')},
                        {title: 'Manage Services', path: this.$route('services.index'), can: this.role.can(['service_view','service_update','service_delete'])},
                    ]
                },
                { title: 'Clients&Categories', icon: 'mdi-account-tie', can: this.role.can([
                        'course_create','course_view','course_update','course_delete',
                        'course_cate_delete','course_cate_view','course_cate_create','course_cate_update'
                    ]),
                    items:[
                        {title: 'Categories', path: this.$route('clientCategories.index'),can:this.role.can([
                            'client_cate_delete','client_cate_view','client_cate_create','client_cate_update'
                    ]), },
                        {title: 'Clients', can: this.role.can(['client_create','client_view','client_update','client_delete',]),
                            items:[
                                {title: 'Add Client', path: this.$route('clients.create'),can: this.role.can('client_create')},
                                {title: 'Manage Clients', path: this.$route('clients.index'),can: this.role.can(['client_view','client_update','client_delete'])},
                            ]
                        },
                    ]
                },
                { title: 'Batch', icon: 'mdi-clipboard-list',path: this.$route('gallery.index'), can: this.role.can(['batch_create','batch_view','batch_update','gallery_delete'])
                },
                { title: 'Gallery&Slide', icon: 'mdi-image-multiple ',path: this.$route('gallery.index'), can: this.role.can(['gallery_create','gallery_view','gallery_update','gallery_delete'])
                },
                { title: 'Review', icon: 'mdi-star-box',path: this.$route('review.index'), can: this.role.can(['review_view','review_update','review_delete'])
                },
            ]
        },
        isRoute(name) {
            let url = this.$page.baseUrl+this.$inertia.page.url.slice(1);
            if (name === url+'admin') {
            return url+'/admin' === url;
            }
            return url === name; 
        },
   }
  }
</script>

