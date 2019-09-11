<?php namespace Avl\AdminGb\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;
use LaravelLocalization;

class Gbs extends Model
{
		use ModelTrait;

		protected $table = 'gbs';

		protected $modelName = __CLASS__;

		protected $lang = null;

		public function theme ()
		{
			return $this->belongsTo('App\Models\Rubrics', 'theme_id', 'id');
		}
}
