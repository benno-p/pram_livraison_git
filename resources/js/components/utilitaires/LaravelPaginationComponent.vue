<template>
	<div class="mt-4">
		<nav aria-label="Page navigation example" v-if="pages.length > 1" :style="styleProps">
	        <ul class="pagination justify-content-center">
	            <li class="page-item" v-if="page != 1">
	                <a class="page-link"  v-on:click.prevent="page--" href="#">
	                	<span><i class="far fa-arrow-alt-circle-left"></i></span>
	                </a>
	            </li>
	            <li class="page-item disabled" v-else>
	                <button class="page-link" disabled="disabled">
	                	<span><i class="far fa-arrow-alt-circle-left"></i></span>
	                </button>
	            </li>
	            <li v-if="page != 1 && page > 5" class="page-item">
	                <a class="page-link page-scroll" v-on:click.prevent="page = 1" href="#">1</a>
	            </li>
	            <li v-if="page != 1 && page > 5 && light_pagination == false " aria-disabled="true" class="page-item">
	                <a class="page-link page-scroll" href="javascript:">...</a>
	            </li>
	            <template v-for="pageNumber in paginationPage()">
	                <li :class="page == pageNumber ? 'page-item active' : 'page-item'">
	                    <a class="page-link page-scroll" v-on:click.prevent="page = pageNumber" href="#">{{pageNumber}}</a>
	                </li>
	            </template>
	            <li v-if="page != numberOfPages && page < numberOfPages-5 && light_pagination == false" aria-disabled="true" class="page-item">
	                <a class="page-link page-scroll" href="javascript:">...</a>
	            </li>
	            <li v-if="page != numberOfPages && page < numberOfPages-5 " class="page-item">
	                <a class="page-link page-scroll" v-on:click.prevent="page = numberOfPages" href="#">{{numberOfPages}}</a>
	            </li>
	            <li class="page-item" v-if="page < pages.length">
	                <a class="page-link" v-on:click.prevent="page++" href="#">
	                	<span><i class="far fa-arrow-alt-circle-right"></i></span>
	                </a>
	            </li>
	            <li class="page-item disabled" v-else>
	                <button class="page-link" disabled="disabled">
	                	<span><i class="far fa-arrow-alt-circle-right"></i></span>
	                </button>
	            </li>
	        </ul>
	    </nav>
	</div>
</template>


<script>
	export default {
		props: {
			pageProps: 0,
			pagesProps: 0,
			perPageProps: 0,
			styleProps: false,
			lightPaginationProps: Boolean,
		},
        data: function(){
            return {
            	pages: [],
            	page: this.pageProps,
            	numberOfPages : 0,
            	perPage: this.perPageProps != 0 ? this.perPageProps : 10,
            	light_pagination: this.lightPaginationProps,
            	pageBeforeAfter: this.lightPaginationProps === false ? 5 : 2,
            }
        },
        watch: {
        	pagesProps: function(val) {
                if(this.pages != val){
                	this.setPages();
                }
            },
            pageProps: function(val) {
                this.page = val
            },
            perPageProps: function(val){
            	this.perPage = val;
            },
            page: function(val){
            	this.$emit('changePage', val);
            },
        },
        methods: {
        	setPages: function(){
	        	this.pages = [];
                for (let index = 1; index <= this.pagesProps; index++) {
                    this.pages.push(index);
                }
                this.numberOfPages = this.pagesProps;

        	},
        	paginationPage: function(){
                let before = 1;
                if(this.page <= this.pageBeforeAfter) before = this.page - this.page; 
                else before = this.page - this.pageBeforeAfter;
                return this.pages.slice(before, this.page + (this.pageBeforeAfter - 1));
            },
        },
        mounted(){
        	this.setPages();
        }
    }
</script>