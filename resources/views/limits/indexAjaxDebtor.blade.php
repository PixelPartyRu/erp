
				<?php 
							$value_sum=0;
							$usedLimit_sum=0;
						?>
				  	@forelse($debtors as $key => $debtor)
						<tr>
							<?php 
								$debtor_val_limit=0;
								$usedLimitItem=0;
								foreach ($debtor->relations as $relation) {
									if($relation->limit){
										$debtor_val_limit += $relation->limit->value;
										foreach ($relation->deliveries as $delivery) {
							                if($delivery->status=='Профинансирована'){
												if($countCongestion=='remainder_of_the_debt_first_payment'){
								                    $usedLimitItem+=$delivery->remainder_of_the_debt_first_payment;
								                }else{
								                    $usedLimitItem+=$delivery->balance_owed;
								                }
											} 
							            }
							        }	
									
								}
								if($debtor_val_limit==0)
									continue;
								else
									$congestion= $usedLimitItem/$debtor_val_limit*100;
							?>
							<td class="{{ $debtor_val_limit < $usedLimitItem ? 'danger':''}}">&nbsp&nbsp&nbsp&nbsp</td>
							<td>{{$debtor->name}}</td>
							<td>{{number_format($debtor_val_limit, 2, ',', ' ')}}</a></td>
							<td>{{number_format($usedLimitItem, 2, ',', ' ')}}</td>
							<td>{{number_format($congestion, 2, ',', ' ')}}</td>
							<td>{{number_format($debtor_val_limit-$usedLimitItem, 2, ',', ' ')}}</td>
							<td></td>
							<td></td>
						</tr>
						<?php 
							$value_sum+=$debtor_val_limit;
							$usedLimit_sum+=$usedLimitItem;
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
							<td>{{number_format($value_sum, 2, ',', ' ')}}</td>
							<td>{{number_format($usedLimit_sum, 2, ',', ' ')}}</td>
							<td>{{number_format($congestion, 2, ',', ' ')}}</td>
							<td>{{number_format($freeLimit_sum, 2, ',', ' ')}}</td>
							<td></td>
							<td></td>
						</tr>
						<script>
							$(document).ready(function(){
								$('th:contains("Клиент")').remove();
								if($('th').is(":contains('Дебитор')")){
								}
								else
									$('th:contains("Установленный лимит")').before('<th>Дебитор</th>');
							});
						</script>