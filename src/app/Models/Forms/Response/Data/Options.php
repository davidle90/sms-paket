<?php namespace App\Models\Forms\Response\Data;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{

	protected $relations = [
		'data'				=> 'App\Models\Forms\Response\Data',
		'element_options'	=> 'App\Models\Forms\Elements\Options',
	];


    protected $fillable = [
		'response_data_id',
		'option_id'
	];

    /*
     * The database table used by the model.
     */
    protected $table = 'forms_response_data_options';


	public function data()
	{
		return $this->belongsTo($this->relations['data'],'id', 'response_data_id');
	}

	public function option()
	{
		return $this->belongsTo($this->relations['element_options'],'option_id', 'id');
	}

}
