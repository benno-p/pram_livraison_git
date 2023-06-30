<table>
	<thead>
		<tr>
			@include('exports.excel.entete_mare')
		</tr>
	</thead>

	
	<tbody>
		@foreach($data as $mare)
			<tr>
				@include('exports.excel.data_mare')
			</tr>
		@endforeach
	</tbody>
</table>