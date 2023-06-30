<table>
	<thead>
		<tr>
			@include('exports.excel.entete_mare')
			@include('exports.excel.entete_fiche')
		</tr>
	</thead>
	
	<tbody>
		@foreach($data as $mare)
			<tr>
				@include('exports.excel.data_mare')

				@php
					$fiche = $mare->latestFiche
				@endphp

				@include('exports.excel.data_fiche')
			</tr>
		@endforeach
	</tbody>
</table>