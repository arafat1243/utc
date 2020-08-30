<template>
   <div class="navbar">
       <v-app-bar app elevation='3'>
          <v-app-bar-nav-icon @click="deawer = !deawer" v-show="$vuetify.breakpoint.smAndDown"></v-app-bar-nav-icon>
         <v-toolbar-title>
            <div class="d-flex justify-start wrap" v-if="$vuetify.breakpoint.lgAndUp && brandImg">
               <v-img :src="brandImg"   height="100" width="100"></v-img>
               <div class="mt-6">
                  <div class="brand-text ml--3" style="font-size: 1.3em !important">Universal Technology Corporation</div>
                  <div class="subtitle-1 primary--text ml--3 mt--2">Futuristic Technology...</div>
               </div>
            </div>
            <inertia-link href="/" class="brand-text v-btn v-btn--flat v-btn--router v-btn--text theme--light v-size--default" v-else :style="$vuetify.breakpoint.smAndDown ? 'margin-left: -20px !important;' : ''">{{brandText}}</inertia-link>
         </v-toolbar-title>
         <v-spacer></v-spacer>
            <v-toolbar-items  :class="$vuetify.breakpoint.mdAndUp? 'mr-8 align-center' : 'align-center'">
               <div v-for="(item,i) in $page.menu" :key="i" style="height: 100%;" v-show="$vuetify.breakpoint.mdAndUp">
                   <inertia-link v-if="!item.items" :href="item.path"  :class="isRoute(item.path) ? 'nav-item v-btn v-btn--active v-btn--flat v-btn--router v-btn--text theme--light v-size--default primary--text' : 'nav-item v-btn v-btn--flat v-btn--router v-btn--text theme--light v-size--default primary--text'">
                       <span class="v-btn__content">{{item.text}}</span>
                   </inertia-link>
                  <v-menu v-else offset-y open-on-hover>
                     <template v-slot:activator="{ on, attrs }">
                         <inertia-link  :href="item.path"
                         :class="isRoute(item.path) ? 'nav-item v-btn v-btn--active v-btn--flat v-btn--router v-btn--text theme--light v-size--default primary--text' : 'nav-item v-btn v-btn--flat v-btn--router v-btn--text theme--light v-size--default primary--text'" v-bind="attrs" v-on="on" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="v-btn__content">{{item.text}}</span>
                        </inertia-link>
                     </template>
                     <v-list>
                        <div v-for="(item, i) in item.items" :key="i">
                           <a :href="item.path" v-if="item.terget" target="_blank" rel="noopener noreferrer"
                           :class="isRoute(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light ' : 'v-list-item v-list-item--link theme--light'" role="menuitem">
                           <div  class="v-list-item__title primary--text">{{ item.text }}</div>
                           </a>
                           <inertia-link  :href="item.path" v-else :class="isRoute(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light ' : 'v-list-item v-list-item--link theme--light'" role="menuitem"
                           >
                            <div  class="v-list-item__title primary--text">{{ item.text }}</div>
                         </inertia-link>
                        </div>    
                     </v-list>
                  </v-menu>
               </div>
               <div v-show="$vuetify.breakpoint.smAndDown" class="nav-sm-item">
                  <v-icon color="primary">mdi-phone</v-icon>
                  <samp>+88014465633</samp>
               </div>
               <div v-show="$vuetify.breakpoint.smAndDown" class="nav-sm-item">
                  <v-icon color="primary">mdi-email</v-icon>
                  <samp>info@utc.net</samp>
               </div>
            </v-toolbar-items>
       </v-app-bar>
       <v-navigation-drawer app v-model="deawer" temporary v-show="$vuetify.breakpoint.smAndDown">
          <v-list
            dense
            nav
            class="py-0"
         >
            <v-list-item two-line link href="/">
               <v-list-item-content align="center">
                  <v-img :src="brandImg" v-if="brandImg" height="100" width="100"></v-img>
                  <v-list-item-title v-else class="brand-text">{{brandText}}</v-list-item-title>
               </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <div v-for="(item,i) in $page.menu"
                  :key="i" class="nav-deawer-item">
                <inertia-link v-if="!item.items" :href="item.path" :class="isRoute(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light' : 'v-list-item v-list-item--link theme--light'"
                    >
                    <div class="v-list-item__icon">
                        <v-icon class="nav-deawer-item-icon">{{ item.icon }}</v-icon>
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title nav-deawer-item-title">{{ item.text }}</div>
                    </div>
                </inertia-link>
               <v-list-group v-else>
                  <v-icon slot="prependIcon">{{item.icon}}</v-icon>
                  <template v-slot:activator>
                     <v-list-item-content>
                        <v-list-item-title class="nav-deawer-item-title">{{ item.text }}</v-list-item-title>
                     </v-list-item-content>
                  </template>
                  <inertia-link v-for="(item, i) in item.items" :key="i" :class="isRoute(item.path) ? 'v-list-item v-list-item--active v-list-item--link theme--light' : 'v-list-item v-list-item--link theme--light'" :href="item.path" role="menuitem"
                    >
                    <div class="v-list-item__icon">
                    </div>
                    <div class="v-list-item__content">
                        <div class="v-list-item__title nav-deawer-item-title">{{ item.text }}</div>
                    </div>
                </inertia-link>
                  
               </v-list-group>
            </div>
         </v-list>
       </v-navigation-drawer>
   </div>
</template>
<script>
export default {
   data: () => ({
     brandImg: 'storage/images/logo/logo.png',
     brandText: 'UTC',
     deawer: false,
   }),
   mounted(){
      this.brandImg = this.$page.baseUrl+this.brandImg;
   },
   methods:{
      isRoute(name) {
        if (name === '/') {
          return '/' === this.$inertia.page.url;
        }
        return this.$inertia.page.url.match(name);
      },
   }
}
</script>
<style lang="scss" scoped>
    .navbar{
       .ml--3{
          margin-left: -20px !important;
       }
       .mt--2{
          margin-top: -15px !important;
       }
         .nav-item{
            font-weight: 600 !important;
            border: none;
            border-radius: 30px !important;
            top: 13px;
            &::before{
               color: #5d9cec !important;
            }
            &:hover{
               color: #fff !important;
               &::before{
               color: #5d9cec !important;
               opacity: 1 !important;
            }
            }
         }
         .nav-item.v-btn--active{
             color: #fff !important;
         }
         .theme--light.nav-item.v-btn--active{
            color: #ffff !important;
            &::before{
               color: #5d9cec !important;
               opacity: 1;
            }
         }
         .nav-sm-item{
            color: #5d9cec;
            font-size: 1em;
            &:not(:nth-child(1)){
               margin-left: 10px;
            }
         }
         .nav-deawer-item{
            .nav-deawer-item-icon,.nav-deawer-item-title,.v-list-item__icon.v-list-group__header__prepend-icon .v-icon{
               color: #5d9cec !important;
               font-weight: 600 !important;
            }
         }
         .brand-text.v-btn--router{
            padding: 0;
            &::before{
               color: transparent;
            }
         }
    }
</style>