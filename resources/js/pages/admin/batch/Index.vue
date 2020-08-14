<template>
    <Layout title="Batch">
        <!-- <Pagination class="mt-4" :links="this.batches"/> -->
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
// import Pagination from '@/shared/admin/Pagination'
export default {
    data: ()=>({
        onlyBatches: []
    }),
    props:['batches'],
    components:{Layout}
}
</script>