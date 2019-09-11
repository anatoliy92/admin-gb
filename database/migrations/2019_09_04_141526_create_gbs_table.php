<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Langs;

class CreateGbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gbs', function (Blueprint $table) {
            $table->bigIncrements('id');
						$table->integer('good')->default(0)->comment('Видимость');
						$table->integer('section_id')->nullable()->comment('Номер раздела');
						$table->string('name')->nullable()->comment('Имя');
						$table->string('surname')->nullable()->comment('Фамилия');
						$table->integer('theme_id')->default(0)->comment('Тема');

						$langs = Langs::all();

						foreach ($langs as $lang) { 	$table->mediumText('text_inbox_' . $lang->key)->nullable()->comment('Текст входящего сообщения'); }
						foreach ($langs as $lang) { 	$table->mediumText('text_outbox_' . $lang->key)->nullable()->comment('Текст исходящего сообщения'); }

						$table->string('position')->nullable()->comment('Должность');
						$table->string('organization')->nullable()->comment('Организация');
						$table->string('address')->nullable()->comment('Адрес');
						$table->string('disctrict_or_index')->nullable()->comment('Район или индекс');
						$table->integer('country_id')->default(0)->comment('Страна');
						$table->string('contact_phone')->nullable()->comment('Контактный телефон');
						$table->string('email')->nullable()->comment('E-mail');
						$table->dateTime('updated_date')->nullable()->comment('Фиксированная дата обновления');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gbs');
    }
}
