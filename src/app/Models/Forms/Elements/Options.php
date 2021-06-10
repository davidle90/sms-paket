<?php namespace Rocketlabs\Forms\App\Models\Forms\Elements;

use Illuminate\Database\Eloquent\Model;
use Rocketlabs\Languages\App\Traits\Translatable;

class Options extends Model
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
        return config('rl_forms.tables.forms_elements_options');
    }

    protected $fillable = [
        'element_id'
    ];

    public $translatable = [
        'label'
    ];

    public function element() {
        return $this->belongsTo(config('rl_forms.models.forms_elements'), 'element_id', 'id');
    }

}
