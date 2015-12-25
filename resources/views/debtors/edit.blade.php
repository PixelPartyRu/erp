
    <p>
		{!! Form::model($debtor, array('route' => array('debtor.update', $debtor->id), 'method' => 'PUT')) !!}

		  {!! Form::text('full_name', @$debtor->full_name) !!}
		  {!! Form::text('name', @$debtor->name) !!}
		  {!! Form::text('inn', @$debtor->inn) !!}
		  {!! Form::text('kpp', @$debtor->kpp) !!}
		  {!! Form::text('ogrn', @$debtor->ogrn) !!}
		  {!! Form::submit('Сохранить') !!}

		{!! Form::close() !!}
    </p>
