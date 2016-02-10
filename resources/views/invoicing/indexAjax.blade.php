							@forelse ($bills as $bill)
							<tr>
								<td>{{$bill->client->name}}</td>
								<td>{{$bill->chargeCommission->delivery->relation->agreement->code}}</td>
								<td></td>
								<td>{{@date('d/m/Y',strtotime($bill->date_of_funding))}}</td>
								<td>{{$bill->id}}</td>
								<td></td>
								<td></td>
								<td>{{$bill->nds}}</td>
								<td>{{$bill->with_nds}}</td>
								<td></td>
								<td></td>
							</tr>
							@empty
							<tr>
								<td class="no-space">
									<p>Нет счетов за выбранный месяц</p>
								</td>
							</tr>
							@endforelse