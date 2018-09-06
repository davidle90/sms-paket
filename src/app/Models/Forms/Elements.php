<?php namespace Rocketlabs\Forms\App\Models\Forms;

use Illuminate\Database\Eloquent\Model;

class Elements extends Model
{

	protected $relations = [
		'form'			=>	'Rocketlabs\Forms\App\Models\Forms',
		'options'		=>	'Rocketlabs\Forms\App\Models\Forms\Elements\Options',
		'listElements'	=>	'Rocketlabs\Forms\App\Models\Forms\Lists\Elements',
		'data'			=>  'Rocketlabs\Forms\App\Models\Forms\Response\Data',
	];

    protected $fillable = [
		'form_id',
		'list_element_id',
		'section_id',
		'help_text',
		'required_text',
		'attr_required',
		'attr_disabled',
		'attr_readonly',
		'attr_novalidate',
		'attr_autocomplete',
		'hidden',
		'default_value',
		'sort_order',
	];

    /*
     * The database table used by the model.
     */
    protected $table = 'forms_elements';

	public function form()
	{
		return $this->belongsTo($this->relations['form'],'form_id', 'id');
	}

	public function options()
	{
		return $this->hasMany($this->relations['options'],'element_id','id');
	}

	public function template()
	{
		return $this->hasOne($this->relations['listElements'],'id','list_element_id');
	}


	/*
	 * Get all data for all the element fields
	 * */
	public function data()
	{
		return $this->hasMany($this->relations['data'],'column_id', 'id');
	}
}
