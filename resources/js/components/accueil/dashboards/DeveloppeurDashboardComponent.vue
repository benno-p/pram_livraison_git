<template>
    <div>
        <widget-statistiques-mares></widget-statistiques-mares>
        <widget-bouton-accueil></widget-bouton-accueil>
        <div class="d-flex flex-wrap">
            <widget-statistiques-mes-mares></widget-statistiques-mes-mares>
            <widget-statistiques-mes-fiches></widget-statistiques-mes-fiches>
            <widget-admin-gestionnaire></widget-admin-gestionnaire>
        </div>
    	<div v-if="isLoad">
            <div class="card mb-4 mt-4">
                <div class="card-header bg-dark text-white">
                    <h4>Mémo pour Dev</h4>
                </div>
                <div class="card-body overflow-auto list_log w-100">
                    <ul>
                        <li>Dans fiche : rajouter avant types de mares, un select avec "types de données" [données mares ou erreur d'interprétation] ==> si erreur interorétation, tout griser sauf date obs + photo + ajouter un flag dans la validation</li>
                        <li>Supprimer message quand mare validée</li>
                        <li>Ajouter un bouton enregistrer (juste pour la mare pour les personnes qui ont le droit sur cette mare)</li>
                        <li>Ajouter recherche par ID pour gestionnaire, comme administrateur</li>

                        <li>Créer automatiquement un observateur a la creation du compte</li>
                        <li>Rajouter possibilité de caracteriser fiche a la creation si gestionnaire ou admin + reporter caracterisation sur fiche</li>
                        <li>lié l'obervateur à la création d'un compte user</li>
                        <li>Page export</li>
                        <li>Acces fiche que si mare dans le groupe</li>
                        <li>mail à la journée</li>
                    </ul>
                </div>
            </div>

    		<div class="card mb-4 mt-4" v-for="log in logs">
    	        <div class="card-header bg-dark text-white">
    	            <button class="btn btn-danger float-right" @click="deleteLog(log.nom_original)">
    	                <i class="fas fa-trash-alt"></i>
    	            </button>
    	            <h4>Log du {{convertDate(log.nom)}}</h4>
    	        </div>
    	        <div class="card-body overflow-auto list_log w-100">
    	            <table class="table table-striped">
    	                <thead>
    	                    <tr>
    	                        <th style="width:20%">Date</th>
    	                        <th>Contenu</th>
    	                    </tr>
    	                </thead>
    	                <tbody>
    	                    <tr v-for="data in log.data">
    	                        <td>{{convertDateHeure(data.date)}}</td>
    	                        <td>{{data.contenu}}</td>
    	                    </tr>
    	                </tbody>
    	            </table>
    	        </div>
    	    </div>
    	</div>
    </div>
</template>


<script>
export default {
    data: function(){
        return {
            errors: [],
            base_url: base_url,
            pages: [],
            page: 1,
            perPage: 10,
            logs: [],
            isLoad: false,
        }
    },
    computed: {
        computedHeight: function () {
            if(this.$user.authenticated == true){
                this.height = '89vh';
            }
            return this.height;
        }
    },
    methods: {
        detectMobile: function(event){
            this.isMobile = event;
        },
        checkPage: function(event){
            this.page = event;
        },
        checkCurrentSortDir: function(event){
            this.currentSortDir = event;
        },
        checkCurrentSort: function(event){
            this.currentSort = event;
        },
        checkPages: function(event){
            this.page = 1;
            this.perPage = event;
        },
        detectMobile: function(event){
            this.isMobile = event;
        },
        searchBarResults: function(event){
            this.page = 1;
            this.logs = event;
        },
        sort: function(data) {
            let currentSort = this.currentSort;
            let currentSortDir = this.currentSortDir;
            let self = this;
            data.sort(function(a, b) { 
                let modifier = 1;
                if(self.currentSortDir === 'desc') modifier = -1;
                return a[currentSort]> b[currentSort] ? 1*modifier : -1*modifier;
            });
            let from = (this.page * this.perPage) - this.perPage;
            let to = (this.page * this.perPage);
            return data.slice(from, to);
        },
        loadLogs: function(){
            const self = this;
            self.logs = '';
            axios.get(base_url + '/dashboard/logs')
            .then(function (resp) {
                self.logs = resp.data.logs;
                self.isLoad = true;
            })
            .catch(function (resp) {
                return self.checkError(resp);
            });
        },
        deleteLog: function(fichier){
            let self = this;
            let formData = new FormData();
            formData.append('fichier', fichier);

            axios.post(base_url + '/dashboard/delete_log', formData, {
                _method: 'POST',
            })
            .then(function (response) {
                if(response.data.error == true){
                    var values = Object.keys(response.data.message).map(function(e) {
                        return self.err = response.data.message[e];
                    });
                    return false;
                }else{
                    return self.loadLogs();
                }
            })
            .catch(function (error) { console.log(error); });
        }
    },
    mounted(){
        if(this.$user.authenticated && this.$user.role == 'developpeur'){
            this.loadLogs();  
        }
    }
}
</script>