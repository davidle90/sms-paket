<?php namespace Rocketlabs\Forms\App\Models\Responses;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $with = [
        'sourceable'
    ];

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
        'sourceable_type',
        'sourceable_id'
    ];

    public function response()
    {
        return $this->belongsTo(config('rl_forms.models.forms_responses'), 'response_id', 'id');
    }

    public function element()
    {
        return $this->belongsTo(config('rl_forms.models.forms_elements'), 'element_id', 'id');
    }

    public function sourceable()
    {
        return $this->morphTo();
    }

    public function scopeVersionData($query, $response_ids)
    {
        return $query->whereIn('response_id', $response_ids);
    }
}
