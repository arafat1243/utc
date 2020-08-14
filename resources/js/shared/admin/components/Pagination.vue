<template>
    <div class="text-center" v-if="showPagination">
        <div class="text-start grey--text">
            {{links.from}}-{{links.to}} of {{links.total}}
        </div>
        <nav role="navigation" aria-label="Pagination Navigation" >
            <ul class="v-pagination v-pagination--circle thead-light">
                <li>
                    <inertia-link aria-label="previous page" :href="links.prev_page_url ? links.prev_page_url  : '#'" :class="links.prev_page_url ? 'pagination__navigation' : 'pagination__navigation v-pagination__navigation--disabled'"><v-icon class="notranslate">mdi-chevron-left</v-icon></inertia-link>
                </li>
                <li v-for="(link, key) in this.links.last_page" :key="key">
                    <inertia-link :aria-label="links.current_page == (key+1) ? `current page,goto page ${(key+1)}` : `goto page ${(key+1)}`" :href="links.path+'?page='+(key+1)" :class="links.current_page == (key+1) ? 'v-pagination__item v-pagination__item--active primary white--text' : 'v-pagination__item'">{{(key+1)}}</inertia-link>
                </li>
                <li>
                     <inertia-link aria-label="Next page" :href="links.next_page_url ? links.next_page_url  : '#'" :class="links.next_page_url ? 'pagination__navigation' : 'pagination__navigation v-pagination__navigation--disabled'"><v-icon class="notranslate">mdi-chevron-right</v-icon></inertia-link>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
export default {
    props: {
            links: Object,
        },
    computed: {
        showPagination() {
            return this.links.total > this.links.per_page
        }
    },
}
</script>
<style lang="scss" scoped>
.v-pagination__item{
    text-align: center !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}
.pagination__navigation{
    text-decoration: none !important;
}
</style>