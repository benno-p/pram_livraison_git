<div class="en-tete">
    <h2 class="text-white">Caractéristiques abiotiques de la mare</h2>
</div>

<div class="mb-1">
    <div class="mb-05">
        <span class="fw-weight">Forme :</span>
        @foreach($formes as $forme)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->caracteristique_forme_id == $forme->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->caracteristique_forme_id == $forme->id) class="text-success" @else class="text-secondary" @endif>{{$forme->nom}}</span>
            </span>
        @endforeach
    </div>
    <div class="mb-05">
        <span class="fw-weight">Taille moyenne :</span>
        <span>Longueur : {{$fiche->caracteristique_longueur}}m -</span>
        <span>Largeur : {{$fiche->caracteristique_largeur}}m</span>
    </div>
    <div class="mb-05">
        <span class="fw-weight">Hauteur d’eau maximum observée :</span>
        @foreach($eau_hauteurs as $hauteur)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->caracteristique_eau_hauteur_id == $hauteur->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->caracteristique_eau_hauteur_id == $hauteur->id) class="text-success" @else class="text-secondary" @endif>{{$hauteur->nom}}</span>
            </span>
        @endforeach
    </div>
    <div class="mb-05">
        <span class="fw-weight">Nature du fond de la mare :</span>
        @foreach($fond_mares as $fond)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->caracteristique_fond_mare_id == $fond->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->caracteristique_fond_mare_id == $fond->id) class="text-success" @else class="text-secondary" @endif>{{$fond->nom}}</span>
            </span>
        @endforeach
    </div>
    <div class="mb-05">
        <span class="fw-weight">Berges en pente douce :</span>
        @foreach($berges as $berge)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->caracteristique_berge_id == $berge->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->caracteristique_berge_id == $berge->id) class="text-success" @else class="text-secondary" @endif>{{$berge->nom}}</span>
            </span>
        @endforeach
    </div>
    <div class="mb-05">
        <span class="fw-weight">Bourrelet de curage en haut de berge</span>
        <input type="checkbox" class="checkbox-height" @if($fiche->caracteristique_curage_haut_berge == false) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
        <span @if($fiche->caracteristique_curage_haut_berge == false) class="text-success" @else class="text-secondary" @endif>Non</span>

        <input type="checkbox" class="checkbox-height" @if($fiche->caracteristique_curage_haut_berge == true) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
        <span @if($fiche->caracteristique_curage_haut_berge == true) class="text-success" @else class="text-secondary" @endif>Oui</span>

        @if($fiche->caracteristique_curage_haut_berge == true && $fiche->caracteristique_curage_haut_berge_texte)
            <span class="text-success"> : {{$fiche->caracteristique_curage_haut_berge_texte}}% du périmètre de la mare</span>
        @endif
    </div>
    <div class="mb-05">
        <span class="fw-weight">Surpiétinement des abords :</span>
        @foreach($surpietinements as $surpietinement)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->caracteristique_pietinement_id == $surpietinement->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->caracteristique_pietinement_id == $surpietinement->id) class="text-success" @else class="text-secondary" @endif>{{$surpietinement->nom}}</span>
            </span>
        @endforeach
    </div>
</div>

<div class="en-tete">
    <h2 class="text-white">Hydrologie</h2>
</div>

<div class="mb-1">
    <div class="mb-05">
        <span class="fw-weight">Régime hydrologique :</span>
        @foreach($regimes as $regime)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->hydrologie_regime_id == $regime->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->hydrologie_regime_id == $regime->id) class="text-success" @else class="text-secondary" @endif>{{$regime->nom}}</span>
            </span>
        @endforeach
    </div>
    <div class="mb-05">
        <span class="fw-weight">Liaison(s) avec le réseau hydrographique superficiel : </span>
        @foreach($reseaux as $reseau)
            <span style="margin: 8px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->hydrologie_reseaus->contains('id', $reseau->id)) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->hydrologie_reseaus->contains('id', $reseau->id)) class="text-success" @else class="text-secondary" @endif>{{$reseau->nom}}</span>
            </span>
        @endforeach
    </div>
    <div class="mb-05">
        <span class="fw-weight">Alimentation spécifique : </span>
        @foreach($alimentations as $alimentation)
            <span style="margin: 8px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->hydrologie_alimentations->contains('id', $alimentation->id)) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->hydrologie_alimentations->contains('id', $alimentation->id)) class="text-success" @else class="text-secondary" @endif>{{$alimentation->nom}}</span>
            </span>
        @endforeach
    </div>
    <div class="mb-05">
        <span class="fw-weight">Turbidité de l'eau :</span>
        @foreach($turbidites as $turbidite)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->hydrologie_turbidite_id == $turbidite->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->hydrologie_turbidite_id == $turbidite->id) class="text-success" @else class="text-secondary" @endif>{{$turbidite->nom}}</span>
            </span>
        @endforeach
    </div>
    <div class="mb-05">
        <span class="fw-weight">Zone tampon :</span>
        @foreach($tampons as $tampon)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->hydrologie_tampon_id == $tampon->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->hydrologie_tampon_id == $tampon->id) class="text-success" @else class="text-secondary" @endif>{{$tampon->nom}}</span>
            </span>
        @endforeach
    </div>
    <div class="mb-05">
        <span class="fw-weight">Exutoire :</span>
        @foreach($exutoires as $exutoire)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->hydrologie_exutoire_id == $exutoire->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->hydrologie_exutoire_id == $exutoire->id) class="text-success" @else class="text-secondary" @endif>{{$exutoire->nom}}</span>
            </span>
        @endforeach
    </div>
