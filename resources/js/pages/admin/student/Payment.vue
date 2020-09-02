<template>
    <Layout title="Payment History">
      <v-data-table
        :headers="headers"
        :items="items"
        hide-default-footer
        multi-sort
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat color="white">
            <v-toolbar-title>Payment History</v-toolbar-title>
            <v-divider
              class="mx-4"
              inset
              vertical
            ></v-divider>
              <v-menu v-model="menu1" :close-on-content-click="true"
                transition="scale-transition" offset-y min-width="290px">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field class="mt-10" v-model="form" label="From Date"
                    readonly outlined v-bind="attrs" v-on="on" clearable @click:clear="()=>{items = payments.data;form = ''}"></v-text-field>
                </template>
                <v-date-picker v-model="form"></v-date-picker>
              </v-menu>
              <v-menu v-model="menu2" :close-on-content-click="true"
                transition="scale-transition" offset-y min-width="290px">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field class="mt-10 ml-5" v-model="to" label="To Date"
                    readonly outlined v-bind="attrs" v-on="on" clearable @click:clear="()=>{items = payments.data;to = ''}"></v-text-field>
                </template>
                <v-date-picker :min="form" v-model="to"></v-date-picker>
              </v-menu>
              <v-spacer></v-spacer>
              <v-btn color="info" outlined @click="filterData">Filter</v-btn>
          </v-toolbar>
        </template>
        <template v-slot:item.status="{ item }">
          <v-chip :color="item.status ? 'success' : 'error'">{{item.status ? 'Approved' : 'Unapproved'}}</v-chip>
        </template>
        <template v-slot:item.amount="{ item }">
          <span>{{item.amount}} tk</span>
        </template>
        <template v-slot:body.append>
          <tr>
            <td colspan="4" class="title">Total</td>
            <td colspan="2" class="title">{{total}} tk</td>
          </tr>
        </template>
      </v-data-table>
      <Pagination class="mt-4" :links="payments"/>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Pagination from '@/shared/admin/components/Pagination'
export default {
  data: vm=>({
    menu1: false,
    menu2: false,
    form: '',
    to: '',
    items: vm.payments.data,
    headers: [
      { text: '#', align: 'start', sortable: false,value: 'serial'},
      { text: 'Course Name',align: 'start',  value: 'course_name' },
      { text: 'Payment At', value: 'payment_at' },
      { text: 'Status', value: 'status' },
      { text: 'Amount', value: 'amount' },
    ],
  }),
  props:['payments'],
  computed:{
    total(){
      let total = 0;
      this.items.forEach( a =>{
        total += a.amount
      })
      return total
    }
  },
  methods:{
    filterData(){
      const formDate = new Date(this.form),
        toDate = new Date(this.to);
      let items = this.items;
      this.items = items.filter((a) => {
        var date = new Date(a.payment_at.split('-').reverse().join('-'));
          return (date >= formDate && date <= toDate);
      });
    },
  },
  components:{
    Layout,Pagination
  }
}
</script>