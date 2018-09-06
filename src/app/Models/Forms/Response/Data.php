<?php namespace App\Models\Forms\Response;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{

	/*
	protected $relations = [
		'response'	=>	App\Models\HealthSurveys\Response::class,
		'data'		=>	App\Models\HealthSurveys\Response\Data::class,
	];
	*/

	protected $relations = [
		'response'	=> 'App\Models\Forms\Response',
		'options'	=> 'App\Models\Forms\Response\Data\Options',
	];



	protected $fillable = [
		'response_id',
		'column_id',
		'value'
	];

    /*
     * The database table used by the model.
     */
    protected $table = 'forms_response_data';


	public function response()
	{
		return $this->belongsTo($this->relations['response'],'id', 'response_id');
	}

	public function options()
	{
		return $this->hasMany($this->relations['options'],'response_data_id', 'id');
	}
}
