<template>
	<div class="col-12 col-md-3 mb-4 p-0" id="widgetMesMares">
		<div class="card m-2 widget-chart widget-chart2 text-left card-btm-border card-shadow-success border-success h-100">
	        <div class="card-body overflow-auto w-100">
	        	<p class="fsize-4 widget-mes-mares-fiches-icone widget-en-tete-header">Mes mares <i class="fas fa-tint text-success"></i></p>

                <template v-if="notifications > 0">
                    <div class="alert alert-warning" role="alert">
                        <b><i class="fas fa-exclamation-triangle"></i> Vous avez {{notifications}} message(s) non lu(s)</b>
                    </div>
                </template>

	        	<hr class="ml-4 mb-4">
                <div class="d-flex mb-4 justify-content-between col-11 widget-mare-fiche-number">
                    <div class="widget-subheading">Mares saisies</div>
                    <div class="widget-numbers m-0">{{mares.total ? mares.total : '0'}}</div>
                </div>
                <div class="d-flex justify-content-between col-12 widget-mare-fiche-number">
                    <div class="widget-subheading">Mes mares en attentes</div>
                    <div class="widget-numbers m-0">{{mares.total_en_attente ? mares.total_en_attente : '0'}}</div>
                </div>
                <router-link to="/mes_mares" class="btn btn-success float-right mt-4 btn-sm">
                    <i class="fas fa-eye"></i> Voir mes mares
                </router-link>
<!--                 <router-link to="/mes_mares" class="btn btn-success float-right mt-4 btn-sm" v-if="mares.total_en_attente && mares.total_en_attente > 0">
                    <i class="fas fa-eye"></i> Voir mes mares
                </router-link>
                <button class="btn btn-secondary float-right mt-4 btn-sm" disabled="disabled" v-else>
                    <i class="fas fa-eye"></i> Voir mes mares
                </button> -->
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
            isLoad: false,
            notifications: 0,
        }
    },
    mounted(){
        const self = this;
        self.logs = '';
        axios.get(base_url + '/widget_statistiques_mes_mares')
        .then(function (resp) {
            self.mares = resp.data.mares;
            self.notifications = resp.data.notifications;
        })
        .catch(function (resp) {
            return self.checkError(resp);
        });
    }
}
</script>