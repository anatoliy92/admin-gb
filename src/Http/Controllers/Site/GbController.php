<?php namespace Avl\AdminGb\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Site\Sections\SectionsController;
use App\Models\{
	Langs, Sections, Rubrics
};
use Avl\AdminGb\Models\Gbs;
use Cache;
use View;
use Api;
use Mail;

class GbController extends SectionsController
{
	public function index ()
	{
		$template = 'site.templates.gb.short.' . $this->getTemplateFileName($this->section->current_template->file_short);

		$records = $this->section->gb()->whereGood(1)->orderBy('created_at', 'desc');

		return view($template, [
			'countries' =>getManualItems('country_list'),
			'rubrics' => $this->section->rubrics()->orderBy('published_at', 'DESC')->get(),
			'records' => $records->paginate() ?? null,
			'section' => $this->section
		]);
	}

	public function store (Request $request)
	{

		$this->validate(request(), [
				'surname' => 'required',
				'name' => 'required',
				'theme_id' => 'required',
				'text_inbox_'.$request->input('lang') => 'required',
				'address' => 'required',
				'email' => 'required|email'
			], [
				'surname.required' => 'Фамилия не заполнена',
				'name.required' => 'Имя не заполнено',
				'theme_id.required' => 'Тема не выбрана',
				'address.required' => 'Адресс не заполнен',
				'email.required' => 'E-mail не заполнен'
			]);


		$response = Api::request('POST', 'api/gb', [
			"section_id" => $request->input("section_id"),
			"surname" => $request->input("surname"),
			"name" => $request->input("name"),
			"theme_id" => $request->input("theme_id"),
			"text_inbox_".$request->input('lang') => $request->input("text_inbox_".$request->input('lang')),
			"position" => $request->input("position"),
			"organization" => $request->input("organization"),
			"address" => $request->input("address"),
			"disctrict_or_index" => $request->input("disctrict_or_index"),
			"country_id" => $request->input("country_id"),
			"contact_phone" => preg_replace('~[^0-9]+~', '', $request->input('contact_phone')),
			"email" => $request->input("email"),
		]);

		if ($response->id) {
			Mail::send('admingb::emails.user', ['response' => $response, 'section' => $this->section], function ($message) use ($response) {
					$message->from(config('admingb.emailFrom'), config('admingb.emailFromName'));
					$message->subject('Вопрос-ответ - Национальный Банк Республики Казахстан');
					$message->to($response->email);
			});

			Mail::send('admingb::emails.admin', ['response' => $response, 'section' => $this->section], function ($message) use ($response) {
					$message->from(config('admingb.emailFrom'), config('admingb.emailFromName'));
					$message->subject('Вопрос-ответ - Национальный Банк Республики Казахстан');
					$message->to('vladimir@ir.kz');
			});

				return redirect()->back()->with(['success' => 'Ваш вопрос успешно отправлен']);
		}

		return redirect()->back()->with(['errors' => 'Произошла внутренняя ошибка системы. Попробуйте позднее.']);






		// $this->validate(request(), [
		// 	'name' => 'required|max:255',
		// 	'email' => 'required|email',
		// 	'city' => '',
		// 	'theme' => 'required|max:255',
		// 	'message' => 'required|min:10|max:300',
		// ], trans('gb'));
		//
		// $gb = new Gbs();
		//
		// $gb->name = $request->input('name') ?? null;
		// $gb->email = $request->input('email') ?? null;
		// $gb->city = $request->input('city') ?? null;
		// $gb->theme = $request->input('theme') ?? null;
		// $gb->message = $request->input('message') ?? null;
		//
		// if ($gb->save()) {
		//
		// 	Mail::send('site.emails.gb.user', ['gb' => $gb, 'section' => $this->section], function ($message) use ($gb) {
		// 			$message->from(env('MAIL_USERNAME'), 'no-reply');
		// 			$message->subject('Уведомление');
		// 			$message->to($gb->email);
		// 	});
		//
		// 	Mail::send('site.emails.gb.admin', ['gb' => $gb, 'section' => $this->section], function ($message) use ($gb) {
		// 			$message->from(env('MAIL_USERNAME'), 'no-reply');
		// 			$message->subject('Новый вопрос в раздел - ' . $this->section->name);
		// 			$message->to('anatoliy@ir.kz');
		// 	});
		//
		// 	return redirect()->route('site.gb.index', ['alias' => $this->section->alias])->with(['success' => 'Ваш вопрос успешно отправлен']);
		// }
		//
		// return redirect()->back()->with(['errors' => 'Произошла внутренняя ошибка системы. Попробуйте позднее.']);
	}
}
