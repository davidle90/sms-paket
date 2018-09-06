<?php namespace Rocketlabs\Forms\App\Models\Forms;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{

	protected $relations = [
		'form'		    =>	'Rocketlabs\Forms\App\Models\Forms',
		'data'		    =>	'Rocketlabs\Forms\App\Models\Forms\Response\Data',
	];

    protected $fillable = [
		'form_id',
	];

    /*
     * The database table used by the model.
     */
    protected $table = 'forms_response';

	public function form()
	{
		return $this->belongsTo($this->relations['form'],'form_id', 'id');
	}

	public function data()
	{
		return $this->hasMany($this->relations['data'],'response_id', 'id');
	}

}
