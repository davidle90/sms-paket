<?php namespace Rocketlabs\Forms\App\Models;

use Illuminate\Database\Eloquent\Model;

class Responses extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_forms.tables.forms_responses');
    }

    protected $fillable = [
        'form_id',
        'iso',
        'sourceable'
    ];

    public function form()
    {
        return $this->belongsTo(config('rl_forms.models.forms'), 'form_id', 'id');
    }

    public function data()
    {
        return $this->hasMany(config('rl_forms.models.forms_responses_data'), 'response_id', 'id');
    }
}
