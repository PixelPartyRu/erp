<?php 
							$value_sum=0;
							$usedLimit_sum=0;
						?>
				  	@forelse($limits as $key => $limit)
						<tr>
							<?php 
								if ($limit->value > 0)
									$congestion= $usedLimit[$key]/$limit->value*100;
								else
									$congestion= 0;
							?>
							<td class="{{ $limit->value < $usedLimit[$key] ? 'danger':''}}">&nbsp&nbsp&nbsp&nbsp</td>
							<td>{{$limit->relation->client->name}}</td>
							<td>{{$limit->relation->debtor->name}}</td>
							<td><a href="#" id="value" class="editable" data-type="text" data-pk="1" data-url="{{URL::current()}}/{{$limit->relation->limit->id}}" data-title="Новый лимит">{{number_format($limit->value, 2, ',', ' ')}}</a></td>
							<td>{{number_format($usedLimit[$key], 2, ',', ' ')}}</td>
							<td>{{number_format($congestion, 2, ',', ' ')}}</td>
							<td>{{number_format($limit->value-$usedLimit[$key], 2, ',', ' ')}}</td>
							<td><a data-delete="{{URL::current()}}/{{$limit->relation->limit->id}}" data-method="delete" class="deleteItem"><i class="fa fa-minus" data-toggle="tooltip" title="Удалить"></i></i></td>
							<td></td>
						</tr>
						<?php 
							$value_sum+=$limit->value;
							$usedLimit_sum+=$usedLimit[$key];
						?>
					@empty
						<tr><td><p>Лимитов нет</p></td></tr>
					@endforelse
					<?php 
						if ($value_sum > 0)
							$congestion= $usedLimit_sum/$value_sum*100;
						else
							$congestion= 0;
						$freeLimit_sum=$value_sum-$usedLimit_sum;
					?>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>{{number_format($value_sum, 2, ',', ' ')}}</td>
							<td>{{number_format($usedLimit_sum, 2, ',', ' ')}}</td>
							<td>{{number_format($congestion, 2, ',', ' ')}}</td>
							<td>{{number_format($freeLimit_sum, 2, ',', ' ')}}</td>
							<td></td>
							<td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
						</tr>
						<script>
							$(document).ready(function(){
								if($('th').is(":contains('Клиент')")){
								}
								else
									$('th:contains("Установленный лимит")').before('<th>Клиент</th>');
								if($('th').is(":contains('Дебитор')")){
								}
								else
									$('th:contains("Установленный лимит")').before('<th>Дебитор</th>');
							});
						</script>