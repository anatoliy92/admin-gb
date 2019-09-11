@extends('avl.default')

@section('js')
	<script type="text/javascript">
		$("body").on('click', '.change--updated-date', function (e) {
			if ($(this).is(':checked')) {
				$('.updated--date').attr({'disabled': false});
			} else {
				$('.updated--date').attr({'disabled': true});
			}
		});
	</script>
	<script src="/avl/js/jquery-ui/jquery-ui.min.js" charset="utf-8"></script>
	<script src="/avl/js/uploadifive/jquery.uploadifive.min.js" charset="utf-8"></script>

	<script src="/avl/js/tinymce/tinymce.min.js" charset="utf-8"></script>

	<script src="/avl/js/jquery-ui/timepicker/jquery.ui.timepicker.js" charset="utf-8"></script>
@endsection

@section('css')
	<link rel="stylesheet" href="/avl/js/jquery-ui/jquery-ui.min.css">
	<link rel="stylesheet" href="/avl/js/uploadifive/uploadifive.css">
	<link rel="stylesheet" href="/avl/js/jquery-ui/timepicker/jquery.ui.timepicker.css">
@endsection

@section('main')
	<div class="card">
		<div class="card-header">
			<i class="fa fa-align-justify"></i> Просмотр обращения от <b>[{{ $gb->surname }} {{ $gb->name }}]</b>
			<div class="card-actions">
				<a href="{{ route('admingb::sections.gb.index', [ 'id' => $id ]) }}" class="btn btn-default pl-3 pr-3" style="width: 70px;" title="Назад"><i class="fa fa-arrow-left"></i></a>
			</div>
		</div>

		<div class="card-body">
			<form>

				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item">
						<a class="nav-link active show" href="#basic" data-toggle="tab">
							Основные данные
						</a>
					</li>
					@foreach($langs as $lang)
						<li class="nav-item">
							<a class="nav-link" href="#title_{{ $lang->key }}" data-toggle="tab">
								{{ $lang->name }}
							</a>
						</li>
					@endforeach
				</ul>

				<div class="tab-content">
					<div class="tab-pane active show" id="basic" role="tabpanel">
						<div class="row">
							<div class="col-12 col-sm-3">
								<div class="form-group">
									{{ Form::label(null, 'Дата обновления') }}
									<span class="form-control"> {{ date('Y-m-d', strtotime($gb->updated_date)) }} </span>
									{{-- {{ Form::text('gb_updated_date', date('Y-m-d', strtotime($gb->updated_date)), ['class' => 'form-control datepicker', 'id' => '']) }} --}}
								</div>
							</div>
							<div class="col-12 col-sm-3">
								<div class="form-group">
									{{ Form::label(null, 'Время обновления') }}
									<span class="form-control"> {{ date('H:i', strtotime($gb->updated_date)) }} </span>
									{{-- {{ Form::text('gb_updated_time', date('H:i', strtotime($gb->updated_date)), ['class' => 'form-control timepicker']) }} --}}
								</div>
							</div>
								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label for="gb_theme_id">Тема</label>
										<select class="form-control" name="gb_theme_id" disabled>
											@if (!is_null($rubrics))
												@foreach ($rubrics as $rubric)
													<option value="{{ $rubric->id }}" @if(old('gb_theme_id') == $rubric->id){{ 'selected' }}@elseif($gb->theme_id == $rubric->id){{ 'selected' }}@endif>{{ !is_null($rubric->title_ru) ? $rubric->title_ru : str_limit(strip_tags($rubric->description_ru), 100) }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
						</div>

						<div class="row">
							<div class="col-1">
								<div class="form-group">
									<label>Вкл / Выкл</label><br/>
									<label class="switch switch-3d switch-primary">
										<input name='gb_good' type='hidden' value='0'>
										<input type="checkbox" class="switch-input" name="gb_good" value="1"@if ($gb->good == 1) checked @endif>
										<span class="switch-label"></span>
										<span class="switch-handle"></span>
									</label>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									{{ Form::label(null, 'Фамилия') }}
									<span class="form-control"> {{ $gb->surname }} </span>
									{{-- {{ Form::text('gb_surname', $gb->surname ?? null, ['class' => 'form-control']) }} --}}
								</div>
							</div>
							<div class="col-5">
								<div class="form-group">
									{{ Form::label(null, 'Имя') }}
									<span class="form-control"> {{ $gb->name }} </span>
									{{-- {{ Form::text('gb_name', $gb->name ?? null, ['class' => 'form-control']) }} --}}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<div class="form-group">
									{{ Form::label(null, 'Адрес') }}
									<span class="form-control"> {{ $gb->address }} </span>
									{{-- {{ Form::text('gb_address', $gb->address ?? null, ['class' => 'form-control']) }} --}}
								</div>
							</div>
							<div class="col-4">
								<div class="form-group">
									{{ Form::label(null, 'Район или индекс') }}
									<span class="form-control"> {{ $gb->disctrict_or_index }} </span>
									{{-- {{ Form::text('gb_district_or_index', $gb->disctrict_or_index ?? null, ['class' => 'form-control']) }} --}}
								</div>
							</div>
							<div class="col-4">
								<div class="form-group">
									{{ Form::label(null, 'Страна') }}
									<select class="form-control" name="gb_country_id" disabled>
										@if (!is_null($countries))
											@foreach ($countries as $country)
												<option value="{{ $country->id }}" @if($gb->country_id == $country->id){{ 'selected' }}@endif>{{ $country->title_ru }}</option>
											@endforeach
										@endif
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									{{ Form::label(null, 'Email') }}
									<span class="form-control"> {{ $gb->email }} </span>
									{{-- {{ Form::text('gb_email', $gb->email ?? null, ['class' => 'form-control']) }} --}}
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									{{ Form::label(null, 'Контактный телефон') }}
									<span class="form-control"> {{ $gb->contact_phone }} </span>
									{{-- {{ Form::text('gb_contact_phone', $gb->contact_phone ?? null, ['class' => 'form-control']) }} --}}
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									{{ Form::label(null, 'Должность') }}
									<span class="form-control"> {{ $gb->position }} </span>
									{{-- {{ Form::text('gb_position', $gb->position ?? null, ['class' => 'form-control']) }} --}}
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									{{ Form::label(null, 'Организация') }}
									<span class="form-control"> {{ $gb->organization }} </span>
									{{-- {{ Form::text('gb_organization', $gb->organization ?? null, ['class' => 'form-control']) }} --}}
								</div>
							</div>
						</div>

					</div>
					@foreach ($langs as $lang)
						<div class="tab-pane"  id="title_{{$lang->key}}" role="tabpanel">

							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item"><a class="nav-link active show" href="#sub-tab_{{ $lang->key }}-inbox" data-toggle="tab">Вопрос</a></li>
								<li class="nav-item"><a class="nav-link" href="#sub-tab_{{ $lang->key }}-outbox" data-toggle="tab">Ответ</a></li>
							</ul>

							<div class="tab-content">
								<div class="tab-pane active show"  id="sub-tab_{{ $lang->key }}-inbox" role="tabpanel">
									<div class="row">
										<div class="col-12">
											<span class="form-control"> {{ $gb->{'text_inbox_' . $lang->key} }} </span>
											{{-- {{ Form::textarea('gb_inbox_'  . $lang->key, $gb->{'text_inbox_' . $lang->key} ?? null, ['class' => 'tinymce']) }} --}}
										</div>
									</div>
								</div>
								<div class="tab-pane"  id="sub-tab_{{ $lang->key }}-outbox" role="tabpanel">
									<div class="row">
										<div class="col-12">
											<span class="form-control"> {{ $gb->{'text_outbox_' . $lang->key} }} </span>
											{{-- {{ Form::textarea('gb_outbox_'  . $lang->key, $gb->{'text_outbox_' . $lang->key} ?? null, ['class' => 'tinymce']) }} --}}
										</div>
									</div>
								</div>
							</div>

						</div>
					@endforeach
				</div>



			</form>
		</div>

		<div class="card-footer position-relative">
			<i class="fa fa-align-justify"></i> Просмотр обращения от <b>[{{ $gb->surname }} {{ $gb->name }}]</b>
			<div class="card-actions">
				<a href="{{ route('admingb::sections.gb.index', [ 'id' => $id ]) }}" class="btn btn-default pl-3 pr-3" style="width: 70px;" title="Назад"><i class="fa fa-arrow-left"></i></a>
			</div>
		</div>
	</div>
@endsection
