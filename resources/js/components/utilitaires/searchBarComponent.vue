<template>
    <div class="form-group col-12 col-md-5 mb-4 pl-0">
        <div class="input-group p-0">
            <input type="text" class="form-control" v-model="searchBar" :placeholder="placeholder">
            <div class="input-group-append">
                <button class="btn btn-primary" disabled="disabled">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="input-group-append">
                <button class="btn btn-danger" @click="clearSearchBar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            dataProps: Array,
            allDataProps: Array,
            columnsProps: Array,
            placeholderProps: String,
        },
        data: function(){
            return {
                searchBar: '',
                data: this.dataProps,
                all_data: this.allDataProps,
                columns: this.columnsProps,
                placeholder: this.placeholderProps,
            }
        },
        watch: {
            searchBarProps: function(val){
                this.searchBar = val;
            },
            dataProps: function(val){
                this.data = val;
            },
            allDataProps: function(val){
                this.all_data = val;
            },
            searchBar: function(){
                this.search();
            },
        },
        methods: {
            checkIfSearchBar: function(self, data, all_data, columns){
                let merged = [];   
                if(self.searchBar != ''){
                    let all_result = [];
                    columns.forEach(function(element){
                        if(element != false){
                            let result = [];
                            let el = '';
                            result = data.filter(function(item){
                                if(item[element] != undefined && typeof item[element] != 'string'){
                                    el = item[element].toString();
                                    // console.log(element)
                                }else{
                                    el = item[element];
                                }
                                if(el != undefined) return el.toLowerCase().includes(self.searchBar); 
                            })
                            all_result.push(result);
                        }
                    })
                    merged = [].concat.apply([], all_result);
                    let obj = {};
                    for ( let i=0, len=merged.length; i < len; i++ ) obj[merged[i]['id']] = merged[i];
                    merged = new Array();
                    for ( let key in obj ) merged.push(obj[key]); 
                }else{
                    merged = all_data;
                }
                return merged;
            },
            search: function(event){
                let self = this;
                self.data = self.all_data;
                // self.searchBar = event.toLowerCase();
                let merged = this.checkIfSearchBar(self, self.data, self.all_data, self.columns);
                /*
                 * If select filter is active 
                 */
                let results = merged.filter(function(item){
                    for(var key in self.filter) {
                        if(item[key.toLowerCase()] === undefined || item[key.toLowerCase()] != self.filter[key.toLowerCase()]) return false;
                    }
                    return true;
                });
                // this.membres = results;
                // this.page = 1;
                this.$emit('searchBarResults', results);

            },
            clearSearchBar: function(){
                this.searchBar = '';
            },
        }
    }
</script>