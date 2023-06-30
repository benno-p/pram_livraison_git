<table>
	<thead>
		<tr>
			@include('exports.excel.entete_mare')
			@include('exports.excel.entete_fiche')
		</tr>
	</thead>
	
	<tbody>
		@foreach($data as $mare)
			@foreach($mare->fiches as $fiche)
				<tr>
					@include('exports.excel.data_mare')

					@include('exports.excel.data_fiche')
				</tr>
			@endforeach
		@endforeach
	</tbody>
</table>