<template>
  <v-footer dark padless class="white-dark-2 grey--text">
    <div style="width: 100%">
      <v-container>
        <v-row >
          <v-col cols="12" sm="6" md="4">
            <div class="white--text text-h6 white--text my-3">
              <v-img height="50" width="50" class="float-left" :src="brandImg"></v-img>
              Universal Technology Corporation
            </div>
            <div class="subtitle-2 text-capitalize">207, Doctor Para, Feni, Bangladesh</div>
            <ul class="footer-list mt-2">
              <li><v-icon small class="mr-2 primary--text">mdi-phone</v-icon>+880 1812-669757</li>
              <li><v-icon small class="mr-2 primary--text">mdi-email-outline</v-icon>supporte@utcworld.net</li>
              <li><v-icon small class="mr-2 primary--text">mdi-earth</v-icon>www.utcworld.net</li>
            </ul>
            <ul class="footer-list mt-2 d-flex">
              <li v-for="(icon,i) in icons" :key="i" class="mr-2">
                <v-btn text link target="_blank" :href="icon.path" icon outlined>
                  <v-icon color="grey">{{icon.icon}}</v-icon>
                </v-btn>
              </li>
            </ul>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <div class="text-h5 white--text mt-4">Usefull Links</div>
            <div class="double-line-bottom-theme-colored-2"></div>
            <ul class="footer-list has-link">
              <li> <inertia-link class="grey--text" :href="$route('home')">Home Page</inertia-link></li>
              <li> <inertia-link class="grey--text" :href="$route('public.about')">About US</inertia-link></li>
              <li> <inertia-link class="grey--text" :href="$route('home')">Contact Us</inertia-link></li>
              <li> <a href="/" class="grey--text">Terms and Conditions</a></li>
            </ul>
          </v-col>
          <v-col cols="12" sm="6" md="4">
            <div class="fb-page mt-3" data-href="https://www.facebook.com/UTCWorldSC/" data-tabs="" data-width="400" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/UTCWorldSC/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/UTCWorldSC/">UTC</a></blockquote></div>
          </v-col>
        </v-row>
      </v-container>
      <v-divider></v-divider>
      <div class="text-center text-body py-5">Copyright ©{{new Date().getFullYear()}} <Strong>UTC</Strong>. All Rights Reserved.| Developed by <a href="https://www.facebook.com/Arafat1243" target="_blank">Yeasir Arafat</a></div>
    </div>
  </v-footer>
</template>

<script>
  export default {
    data: () => ({
      brandImg:'storage/images/logo/WhiteLogo.png',
      icons: [
        {path: 'https://www.facebook.com/groups/666854090312341/', icon: 'mdi-facebook'},
        {path: 'https://www.facebook.com/groups/666854090312341/', icon: 'mdi-twitter'},
        {path: 'https://www.facebook.com/groups/666854090312341/', icon: 'mdi-linkedin'},
        {path: 'https://www.facebook.com/groups/666854090312341/', icon: 'mdi-instagram'}
      ]
    }),
    mounted(){
      this.brandImg = this.$page.baseUrl+this.brandImg;
    }
  }
</script>
<style lang="scss" scoped>
  .footer-list{
    list-style: none;
    text-align: start !important;
    margin-left: -25px;
    li{
      padding: 2px;
    }
  }
  .footer-list.has-link{
        li{
          padding: 5px 0;
          border-bottom:1px dotted rgb(87, 87, 87);
          display: flex;
          justify-content: space-between;
        a{
          text-decoration: none;
          &:hover{
            color: rgb(109, 107, 107) !important;
          }
        }
        samp.close{
          background: #5d9cec;
          color: #fff !important;
          padding: 0 5px;
          border: none;
          border-radius: 5px;
        }
      }
  }
</style>