</div>

<div class="en-tete">
    <h2 class="text-white">Ecologie</h2>
</div>

<div class="mb-1 bloc-encadrement">
    <div class="text-center">
        <span class="fw-weight">Recouvrement de la végétation herbacée sur la surface de la mare</span>
    </div>
    <div>
        <table>
            <tr>
                @foreach($recouvrements as $key => $recouvrement)
                    <td align="center">
                        @if($key === 'ecologie_helophytes')
                            <div style="width: 60px;" class="fw-weight text-success">
                                {{$fiche[$key]}}%
                            </div>
                        @elseif($key === 'ecologie_hydrophytes')
                            <div style="width: 150px;" class="fw-weight text-success">
                                {{$fiche[$key]}}%
                            </div>
                        @else
                            <div style="width: 40px;" class="fw-weight text-success">
                                {{$fiche[$key]}}%
                            </div>
                        @endif
                    </td>
                @endforeach
                <td align="center">100%</td>
            </tr>
            <tr>
                @foreach($recouvrements as $key => $recouvrement)
                    <td>
                        @if($key === 'ecologie_helophytes')
                            <img src="{{asset($recouvrement)}}" style="height:90px;width: 70px;" />
                        @elseif($key === 'ecologie_hydrophytes')
                            <img src="{{asset($recouvrement)}}" style="height:90px;width: 160px;" />
                        @else
                            <img src="{{asset($recouvrement)}}" style="height:90px;width: 50px;" />
                        @endif
                        {{-- 
<!--                         <div class="text-center">
                            <input type="checkbox" class="checkbox-height" @if($fiche->stade_evolution_mare_id == $stade->id) class="text-success checkbox-height" checked @endif>
                            <span @if($fiche->stade_evolution_mare_id == $stade->id) class="text-success" @endif>{{$stade->nom}}</span>
                        </div> -->
                        --}}
                    </td>
                @endforeach
                <td></td>
            </tr>
        </table>
    </div>
</div>

<div class="mb-1">
    <div class="mb-05">
        <span class="fw-weight">Boisement / embroussaillement des abords :</span>
        @foreach($boisements as $boisement)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->ecologie_boisement_id == $boisement->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->ecologie_boisement_id == $boisement->id) class="text-success" @else class="text-secondary" @endif>{{$boisement->nom}}</span>
            </span>
        @endforeach
    </div>

    <div class="mb-05">
        <span class="fw-weight">Ombrage surface par ligneux :</span>
        @foreach($ombrages as $ombrage)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->ecologie_ombrage_id == $ombrage->id) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->ecologie_ombrage_id == $ombrage->id) class="text-success" @else class="text-secondary" @endif>{{$ombrage->nom}}</span>
            </span>
        @endforeach
    </div>
</div>


<div class="en-tete">
    <h2 class="text-white">Intervenir en faveur de cette mare...</h2>
</div>

<div class="mb-1">
    <div class="mb-05">
        <span class="fw-weight">Travaux à envisager :</span>
        @foreach($interventions as $intervention)
            <span style="margin: 2px;">
                <input type="checkbox" class="checkbox-height" @if($fiche->interventions->contains('id', $intervention->id)) class="text-success checkbox-height" checked @else class="text-secondary checkbox-height" @endif>
                <span @if($fiche->interventions->contains('id', $intervention->id)) class="text-success" @else class="text-secondary" @endif>{{$intervention->nom}}</span>
            </span>
        @endforeach
    </div>

    <div>
        <span class="fw-weight">Dans quel(s) objectif(s) ?</span>
        <span>{{$fiche->intervenir_objectif}}</span>
    </div>
</div>