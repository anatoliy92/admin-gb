@extends('avl.default')

@section('js')
	<script src="{{ asset('vendor/admingb/js/gb.js') }}" charset="utf-8"></script>
@endsection

@section('main')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-align-justify"></i> {{ $section->name_ru }}
			<div class="card-actions">
				<a class="btn collapsed pl-3 pr-3" data-toggle="collapse" href="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter"><i class="fa fa-sliders"></i></a>
				@can('create', $section)
					<a href="{{ route('admingb::sections.gb.create', ['id' => $id]) }}" class="pl-3 pr-3 bg-primary text-white" title="Добавить"><i class="fa fa-plus"></i></a>
				@endcan
			</div>
		</div>
		<div class="card-body">
			<div class="collapse" id="collapseFilter">
        		<div class="card">
          			<div class="card-body">
						<form action="" method="get" class="mb-4">
							<div class="row">
								@if ($section->rubric > 0)
									<div class="col-4">
										{{ Form::select('rubric', $rubrics, $request->input('rubric'), ['placeholder' => 'Все обращения', 'class' => 'form-control']) }}
									</div>
								@endif
								<div class="col-2">
									{{ Form::text('gb_id', null, ['class' => 'form-control', 'placeholder' => 'Номер вопроса (ID)']) }}
								</div>
								<div class="col-4">
									{{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) }}
								</div>
								<div class="col-2">
									<button type="submit" class="btn btn-primary w-100">Показать</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>

			@if ($gbs)
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th width="50" class="text-center">ID</th>
								<th class="text-center" style="width: 20px">Вкл / Выкл</th>
								<th class="text-center">ФИО</th>
								<th class="text-center">Тема</th>
								<th class="text-center">Email</th>
								<th class="text-center">Контактный номер</th>
								<th class="text-center" style="width: 160px">Дата создания</th>
								<th class="text-center" style="width: 100px;">Действие</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($gbs as $gb)
								<tr class="position-relative" id="gb--item-{{ $gb->id }}">
									<td class="text-center">{{ $gb->id }}</td>
										<td class="text-center">
											<a class="change--status" href="#" data-id="{{ $gb->id }}" data-model="Avl\AdminGb\Models\Gbs">
												<i class="fa @if ($gb->good == 1){{ 'fa-eye' }}@else{{ 'fa-eye-slash' }}@endif"></i>
											</a>
										</td>
									<td>{{ $gb->name }} {{ $gb->surname }}</td>
									@if ($section->rubric == 1 && $gb->theme_id != 0)
										<td class="text-center">@if(!is_null($gb->theme_id) || $gb->theme_id != 0)@if(!is_null($gb->theme->title_ru)){{ $gb->theme->title_ru }}@else{{ str_limit(strip_tags($gb->theme->description_ru), 70) }}@endif @endif</td>
									@endif
									@if ($gb->theme_id == 0)
										<td class="text-center">Не выбрана тема вопроса</td>
									@endif
									<td>{{ $gb->email }}</td>
									<td>{{ $gb->contact_phone }}</td>
									<td>{{ $gb->created_at }}</td>
									<td class="text-right">
										<div class="btn-group" role="group">
											@can('view', $section) <a href="{{ route('admingb::sections.gb.show', ['id' => $id, 'gb_id' => $gb->id]) }}" class="btn btn btn-outline-primary" title="Просмотр"><i class="fa fa-eye"></i></a> @endcan
											@can('update', $section) <a href="{{ route('admingb::sections.gb.edit', ['id' => $id, 'gb_id' => $gb->id]) }}" class="btn btn btn-outline-success" title="Изменить"><i class="fa fa-edit"></i></a> @endcan
											@can('delete', $section) <a href="#" class="btn btn btn-outline-danger remove--record" title="Удалить"><i class="fa fa-trash"></i></a> @endcan
										</div>
										@can('delete', $section)
											<div class="remove-message">
													<span>Вы действительно желаете удалить запись?</span>
													<span class="remove--actions btn-group btn-group-sm">
															<button class="btn btn-outline-primary cancel"><i class="fa fa-times-circle"></i> Нет</button>
															<button class="btn btn-outline-danger remove--gb" data-id="{{ $gb->id }}" data-section="{{ $id }}"><i class="fa fa-trash"></i> Да</button>
													</span>
											</div>
										 @endcan
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

				</div>
			@endif
		</div>
	</div>
@endsection
