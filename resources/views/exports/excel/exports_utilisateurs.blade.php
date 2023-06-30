<table>
	<thead>
		<tr>
			<th>Nom</th>
			<th>Prénom</th>
			<th>Rôle</th>
			<th>Email</th>
			<th>Groupe</th>
		</tr>
	</thead>

	
	<tbody>
		@foreach($data as $utilisateur)
			<tr>
				<td>{{$utilisateur['nom']}}</td>
				<td>{{$utilisateur['prenom']}}</td>
				<td>{{$utilisateur['role']}}</td>
				<td>{{$utilisateur['email']}}</td>
				<td>{{$utilisateur['groupe']}}</td>
			</tr>
		@endforeach
	</tbody>
</table>