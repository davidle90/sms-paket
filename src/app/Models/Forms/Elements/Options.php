<?php namespace Rocketlabs\Forms\App\Models\Forms\Elements;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{

	protected $relations = [
		'elements'	=> 'Rocketlabs\Forms\App\Models\Forms\Elements',
		'options'	=> 'Rocketlabs\Forms\App\Models\Forms\Elements\Options',
	];

	protected $fillable = [
		'element_id',
		'label',
		'other'
	];

    /*
     * The database table used by the model.
     */
    protected $table = 'forms_elements_options';


	public function response()
	{
		return $this->belongsTo($this->relations['elements'],'id', 'element_id');
	}

	function scopeOther($query, $type = 1)
	{
		return $query->where('other',$type);
	}

}
