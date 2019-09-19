<?php namespace Avl\AdminGb\Controllers\Admin;

	use App\Http\Controllers\Avl\AvlController;
	use Avl\AdminGb\Models\Gbs;
	use Illuminate\Http\Request;
	use App\Models\{
		Langs, Sections, Rubrics
	};
	use Avl\AdminManuals\Models\Manuals;
	use View;

class GbController extends AvlController
{
	protected $accessModel = null;

	public function __construct (Request $request) {
		parent::__construct($request);

		$this->accessModel = new Gbs();

		$this->langs = Langs::get();

		View::share('accessModel', $this->accessModel);
	}

	/**
	 * Страница вывода обращений
	 * @param  Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function index($id, Request $request)
	{
		$this->authorize('view', Sections::findOrFail($id));

		$section = Sections::whereId($id)->firstOrFail();

		$gbs = Gbs::where('section_id', $id)->get();

		return view('admingb::gb.index', [
			'id' => $id,
			'section' => $section,
			'gbs' => $this->getQuery($gbs, $request),
			'request' => $request,
			'rubrics' => array_add(toSelectTransform(Rubrics::select('id', 'title_ru')->where('section_id', $section->id)->get()->toArray()), 0, 'Обращения без рубрики'),
		]);
	}

	/**
	 * Вывод формы на добавление обращений
	 * @param  int $id     Номер раздела
	 * @return [type]     [description]
	 */
	public function create($id)
	{
			$section = Sections::whereId($id)->firstOrFail();

			$this->authorize('create', $section);

			return view('admingb::gb.create', [
					'langs' => $this->langs,
					'section' => $section,
					'countries' =>getManualItems('country_list'),
					'id' => $id,
					'rubrics' => $section->rubrics()->orderBy('published_at', 'DESC')->get(),
			]);
	}

	/**
	 * Метод для добавления нового обращения в базу
	 * @param  Request $request
	 * @param  int  $id      номер раздела
	 * @return redirect to index or create method
	 */
	public function store(Request $request, $id)
	{
		$this->authorize('create', Sections::findOrFail($id));

		$post = $request->input();

		$this->validate(request(), [
				'button' => 'required|in:add,save,edit',
				'gb_theme_id' => 'sometimes',
				'gb_good' => '',
				'gb_surname' => 'max:255',
				'gb_name' => 'max:255',
				'gb_address' => 'max:255',
				'gb_district_or_index' => 'max:255',
				'gb_country_id' => '',
				'gb_email' => 'max:255',
				'gb_contact_phone' => 'max:255',
				'gb_position' => 'max:255',
				'gb_organization' => 'max:255',
				'gb_inbox_ru' => '',
				'gb_outbox_ru' => '',
				'gb_updated_date' => 'date_format:"Y-m-d"',
				'gb_updated_time' => 'date_format:"H:i"',
				'gb_updated' => ''
		]);

		$record = new Gbs;
		$record->section_id = $id;
		$record->good = $post['gb_good'];
		$record->surname = $post['gb_surname'];
		$record->name = $post['gb_name'];
		$record->address = $post['gb_address'];
		$record->disctrict_or_index = $post['gb_district_or_index'];
		$record->country_id = $post['gb_country_id'];
		$record->email = $post['gb_email'];
		$record->contact_phone = $post['gb_contact_phone'];
		$record->position = $post['gb_position'];
		$record->organization = $post['gb_organization'];
		$record->updated_date = $post['gb_updated_date'] . ' ' . $post['gb_updated_time'];

		foreach ($this->langs as $lang) {
			$record->{'text_inbox_' . $lang->key} = $post['gb_inbox_' . $lang->key];
			$record->{'text_outbox_' . $lang->key} = $post['gb_outbox_' . $lang->key];
		}

		if (isset($post['gb_theme_id']) && ($post['gb_theme_id'] > 0)) {
			$record->theme_id = $post['gb_theme_id'];    // проставляему рубрику если ее выбрали
		}

		if ($record->save()) {
			switch ($post['button']) {
				case 'add': { return redirect()->route('admingb::sections.gb.create', ['id' => $id])->with(['success' => ['Сохранение прошло успешно!']]); }
				case 'edit': { return redirect()->route('admingb::sections.gb.edit', ['id' => $id, 'gb_id' => $record->id])->with(['success' => ['Сохранение прошло успешно!']]); }
				default: { return redirect()->route('admingb::sections.gb.index', ['id' => $id])->with(['success' => ['Сохранение прошло успешно!']]); }
			}
		}

		return redirect()->route('admingb::sections.gb.create', ['id' => $id])->with(['errors' => ['Что-то пошло не так.']]);
	}

