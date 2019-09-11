@extends('avl.default')

@section('js')
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
			<i class="fa fa-align-justify"></i> Добавление обращения
			<div class="card-actions">
				<a href="{{ route('admingb::sections.gb.index', [ 'id' => $id ]) }}" class="btn btn-default pl-3 pr-3" style="width: 70px;" title="Назад"><i class="fa fa-arrow-left"></i></a>
				<button type="submit" form="submit" name="button" value="add" class="btn btn-primary pl-3 pr-3" style="width: 70px;" title="Сохранить и добавить новое"><i class="fa fa-plus"></i></button>
				<button type="submit" form="submit" name="button" value="save" class="btn btn-success pl-3 pr-3" style="width: 70px;" title="Сохранить и перейти к списку"><i class="fa fa-floppy-o"></i></button>
				<button type="submit" form="submit" name="button" value="edit" class="btn btn-warning pl-3 pr-3" style="width: 70px;" title="Сохранить и изменить"><i class="fa fa-floppy-o"></i></button>
			</div>
		</div>

		<div class="card-body">
			<form action="{{ route('admingb::sections.gb.store', ['id' => $id]) }}" method="post" id="submit">
				{!! csrf_field(); !!}

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
									{{ Form::text('gb_updated_date', date('Y-m-d'), ['class' => 'form-control datepicker', 'id' => '']) }}
								</div>
							</div>
							<div class="col-12 col-sm-3">
								<div class="form-group">
									{{ Form::label(null, 'Время обновления') }}
									{{ Form::text('gb_updated_time', date('H:i'), ['class' => 'form-control timepicker']) }}
								</div>
							</div>
							@if ($section->rubric == 1)
								<div class="col-12 col-sm-6">
									<div class="form-group">
										<label for="gb_theme_id">Тема</label>
										<select class="form-control" name="gb_theme_id">
											<option value="0">---</option>
											@if (!is_null($rubrics))
												@foreach ($rubrics as $rubric)
													<option value="{{ $rubric->id }}" @if(old('gb_theme_id') == $rubric->id){{ 'selected' }}@endif>{{ !is_null($rubric->title_ru) ? $rubric->title_ru : str_limit(strip_tags($rubric->description_ru), 100) }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
							@endif
						</div>

						<div class="row">
							<div class="col-1">
								<div class="form-group">
									<label>Вкл / Выкл</label><br/>
									<label class="switch switch-3d switch-primary">
										<input name='gb_good' type='hidden' value='0'>
										<input type="checkbox" class="switch-input" name="gb_good" value="1" @if (old('gb_good') == 1) checked @endif>
										<span class="switch-label"></span>
										<span class="switch-handle"></span>
									</label>
								</div>
							</div>
							<div class="col-6">
								<div class="form-group">
									{{ Form::label(null, 'Фамилия') }}
									{{ Form::text('gb_surname', null, ['class' => 'form-control']) }}
								</div>
							</div>
							<div class="col-5">
								<div class="form-group">
									{{ Form::label(null, 'Имя') }}
									{{ Form::text('gb_name', null, ['class' => 'form-control']) }}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<div class="form-group">
									{{ Form::label(null, 'Адрес') }}
									{{ Form::text('gb_address', null, ['class' => 'form-control']) }}
								</div>
							</div>
							<div class="col-4">
								<div class="form-group">
									{{ Form::label(null, 'Район или индекс') }}
									{{ Form::text('gb_district_or_index', null, ['class' => 'form-control']) }}
								</div>
							</div>
							<div class="col-4">
								<div class="form-group">
									{{ Form::label(null, 'Страна') }}
									<select class="form-control" name="gb_country_id">
										<option value="0">---</option>
										@if (!is_null($countries))
											@foreach ($countries as $country)
												<option value="{{ $country->id }}" @if(old('gb_country_id') == $country->id){{ 'selected' }}@endif>{{ $country->title_ru }}</option>
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
									{{ Form::text('gb_email', null, ['class' => 'form-control']) }}
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									{{ Form::label(null, 'Контактный телефон') }}
									{{ Form::text('gb_contact_phone', null, ['class' => 'form-control']) }}
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									{{ Form::label(null, 'Должность') }}
									{{ Form::text('gb_position', null, ['class' => 'form-control']) }}
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									{{ Form::label(null, 'Организация') }}
									{{ Form::text('gb_organization', null, ['class' => 'form-control']) }}
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
											{{ Form::textarea('gb_inbox_'  . $lang->key, null, ['class' => 'tinymce']) }}
										</div>
									</div>
								</div>
								<div class="tab-pane"  id="sub-tab_{{ $lang->key }}-outbox" role="tabpanel">
									<div class="row">
										<div class="col-12">
											{{ Form::textarea('gb_outbox_'  . $lang->key, null, ['class' => 'tinymce']) }}
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
			<i class="fa fa-align-justify"></i> Добавление обращения
			<div class="card-actions">
				<a href="{{ route('admingb::sections.gb.index', [ 'id' => $id ]) }}" class="btn btn-default pl-3 pr-3" style="width: 70px;" title="Назад"><i class="fa fa-arrow-left"></i></a>
				<button type="submit" form="submit" name="button" value="add" class="btn btn-primary pl-3 pr-3" style="width: 70px;" title="Сохранить и добавить новое"><i class="fa fa-plus"></i></button>
				<button type="submit" form="submit" name="button" value="save" class="btn btn-success pl-3 pr-3" style="width: 70px;" title="Сохранить и перейти к списку"><i class="fa fa-floppy-o"></i></button>
				<button type="submit" form="submit" name="button" value="edit" class="btn btn-warning pl-3 pr-3" style="width: 70px;" title="Сохранить и изменить"><i class="fa fa-floppy-o"></i></button>
			</div>
		</div>
	</div>
@endsection
