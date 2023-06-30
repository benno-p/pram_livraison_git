<template>
	<div>
		<nav aria-label="Page navigation example" v-if="pages.length > 1" :style="styleProps">
	        <ul class="pagination justify-content-center">
	            <li class="page-item" v-if="page != 1">
	                <a class="page-link"  v-on:click.prevent="page--" href="#"><i class="fa fa-angle-left"></i></a>
	            </li>
	            <li class="page-item disabled" v-else>
	                <button class="page-link" disabled="disabled"><i class="fa fa-angle-left"></i></button>
	            </li>
	            <li v-if="page != 1 && page > 5" class="page-item">
	                <a class="page-link page-scroll" v-on:click.prevent="page = 1" href="#">1</a>
	            </li>
	            <li v-if="page != 1 && page > 5" aria-disabled="true" class="page-item">
	                <a class="page-link page-scroll" href="javascript:">...</a>
	            </li>
	            <template v-for="pageNumber in paginationPage()">
	                <li :class="page == pageNumber ? 'page-item active' : 'page-item'">
	                    <a class="page-link page-scroll" v-on:click.prevent="page = pageNumber" href="#">{{pageNumber}}</a>
	                </li>
	            </template>
	            <li v-if="page != numberOfPages && page < numberOfPages-5" aria-disabled="true" class="page-item">
	                <a class="page-link page-scroll" href="javascript:">...</a>
	            </li>
	            <li v-if="page != numberOfPages && page < numberOfPages-5" class="page-item">
	                <a class="page-link page-scroll" v-on:click.prevent="page = numberOfPages" href="#">{{numberOfPages}}</a>
	            </li>
	            <li class="page-item" v-if="page < pages.length">
	                <a class="page-link" v-on:click.prevent="page++" href="#"><i class="fa fa-angle-right"></i></a>
	            </li>
	            <li class="page-item disabled" v-else>
	                <button class="page-link" disabled="disabled"><i class="fa fa-angle-right"></i></button>
	            </li>
	        </ul>
	    </nav>
	</div>
</template>


<script>
	export default {
		props: {
			pageProps: 0,
			perPageProps: 0,
			styleProps: false,
		},
        data: function(){
            return {
            	pages: [],
            	page: this.pageProps,
            	numberOfPages : 0,
            	perPage: this.perPageProps != 0 ? this.perPageProps : 10, 
            }
        },
        watch: {
            pagesProps: function(val) {
                this.pages = val
            },
            pageProps: function(val) {
                this.page = val
            },
            perPageProps: function(val){
            	this.perPage = val;
            },
            page: function(val){
            	this.$emit('page', val);
            },
        },
        methods: {
        	paginationPage: function(){
                let before = 1;
                if(this.page <= 5) before = this.page - this.page; 
                else before = this.page-5;
                return this.pages.slice(before, this.page+4);
            },
            setPages: function(event, data) {
                this.pages = [];
                let numberOfPages = Math.ceil(data.length / this.perPage);
                for (let index = 1; index <= numberOfPages; index++) {
                    this.pages.push(index);
                }
                this.numberOfPages = numberOfPages;
            },
        }
    }
</script>