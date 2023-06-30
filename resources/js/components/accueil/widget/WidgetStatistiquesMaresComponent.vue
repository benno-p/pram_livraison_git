<template>
    <div class="card mb-4 mt-4">
<!--         <div class="card-header widget-en-tete-header">
            <h5>Statistiques mares</h5>
        </div> -->
        <div class="card-body overflow-auto w-100">
            <div class="">
                <div class="col-12 mb-4 widget-chart d-flex">
                    <span class="dashboard_icones">
                        <i class="fas fa-water text-white bg-cen-orange"></i> 
                    </span>
                    <div class="widget-total-mare ml-4">Nombre total de mares :</div>
                    <div class="widget-numbers m-0 pl-4">{{mares.total}}</div>
                </div>

                <hr class="ml-4 mb-4">

                <div class="d-flex flex-wrap justify-content-between mt-4">
                    <div class="col-12 col-md-6 col-lg-6 col-xl-3 d-flex widget-chart mb-4">
                        <span class="dashboard_icones">
                            <i class="fas fa-check text-white bg-primary"></i> 
                        </span>
                        <div class="widget-chart-content ml-4">
                            <div class="widget-subheading">Mares caractérisées</div>
                            <div class="widget-numbers m-0">{{mares.total_caracterisee}}</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6 col-xl-3 d-flex widget-chart mb-4">
                        <span class="dashboard_icones">
                            <i class="fas fa-eye bg-success text-white"></i> 
                        </span>
                        <div class="widget-chart-content ml-4">
                            <div class="widget-subheading">Mares vues</div>
                            <div class="widget-numbers m-0">{{mares.total_vue}}</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6 col-xl-3 d-flex widget-chart mb-4">
                        <span class="dashboard_icones">
                            <i class="fas fa-question bg-danger text-white"></i> 
                        </span>
                        <div class="widget-chart-content ml-4">
                            <div class="widget-subheading">Mares potentielles</div>
                            <div class="widget-numbers m-0">{{mares.total_potentielle}}</div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6 col-xl-3 d-flex widget-chart mb-4">
                        <span class="dashboard_icones">
                            <i class="fas fa-ban bg-dark text-white"></i>
                        </span>
                        <div class="widget-chart-content ml-4">
                            <div class="widget-subheading">Mares Disparues</div>
                            <div class="widget-numbers m-0">{{mares.total_disparue}}</div>
                        </div>
                    </div>
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
            isLoad: false,
        }
    },
    mounted(){
        const self = this;
        self.logs = '';
        axios.get(base_url + '/widget_statistiques_mares')
        .then(function (resp) {
            self.mares = resp.data.mares;
        })
        .catch(function (resp) {
            return self.checkError(resp);
        });
    }
}
</script>