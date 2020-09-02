
<template>
  <v-app id="inspire">
    <v-navigation-drawer v-model="drawer" :clipped="$vuetify.breakpoint.lgAndUp" app
      :mini-variant.sync="mini" permanent v-if="!nav"
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
                                <inertia-link v-if="item.can" :class="isRoute(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light primary--text' : 'v-list-item v-list-item--link theme--light'" :href="item.path" role="menuitem"
                                        >
                                        <div class="v-list-item__icon">
                                        </div>
                                        <div class="v-list-item__content">
                                            <div class="v-list-item__title nav-deawer-item-title">{{ item.title }}</div>
                                        </div>
                                </inertia-link>
                            </div>   
                        </v-list-group>
                    </div>
                </v-list>
    </v-navigation-drawer>

    <v-app-bar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
    >
      <v-app-bar-nav-icon v-if="!nav" @click.stop="mini = !mini"></v-app-bar-nav-icon>
      <v-toolbar-title class="ml-0 pl-4">
        <inertia-link :href="$route('student')" role="menuitem">
            <span >Universal Technology Corporation</span>
        </inertia-link>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      
      <v-menu v-model="menu" bottom right transition="scale-transition" origin="top right">
            <template v-slot:activator="{ on }">
               <v-btn icon large v-on="on">
                                <v-avatar size="50px" item>
                                    <v-img :src="$page.auth.avatar"></v-img>
                                </v-avatar>
                            </v-btn>
                        </template>
                        <v-card width="300">
                        <v-list>
                            <v-list-item>
                            <v-list-item-avatar size="80px">
                                <v-img :src="$page.auth.avatar"></v-img>
                            </v-list-item-avatar>
                            <v-list-item-content>
                                <v-list-item-title>{{$page.auth.name}}</v-list-item-title>
                                <v-list-item-subtitle>{{$page.auth.email}}</v-list-item-subtitle>
                            </v-list-item-content>
                            <v-list-item-action>
                                <v-btn icon @click="menu = false">
                                <v-icon>mdi-close-circle</v-icon>
                                </v-btn>
                            </v-list-item-action>
                            </v-list-item>
                        </v-list>
                        <v-list>
                            <inertia-link class="v-list-item v-list-item--link theme--light primary--text" :href="$route('student.profile')" role="menuitem">
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
    data: vm => ({
        dialog: false,
        mini: true,
        drawer: null,
        menu: false,
        role: new Auth(vm.$page.auth.roles),
        items: [],
    }),
    props: {
      title: String,
      nav: Boolean
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
        this.init()    
    },
    methods:{
        init(){
            this.items = [
                { title: 'Dashboard', icon: 'mdi-view-dashboard', path: this.$route('student'),can: true },
                { title: 'Outher Courses', icon: 'mdi-school', path: this.$route('student.batch'),can: true },
            ]
        },
        isRoute(name) {
            let url = this.$page.baseUrl+this.$inertia.page.url.slice(1);
            if (name === url+'student') {
            return url+'/student' === url;
            }
            return url === name; 
        },
   }
  }
</script>

