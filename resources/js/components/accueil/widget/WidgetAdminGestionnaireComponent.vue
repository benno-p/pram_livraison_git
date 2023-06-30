<template>
	<div class="col-12 col-md-6 mb-4 p-0" id="widgetAdminGestionnaire">
		<div class="card m-2 widget-chart widget-chart2 text-left card-btm-border card-shadow-primary border-primary h-100">
	        <div class="card-body overflow-auto w-100">
	        	<p class="fsize-4 widget-mes-mares-fiches-icone widget-en-tete-header">Administration <i class="fas fa-clipboard-check text-primary"></i></p>

                <template v-if="notifications_mares > 0">
                    <div class="alert alert-warning" role="alert">
                        <b><i class="fas fa-exclamation-triangle"></i> Vous avez {{notifications_mares}} message(s) non lu(s) concernant des mares à valider</b>
                    </div>
                </template>

                <template v-if="notifications_fiches > 0">
                    <div class="alert alert-warning" role="alert">
                        <b><i class="fas fa-exclamation-triangle"></i> Vous avez {{notifications_fiches}} message(s) non lu(s) concernant des fiches à valider</b>
                    </div>
                </template>

	        	<hr class="ml-4 mb-4">
                <div class="d-flex mb-4 justify-content-between widget-mare-fiche-number flex-wrap">
                    <div class="widget-subheading col-10 col-md-4">Mares à valider</div>
                    <div class="col-2 col-md-3"><b>{{mares}}</b></div>
                    <router-link to="/validation_mares" class="btn btn-primary float-right col-12 col-md-5 btn-sm" v-if="mares > 0">
                        <i class="fas fa-eye"></i> Voir les mares à valider
                    </router-link>
                    <button class="btn btn-secondary float-right col-12 col-md-5 btn-sm" disabled="disabled" v-else>
                        <i class="fas fa-eye"></i> Voir les mares à valider
                    </button>
                </div>
                <div class="d-flex mb-4 justify-content-between widget-mare-fiche-number flex-wrap">
                    <div class="widget-subheading col-10 col-md-4">Fiches à valider</div>
                    <div class="col-2 col-md-3"><b>{{fiches}}</b></div>
                    <router-link to="/validation_fiches" class="btn btn-primary float-right col-12 col-md-5 btn-sm" v-if="fiches > 0">
                        <i class="fas fa-eye"></i> Voir les fiches à valider
                    </router-link>
                    <button class="btn btn-secondary float-right col-12 col-md-5 btn-sm" disabled="disabled" v-else>
                        <i class="fas fa-eye"></i> Voir les fiches à valider
                    </button>
                </div>
                <div v-if="$user.role == 'administrateur' || $user.role == 'developpeur'" class="d-flex justify-content-between widget-mare-fiche-number flex-wrap">
                    <div class="widget-subheading col-10 col-md-4">Comptes à valider</div>
                    <div class="col-2 col-md-3"><b>{{comptes}}</b></div>
                    <router-link to="/validation_comptes" class="btn btn-primary float-right col-12 col-md-5 btn-sm" v-if="comptes > 0">
                        <i class="fas fa-eye"></i> Voir les comptes à valider
                    </router-link>
                    <button class="btn btn-secondary float-right col-12 col-md-5 btn-sm" disabled="disabled" v-else>
                        <i class="fas fa-eye"></i> Voir les comptes à valider
                    </button>
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
            mares: '',
            fiches: '',
            comptes: '',
            isLoad: false,
            notifications_mares: 0,
            notifications_fiches: 0,
        }
    },
    mounted(){
        const self = this;
        self.logs = '';
        axios.get(base_url + '/widget_admin_gestionnaire')
        .then(function (resp) {
            self.mares = resp.data.mares;
            self.fiches = resp.data.fiches;
            self.comptes = resp.data.comptes;
            self.notifications_mares = resp.data.notifications_mares;
            self.notifications_fiches = resp.data.notifications_fiches;
        })
        .catch(function (resp) {
            return self.checkError(resp);
        });
    }
}
</script>