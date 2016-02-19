<div class="table-responsive">
	<table class="table table-striped excel-table">
	  <thead>
	  	<tr>
			<th class="head-start">№</th>
	  		<th>Полное наименование</th>
	  		<th>Наименование</th>
	  		<th>ИНН</th>
	  		<th>КПП</th>
	  		<th class="head-finish">ОГРН</th>
	  		<th></th>
	  		<th></th>
	  		<th></th>
	  	</tr>
	  </thead>
	  <tbody class='layoutTable' id="datatable">
	  	<?php $num = 0; ?>
	  	@forelse($clients as $client)
			<tr>
				<td class="body-start">{{ $num += 1 }}</td>
				<td>{{ $client->full_name }}</td>
				<td>{{ $client->name }}</td>
				<td>{{ $client->inn }}</td>
				<td>{{ $client->kpp }}</td>
				<td class="body-finish">{{ $client->ogrn }}</td>
				<td><a href="/client/{{ $client->id }}/agreement"><i class="fa fa-file-text-o" data-toggle="tooltip" title="Договора"></i></a></td>
				<td><a href="/client/{{ $client->id }}/edit"><i class="fa fa-pencil" data-toggle="tooltip" title="Редактировать"></i></a></td>
				<td><a class="deleteItem" data-delete="/client/{{ $client->id }}" data-method="delete"><i class="fa fa-close"  data-toggle="tooltip" title="Удалить"></a></i></td>
			</tr>
		@empty
			<tr>
				<td>
					<p>Нет клиентов</p>
				</td>
			</tr>
		@endforelse
	  </tbody>
	</table>
</div>