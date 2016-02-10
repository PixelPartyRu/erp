<table class="table table-striped">
   <thead>
     <tr>
       <th>Номер</th>
       <th>Дата</th>
       <th>Сумма</th>
       <th>Инфо по корреспонденту</th>
       <th>ИНН корреспондента</th>
       <th>Назначение платежа</th>
       <th>Клиент в системе</th>
       <th>Дебитор в системе</th>
     </tr>
   </thead>
   <tbody>
      @foreach ($resultArray as $row)
         @if (strpos($row['Получатель'],'Факторинг-Финанс'))
            <?php 
                  $client = $clients->where('inn',$row['ПолучательИНН'])->first(); 
                  $debtor = $debtors->where('inn',$row['ПолучательИНН'])->first(); 
                  $clientChoice = '0'; 
                  $debtorChoice = '0'; 

                  if($client == null && $debtor == null){
                     $clientChoice = '0'; 
                     $debtorChoice = '0'; 
                  }elseif($client != null){
                     $clientChoice = $client->id;
                     $relation = $client->relation;
                     if ($relation->count() == 1){
                        $debtorChoice = $relation->debtor->id;
                     }else{
                        $debtorChoice = '0'; 
                     } 
                  }elseif($debtor != null){
                     $debtorChoice = $debtor->id;
                     $relation = $debtor->relation;
                     if ($relation->count() == 1){
                        $clientChoice = $relation->debtor->id;
                     }else{
                        $clientChoice = '0';
                     }  
                  }elseif($client != null && $debtor != null){
                     $clientChoice = $client->id;
                     $debtorChoice = '0';
                  }
            ?>
            <tr class="importModalTableBodyTr">
            	<td class='no-space'>{{ $row['Номер'] }}</td>
               <td class='date no-space' data-val={{ $row['Дата'] }}>{{ date('d/m/Y', strtotime($row['Дата'])) }}</td>
               <td class='no-space' data-val={{ $row['Сумма'] }} >
                  <nobr>{{ number_format($row['Сумма'],2,',',' ') }}</nobr>
               </td>
            	<td class='no-space'>{{ $row['Плательщик'] }}</td>
            	<td class='no-space'>{{ $row['ПлательщикИНН'] }}</td>
               <td>{{ $row['НазначениеПлатежа'] }}</td>
               <td  class='no-space'>
                  {!! Form::select('clientPayerCreate', ['0' => 'Выберите клиента'] + array_pluck($clients, 'name', 'id'),$clientChoice,array('class' => 'selectpicker')) !!}
               </td>
               <td class='no-space'>
                  {!! Form::select('debtorPayerCreate', ['0' => 'Выберите дебитора'] + array_pluck($debtors, 'name', 'id'),$debtorChoice,array('class' => 'selectpicker')) !!}
               </td>    	
            </tr>
         @endif
      @endforeach
   </tbody>
</table>