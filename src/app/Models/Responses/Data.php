<?php namespace Rocketlabs\Forms\App\Models\Responses;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_forms.tables.forms_responses_data');
    }

    protected $fillable = [
        'slug',
        'response_id',
        'element_id',
        'sourceable'
    ];

    public function response()
    {
        return $this->belongsTo(config('rl_forms.models.forms_responses'), 'response_id', 'id');
    }

    public function element()
    {
        return $this->belongsTo(config('rl_forms.models.forms_elements'), 'element_id', 'id');
    }

}
