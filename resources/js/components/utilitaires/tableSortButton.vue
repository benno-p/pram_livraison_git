<template>
	<a href="javascript:" v-on:click="checkSort(key)">
        {{name}} <i v-bind:class="sortOrder(key, currentSortDir)"></i>
    </a>
</template>


<script>
	export default {
		props:{
			keyProps: false,
			nameProps: false,
			currentSortDirProps : false,
			currentSortProps: false,
		},
        data: function(){
            return {
            	key : this.keyProps ? this.keyProps : '',
            	name: this.nameProps ? this.nameProps : '',
            	currentSortDir: this.currentSortDirProps ? this.currentSortDirProps : 'asc',
            	currentSort : this.currentSortProps ? this.currentSortProps : '',
            }
        },
        watch: {
        	currentSortDirProps: function(val){
        		this.currentSortDir = val;
        	},
        	keyProps: function(val){
        		this.key = val;
        	},
        	nameProps: function(val){
        		this.name = val;
        	},
        	currentSortProps: function(val){
                this.currentSort = val;
            },
        },
        methods: {
        	sortOrder: function(tri, ordre){
                if(this.currentSort == tri && this.currentSortDir == 'asc') return 'fas fa-caret-up';
                if(this.currentSort == tri && this.currentSortDir == 'desc') return 'fas fa-caret-down';
            },
            checkSort: function(tri){
                this.currentSortDir = this.currentSortDir === 'asc'?'desc':'asc';
                this.currentSort = tri;
                this.$emit('currentSortDir', this.currentSortDir);
                this.$emit('currentSort', tri);
            },
        }
    }
</script>


