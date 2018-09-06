<?php namespace Rocketlabs\Forms\App\Models;

use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{

	protected $relations = [
		'responses'					=>	'Rocketlabs\Forms\App\Models\Forms\Response',
		'elements'					=>	'Rocketlabs\Forms\App\Models\Forms\Elements',
		'sections'					=>	'Rocketlabs\Forms\App\Models\Forms\Sections',
	];

    protected $fillable = [
		'label'
	];

    /*
     * The database table used by the model.
     */
    protected $table = 'forms';

	public function responses()
	{
		return $this->hasMany($this->relations['responses'],'form_id', 'id');
	}

	public function elements()
	{
		return $this->hasMany($this->relations['elements'],'form_id','id');
	}

	public function sections()
	{
		return $this->hasMany($this->relations['sections'],'form_id','id');
	}

    public function tickets()
    {
        //return $this->belongsToMany();
    }

}
