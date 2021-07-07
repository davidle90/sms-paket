<?php namespace Rocketlabs\Forms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Rocketlabs\Languages\App\Traits\Translatable;

class Forms extends Model
{
    use Translatable;

    protected $with = ['translations'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_forms.tables.forms');
    }

    protected $fillable = [
        'slug'
    ];

    public $translatable = [
        'label'
    ];

    public function sections()
    {
        return $this->hasMany(config('rl_forms.models.forms_sections'), 'form_id', 'id');
    }

    public function responses()
    {
        return $this->hasMany(config('rl_forms.models.forms_responses'), 'form_id', 'id');
    }

    public function sourceable()
    {
        return $this->belongsTo(config('rl_forms.models.forms_sourceable'), 'id', 'form_id');
    }

}

