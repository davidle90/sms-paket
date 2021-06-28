<?php namespace Rocketlabs\Forms\App\Models\Responses\Data;

use Illuminate\Database\Eloquent\Model;

class Text extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_forms.tables.forms_responses_data_text');
    }

    protected $fillable = [
        'value'
    ];

    public function data()
    {
        return $this->morphOne(config('rl_forms.models.forms_responses_data'), 'sourceable');
    }
}