	/**
	 * Отобразить запись на просмотр
	 * @param  int $id      Номер раздела
	 * @param  int $gb_id Номер записи
	 * @return \Illuminate\Http\Response
	 */
	public function show($id, $gb_id)
	{
			$this->authorize('view', Sections::findOrFail($id));
			$section = Sections::whereId($id)->firstOrFail();

			return view('admingb::gb.show', [
					'langs' => $this->langs,
					'gb' => Gbs::findOrFail($gb_id),
					'id' => $id,
					'countries' =>getManualItems('country_list'),
					'rubrics' => $section->rubrics()->orderBy('published_at', 'DESC')->get()
			]);
	}

	/**
	 * Форма открытия обращения на редактирование
	 * @param  int $id      Номер раздела
	 * @param  int $gb_id Номер записи
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id, $gb_id)
	{
			$section = Sections::whereId($id)->firstOrFail();

			$this->authorize('update', $section);

			return view('admingb::gb.edit', [
					'gb' => Gbs::findOrFail($gb_id),
					'id' => $id,
					'section' => $section,
					'countries' =>getManualItems('country_list'),
					'rubrics' => $section->rubrics()->orderBy('published_at', 'DESC')->get(),
					'langs' => $this->langs,
			]);
	}

	/**
	 * Метод для обновления обращений
	 * @param  Request $request
	 * @return redirect to index method
	 */
	public function update(Request $request, $id, $gb_id)
	{
		$this->authorize('update', Sections::findOrFail($id));

		$post = $request->input();

		$this->validate(request(), [
				'button' => 'required|in:add,save,edit',
				'gb_theme_id' => 'sometimes',
				'gb_good' => '',
				'gb_surname' => 'max:255',
				'gb_name' => 'max:255',
				'gb_address' => 'max:255',
				'gb_district_or_index' => 'max:255',
				'gb_country_id' => '',
				'gb_email' => 'max:255',
				'gb_contact_phone' => 'max:255',
				'gb_position' => 'max:255',
				'gb_organization' => 'max:255',
				'gb_inbox_ru' => '',
				'gb_outbox_ru' => '',
				'gb_updated_date' => 'date_format:"Y-m-d"',
				'gb_updated_time' => 'date_format:"H:i"',
		]);

		$record = Gbs::findOrFail($gb_id);

		$record->section_id = $id;
		$record->good = $post['gb_good'];
		$record->surname = $post['gb_surname'];
		$record->name = $post['gb_name'];
		$record->address = $post['gb_address'];
		$record->disctrict_or_index = $post['gb_district_or_index'];
		$record->country_id = $post['gb_country_id'];
		$record->email = $post['gb_email'];
		$record->contact_phone = $post['gb_contact_phone'];
		$record->position = $post['gb_position'];
		$record->organization = $post['gb_organization'];
		$record->updated_date = $post['gb_updated_date'] . ' ' . $post['gb_updated_time'];

		foreach ($this->langs as $lang) {
			$record->{'text_inbox_' . $lang->key} = $post['gb_inbox_' . $lang->key];
			$record->{'text_outbox_' . $lang->key} = $post['gb_outbox_' . $lang->key];
		}

		if (isset($post['gb_theme_id']) && ($post['gb_theme_id'] > 0)) {
			$record->theme_id = $post['gb_theme_id'];    // проставляему рубрику если ее выбрали
		}

		if ($record->save()) {
			return redirect()->route('admingb::sections.gb.index', ['id' => $id])->with(['success' => ['Сохранение прошло успешно!']]);
		}
		return redirect()->back()->with(['errors' => ['Что-то пошло не так.']]);
	}

	/**
	 * Удаление обращений
	 * @param  int $id      Номер раздела
	 * @param  int $gb_id Номер записи
	 * @return json
	 */
	public function destroy($id, $gb_id, Request $request)
	{
		$this->authorize('delete', Sections::findOrFail($id));

		$record = Gbs::find($gb_id);

		if (!is_null($record)) {
			if ($record->delete()) { return ['success' => ['Обращение удалено']]; }
		}

		return ['errors' => ['Ошибка удаления.']];
	}

	/**
	 * Функция для формирования фильтра в списке записей
	 * @param  query $query   Eloquent
	 * @param  request $request
	 * @return query
	 */
	private function getQuery ($query, $request)
	{
			if (!is_null($request->input('rubric'))) {
					if ($request->input('rubric') == 0) {
							$query = $query->where('theme_id', 0);
					} else {
							$query = $query->where('theme_id', $request->input('rubric'));
					}
			}
			if (!is_null($request->input('gb_id'))) {
					if ($request->input('gb_id') == 0) {

					} else {
							$query = $query->where('id', $request->input('gb_id'));
					}
			}

			if (!is_null($request->input('email'))) {
					if ($request->input('email') == 0) {

					} else {
							$query = $query->where('email', $request->input('email'));
					}
			}

			return $query;
	}

}
