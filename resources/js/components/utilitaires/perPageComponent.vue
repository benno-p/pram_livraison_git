<template>
	<div>
        <select class="form-control" v-on:change="changeNumberPerPage($event)" v-model="perPage">
            <option v-for="nb in nbPerPage" :value="nb" :key="'nbPerPage' + nb">
                {{nb}}
            </option>
        </select>
    </div>
</template>


<script>
	export default {
        data: function(){
            return {
            	nbPerPage: [10,20,30],
            	perPage: 10,
            }
        },
        watch: {

        },
        methods: {
        	changeNumberPerPage: function(event){
        		this.$emit('setPages', this.perPage);
        	},
        }
    }
</script>