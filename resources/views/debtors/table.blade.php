<div class="table-responsive">
	<table class="table table-striped" id="debtor-table">
	  <thead>
	  	<tr>
	  		<th>№</th>
	  		<th>Полное наименование</th>
	  		<th>Наименование</th>
	  		<th>ИНН</th>
	  		<th>КПП</th>
	  		<th>ОГРН</th>
	  		<th></th>
	  		<th></th>
	  	</tr>
	  </thead>
	  <tbody>
	  {{-- */ $num = 0; /* --}}
	  	@forelse($debtors as $debtor)
			<tr>
				<td>{{ $num += 1 }}</td>
				<td>{{ $debtor->full_name }}</td>
				<td>{{ $debtor->name }}</td>
				<td>{{ $debtor->inn }}</td>
				<td>{{ $debtor->kpp }}</td>
				<td>{{ $debtor->ogrn }}</td>
				<td><a href="/debtor/{{ $debtor->id }}/edit"><i class="fa fa-pencil" data-toggle="tooltip" title="Редактировать"></i></a></td>
				<td>
				<a class="deleteItem" data-toggle="tooltip" title="Удалить" data-delete="/debtor/{{$debtor->id}}" data-method="delete"><i class="fa fa-close"></i></a>
				</td>
			</tr>
		@empty
			<tr>
				<td>Дебиторов нет</td>
			</tr>
		@endforelse
	  </tbody>
	</table>
</div>