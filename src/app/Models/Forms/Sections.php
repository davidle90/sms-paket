<?php namespace Rocketlabs\Forms\App\Models\Forms;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{

	protected $relations = [
		'form'			=>	'Rocketlabs\Forms\App\Models\Forms',
		'elements'		=>	'Rocketlabs\Forms\App\Models\Forms\Elements',
		'contact'		=>	'Rocketlabs\Forms\App\Models\Forms\Contact',
	];

    protected $fillable = [
		'form_id',
		'label',
		'sort_order'
	];
	
    /*
     * The database table used by the model.
     */
    protected $table = 'forms_sections';


	public function form()
	{
		return $this->belongsTo($this->relations['form'],'form_id', 'id');
	}

	public function elements()
	{
		return $this->hasMany($this->relations['elements'],'section_id','id');
	}
	
}
