<aside class="p-0 my-vh100 shadow-sm" id="sidebar" style="width: 250px;">
    <nav class="navbar navbar-expand navbar-dark bg-white flex-column align-items-start w-100 p-0">
        <div id="sidebarMenu" class="flex-column navbar-nav justify-content-between">
            <div class="clearfix flex-row w-100 mb-3">
                <h2 id="maintitle" class="p-4 mt-1 mb-1">
                    <router-link to="/" onclick="changeMenu()">
                        <img src="{{asset('images/logo/logo.png')}}" />
                    </router-link>
                </h2>
            </div>
            <div class="collapse navbar-collapse w-100">
                <ul id="sidebar_ul">
                    <li>
                        <router-link to="/" onclick="changeMenu()">
                            <i class="fas fa-home"></i> Accueil
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/carte" onclick="changeMenu()">
                            <i class="fas fa-map-marked-alt"></i> Carte
                        </router-link>
                    </li>

                    @if(!Auth::check())
                    <li>
                        <router-link to="/connexion" onclick="changeMenu()">
                            <i class="fas fa-unlock-alt"></i> Connexion
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/demande_compte" onclick="changeMenu()">
                            <i class="fas fa-sign-in-alt"></i> Demander un compte
                        </router-link>
                    </li>
                    @else
                        <li>
                            <a href="#:javascript" title="Leads" class="menu-deroulant">
                                <i class="fas fa-water"></i> <span class="d-inline-block">Mes mares</span>
                                <i class="fas fa-caret-down float-right d-none d-sm-none"></i>
                                <i class="fas fa-caret-right float-right d-none d-sm-none"></i>
                            </a>

                            <ul class="nav-item-slidedown">
                                <li>
                                    <router-link to="/mes_mares" onclick="changeMenu()">
                                        <i class="fas fa-angle-right"></i> Toutes mes mares
                                    </router-link>
                                </li>
                                <li>
                                    <router-link to="/mes_mares/attentes" onclick="changeMenu()">
                                        <i class="fas fa-angle-right"></i> Mares en attente
                                    </router-link>
                                </li>
                                <li>
                                    <router-link to="/fiches_attentes" onclick="changeMenu()">
                                        <i class="fas fa-angle-right"></i> Fiches en attente
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        @can('access_validation_mares_fiches', App\Role::class)
                            <li>
                                <a href="#:javascript" title="Leads" class="menu-deroulant">
                                    <i class="fas fa-clipboard-check"></i> <span class="d-inline-block">Validation</span>
                                    <i class="fas fa-caret-down float-right d-none d-sm-none"></i>
                                    <i class="fas fa-caret-right float-right d-none d-sm-none"></i>
                                </a>

                                <ul class="nav-item-slidedown">
                                    <li>
                                        <router-link to="/validation_mares" onclick="changeMenu()">
                                            <i class="fas fa-angle-right"></i> Mares
                                        </router-link>
                                    </li>
                                    <li>
                                        <router-link to="/validation_fiches" onclick="changeMenu()">
                                            <i class="fas fa-angle-right"></i> Fiches
                                        </router-link>
                                    </li>
                                    @can('access_validation_compte', App\Role::class)
                                        <li>
                                            <router-link to="/validation_comptes" onclick="changeMenu()">
                                                <i class="fas fa-angle-right"></i> Comptes
                                            </router-link>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('access_parametres', App\Role::class)
                            <li>
                                <a href="#:javascript" title="Leads" class="menu-deroulant">
                                    <i class="fas fa-cogs"></i> <span class="d-inline-block">Paramètres</span>
                                    <i class="fas fa-caret-down float-right d-none d-sm-none"></i>
                                    <i class="fas fa-caret-right float-right d-none d-sm-none"></i>
                                </a>

                                <ul class="nav-item-slidedown">
                                    @can('access_utilisateurs', App\Role::class)
                                        <li>
                                            <router-link to="/utilisateurs" onclick="changeMenu()">
                                                <i class="fas fa-angle-right"></i> Utilisateurs
                                            </router-link>
                                        </li>
                                    @endcan

                                    <li>
                                        <router-link to="/parametres" onclick="changeMenu()">
                                            <i class="fas fa-angle-right"></i> Autres
                                        </router-link>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        <li>
                            <a href="#:javascript" title="Assistance" class="menu-deroulant">
                                <i class="fas fa-question-circle "></i> <span class="d-inline-block">Assistance</span>
                                <i class="fas fa-caret-down float-right d-none d-sm-none"></i>
                                <i class="fas fa-caret-right float-right d-none d-sm-none"></i>
                            </a>

                            <ul class="nav-item-slidedown">
                                <li>
                                    <router-link to="/aides" onclick="changeMenu()">
                                        <i class="fas fa-angle-right"></i> Aide
                                    </router-link>
                                </li>
                                <li>
                                    <router-link to="/ameliorations" onclick="changeMenu()">
                                        <i class="fas fa-angle-right"></i> Améliorations
                                    </router-link>
                                </li>
                                <li>
                                    <router-link to="/tickets" onclick="changeMenu()">
                                        <i class="fas fa-angle-right"></i> Tickets
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class="menu-fixe" onclick="deconnexionButton()">
                                <i class="fas fa-door-open"></i> <span class="d-inline-block">Deconnexion</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</aside>