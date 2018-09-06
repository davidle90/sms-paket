<?php namespace Rocketlabs\Forms\App\Models\Forms\Lists;

use Illuminate\Database\Eloquent\Model;

class Elements extends Model
{
	
	protected $fillable = [
		'label',
		'description',
		'template',
		'enabled',
		'sort_order',
	];

    /*
     * The database table used by the model.
     */
    protected $table = 'list_forms_elements';


	public function scopeEnabled($query, $type = 1)
	{
		return $query->where('enabled', $type);
	}
}
