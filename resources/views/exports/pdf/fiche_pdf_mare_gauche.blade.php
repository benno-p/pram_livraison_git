<div class="en-tete">
    <h2 class="text-white">Données générales</h2>
</div>
<div>
    <table>
        <tr>
            <td style="width: 300px;">
                <span class="fw-weight">Identifiant PRAM</span> : {{$fiche->id}}
            </td>
            <td style="width: 400px;">
                <span class="fw-weight">X Lambert 93</span> : {{$fiche->mare->x_l93}}
            </td>
        </tr>
        <tr>
            <td style="width: 250px;">
                <span class="fw-weight">Nom</span> : {{$fiche->mare->nom}}
            </td>
            <td style="width: 400px;">
                <span class="fw-weight">Y Lambert 93</span> : {{$fiche->mare->y_l93}}
            </td>
        </tr>
        <tr>
            <td style="width: 250px;">
                <span class="fw-weight">Commune</span> : {{$fiche->mare->commune->insee}} {{$fiche->mare->commune->nom}}
            </td>
            <td style="width: 400px;" rowspan="5">
                Groupe faunistiques observés :
                <ul>
                    @foreach($fiche->groupe_faunistique as $faune)
                        <li>{{$faune}}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <td style="width: 250px;">
                <span class="fw-weight">Date</span> : {{convertDate($fiche->mare->created_at)}}
            </td>
<!--                             <td style="width: 400px;">
                Coordonnées Y Lambert 93 : {{$fiche->mare->y_l93}}
            </td> -->
        </tr>
        <tr>
            <td style="width: 250px;">
                <span class="fw-weight">Observateur</span> : {{$fiche->mare->observateur_id ? $fiche->mare->observateur->prenom : ''}} {{$fiche->mare->observateur_id ? $fiche->mare->observateur->nom : ''}}
            </td>
<!--                             <td style="width: 400px;">
                Coordonnées Y Lambert 93 : {{$fiche->mare->y_l93}}
            </td> -->
        </tr>
        <tr>
            <td style="width: 250px;">
                <span class="fw-weight">Je suis le</span> : {{$fiche->mare->type_observateur_id ? $fiche->mare->type_observateur->nom : ''}}
            </td>
<!--                             <td style="width: 400px;">
                Coordonnées Y Lambert 93 : {{$fiche->mare->y_l93}}
            </td> -->
        </tr>
        <tr>
            <td style="width: 250px;">
                <span class="fw-weight">Type de propriété</span> : {{$fiche->mare->type_propriete_id ? $fiche->mare->type_propriete->nom : ''}}
            </td>
<!--                             <td style="width: 400px;">
                Présence de végétation aquatique : 
            </td> -->
        </tr>
    </table>
</div>

<div class="mb-1 bloc-encadrement">
    <div class="text-center mb-1">
        <span class="fw-weight">Type de mare</span>
    </div>
    <div>
        @foreach($types_mares as $type)
            <span style="margin: 6px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->type_mare_id == $type->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->type_mare_id == $type->id) class="text-success" @else class="text-secondary" @endif>{{$type->nom}}</span>
            </span>
        @endforeach
    </div>
</div>

