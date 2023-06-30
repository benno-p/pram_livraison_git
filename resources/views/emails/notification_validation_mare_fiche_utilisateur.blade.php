@extends('emails.email_layout')

@section('content')
	<!-- module 2 -->
	<table data-module="module-2" data-thumb="" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td data-bgcolor="bg-module" bgcolor="#eaeced">
				<table class="flexible" width="800" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
					<tr>
						<td data-bgcolor="bg-block" class="holder" style="padding:58px 60px 52px;" bgcolor="#f9f9f9">
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td data-color="title" data-size="size title" data-min="25" data-max="45" data-link-color="link title color" data-link-style="text-decoration:none; color:#292c34;" class="title" align="left" style="font:35px/38px Arial, Helvetica, sans-serif; color:#292c34; padding:0 0 24px;">
										Validation d'une {{$type}} sur {{ config('app.name') }} !
									</td>
								</tr>
								<tr>
									<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:normal; text-decoration:underline; color:#40aceb;" align="left" style="font:normal 16px/25px Arial, Helvetica, sans-serif; color:#888; padding:0 0 23px;">
										@if($type == 'mare')
	                                        <p>
	                                        	Bonjour, {{$mare->utilisateur_id && $mare->utilisateur->nom && $mare->utilisateur->prenom ? $mare->utilisateur->nom.' '. $mare->utilisateur->prenom : ''}}
	                                        </p>
	                                    @else
	                                    	<p>
	                                        	Bonjour, {{$fiche->utilisateur_id && $fiche->utilisateur->nom && $fiche->utilisateur->prenom ? $fiche->utilisateur->nom.' '. $fiche->utilisateur->prenom : ''}}
	                                        </p>
	                                    @endif
	                                    @if($type == 'mare')
		                                   	<p>
	                                        	La mare que vous avez enregistrée (identifiant : {{$mare->id}} {{$mare->nom ? ' - Nom de la mare : '.$mare->nom : ''}}) a été 
	                                        	{{$validation == 1 ? 'Validée' : 'Refusée'}}
	                                        </p>
	                                    @else
	                                    	<p>
	                                        	La fiche que vous avez enregistrée pour la mare : 
	                                        	<ul>
	                                        		<li>identifiant :{{$mare->id}}</li>
	                                        		<li>Nom de la mare : {{$mare->nom ? $mare->nom : 'N/C'}}</li>
	                                        	</ul>
	                                        	a été {{$validation == 1 ? 'Validée' : 'Refusée'}}
	                                        </p>
	                                    @endif

                                        @if($validation == 1)
                                        	@if($type == 'mare')
                                        		<p>Vous pouvez consulter la mare dans votre espace : <a href="{{ config('app.url').'/#/mes_mares/consultation/'.$mare->id }}">"Mes mares"</a></p>
                                        	@else
                                        		<p>Vous pouvez consulter la fiche dans votre espace : <a href="{{ config('app.url').'/#/mes_mares/consultation/'.$mare->id }}">"Mes mares"</a></p>
                                        	@endif
                                        @else
                                       		@if($type == 'mare')
	                                        	<p>Commentaire : {{$mare->commentaire_validation}}</p>
	                                        	<p>Vous pouvez modifier la mare dans votre espace  : <a href="{{ config('app.url').'/#/mes_mares/edit/'.$mare->id }}">"Mes mares en attentes"</a></p>
	                                        @else
	                                        	<p>Commentaire : {{$fiche->commentaire_validation}}</p>
	                                        	<p>Vous pouvez modifier la fiche dans votre espace  : <a href="{{ config('app.url').'/#/fiches_attentes/edit/'.$fiche->id }}">"Mes fiches en attentes"</a></p>
	                                        @endif
                                        @endif 
                                    </td>
								</tr>
                                <tr>
									<td style="padding:0 0 20px;">
										<table width="220" align="center" style="margin:0 auto;" cellpadding="0" cellspacing="0">
											<tr>
												<td data-bgcolor="bg-button" data-size="size button" data-min="10" data-max="16" class="btn" align="center" style="font:16px/18px Arial, Helvetica, sans-serif; color:#ffffff; mso-padding-alt:12px 10px 10px; border-radius:2px;" bgcolor="#343a40">
													<a target="_blank" style="text-decoration:none; color:#ffffff; display:block; padding:12px 10px 10px;" href="{{ URL::to('/') }}">Accéder à {{ config('app.name') }}</a>
												</td>
											</tr>
										</table>
									</td>
                                </tr>
                                <tr>
									<td data-color="text" data-size="size text" data-min="10" data-max="26" data-link-color="link text color" data-link-style="font-weight:normal; text-decoration:underline; color:#40aceb;" align="left" style="font:normal 16px/25px Arial, Helvetica, sans-serif; color:#888; padding:0 0 23px;">
                                        <p>Si vous ne parvenez pas à cliquer sur le bouton ci-dessus, veuillez copier-coller ce lien dans votre navigateur : 
                                            <a target="_blank" href="{{ URL::to('/') }}">{{ URL::to('/') }}</a></p> 
                                    </td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td height="28"></td></tr>
				</table>
			</td>
		</tr>
	</table>
@stop