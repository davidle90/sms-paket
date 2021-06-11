<?php namespace Rocketlabs\Forms\App\Models\Forms;

use Illuminate\Database\Eloquent\Model;

class Sourceable extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_forms.tables.forms_sourceable');
    }

    protected $fillable = [
        'form_id',
        'sourceable'
    ];

    public function sourceable()
    {
        return $this->morphTo();
    }

    public function form()
    {
        return $this->hasOne(config('rl_forms.models.forms'), 'id', 'form_id');
    }

}