<div class="mb-1 bloc-encadrement">
    <div class="text-center">
        <span class="fw-weight">Stade d'évolution de la mare</span>
    </div>
    <div>
        <table>
            <tr>
                @foreach($stades as $key => $stade)
                    <td>
                        @if($key === 1)
                            <img src="{{$stade->chemin_image}}" style="height:80px;width: 80px;margin-top: 10px;" />
                        @elseif($key === 3)
                            <img src="{{$stade->chemin_image}}" style="height:80px;width: 90px;" />
                        @elseif($key === 4)
                            <img src="{{$stade->chemin_image}}" style="height:70px;width: 80px;margin-bottom: 20px;" />
                        @elseif($key === 5)
                            <img src="{{$stade->chemin_image}}" style="height:70px;width: 110px;margin-bottom: 14px;" />
                        @else
                            <img src="{{$stade->chemin_image}}" style="height:80px;width: 80px;" />
                        @endif
                    </td>
                @endforeach
            </tr>
            <tr>
                @foreach($stades as $key => $stade)
                    <td>
                        <div class="text-center">
                            <input type="checkbox" class="checkbox-height" @if($fiche->stade_evolution_mare_id == $stade->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                            <span @if($fiche->stade_evolution_mare_id == $stade->id) class="text-success" @else class="text-secondary" @endif>{{$stade->nom}}</span>
                        </div>
                    </td>
                @endforeach
            </tr>
        </table>
    </div>
</div>

<div class="en-tete">
    <h2 class="text-white">Usages</h2>
</div>

<div class="mb-1">
    <div class="mb-05">
        <span class="fw-weight">Usage(s) observé(s) de la mare : </span>
        @foreach($usages_mares as $usage)
            <span style="margin: 8px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->usage_mares->contains('id', $usage->id)) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->usage_mares->contains('id', $usage->id)) class="text-success" @else class="text-secondary" @endif>{{$usage->nom}}</span>
            </span>
        @endforeach
    </div>

    <div class="mb-05">
        <span class="fw-weight">Mare équipée d’une pompe à nez ?</span>
        <input type="checkbox" class="checkbox-height" @if($fiche->pompe_a_nez == true) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
        <span @if($fiche->pompe_a_nez == true) class="text-success" @else class="text-secondary" @endif>Oui</span>
        <input type="checkbox" class="checkbox-height" @if($fiche->pompe_a_nez == false) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
        <span @if($fiche->pompe_a_nez == false) class="text-success" @else class="text-secondary" @endif>Non</span>
    </div>

    <div>
        <span class="fw-weight">Présence de déchets ?</span>
        @foreach($usages_dechets as $dechet)
            <span style="margin: 6px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->usage_dechets->contains('id', $dechet->id)) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->usage_dechets->contains('id', $dechet->id)) class="text-success" @else class="text-secondary" @endif>{{$dechet->nom}}</span>
            </span>
        @endforeach
    </div>
</div>

<div class="en-tete">
    <h2 class="text-white">Situation</h2>
</div>

<div class="mb-05">
    <span class="fw-weight">Topographie</span>
    @foreach($topographies as $topographie)
        <span style="margin: 6px;">
            <input type="checkbox" class="checkbox-height" @if($fiche->mare->situation_topographie_id == $topographie->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
            <span @if($fiche->mare->situation_topographie_id == $topographie->id) class="text-success" @else class="text-secondary" @endif>{{$topographie->nom}}</span>
        </span>
    @endforeach
</div>

<div class="mb-05">
    <span class="fw-weight">Contexte</span>
    @foreach($contextes as $contexte)
        <span style="margin: 5px;">
            <input type="checkbox" class="checkbox-height" @if($fiche->situation_contextes->contains('id', $contexte->id)) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
            <span @if($fiche->situation_contextes->contains('id', $contexte->id)) class="text-success" @else class="text-secondary" @endif>{{$contexte->nom}}</span>
        </span>
    @endforeach
</div>

<div class="mb-05">
    <span class="fw-weight">Petit patrimoine bâti associé ?</span>
    @foreach($batis as $bati)
        <span style="margin: 5px;">
            <input type="checkbox" class="checkbox-height" @if($fiche->situation_batis->contains('id', $bati->id)) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
            <span @if($fiche->situation_batis->contains('id', $bati->id)) class="text-success" @else class="text-secondary" @endif>{{$bati->nom}}</span>
        </span>
    @endforeach
</div>

<div class="mb-05">
    <span class="fw-weight">Mare clôturée ?</span>
    @foreach($clotures as $cloture)
        <span style="margin: 2px;">
            <input type="checkbox" class="checkbox-height" @if($fiche->situation_cloture_id == $cloture->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
            <span @if($fiche->situation_cloture_id == $cloture->id) class="text-success" @else class="text-secondary" @endif>{{$cloture->nom}}</span>
        </span>
    @endforeach
</div>

<div>
    <span class="fw-weight">Présence d’une haie en contact avec la mare ?</span>
    <input type="checkbox" class="checkbox-height" @if($fiche->haie_contact_mare == true) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
    <span @if($fiche->haie_contact_mare == true) class="text-success" @else class="text-secondary" @endif>Oui</span>
    <input type="checkbox" class="checkbox-height" @if($fiche->haie_contact_mare == false) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
    <span @if($fiche->haie_contact_mare == false) class="text-success" @else class="text-secondary" @endif>Non</span>
</div>