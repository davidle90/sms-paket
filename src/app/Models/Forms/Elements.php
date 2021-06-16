<?php namespace Rocketlabs\Forms\App\Models\Forms;

use Illuminate\Database\Eloquent\Model;
use Rocketlabs\Languages\App\Traits\Translatable;

class Elements extends Model
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
        return config('rl_forms.tables.forms_elements');
    }

    protected $fillable = [
        'slug',
        'type_id',
        'validator',
        'table_id',
        'options_id'
    ];

    public $translatable = [
        'label',
        'description',
        'required'
    ];

    public function sections()
    {
        return $this->belongsToMany(config('rl_forms.models.forms_sections'), config('rl_forms.tables.forms_sections_elements'),'element_id','section_id')
            ->withPivot('required', 'sort_order', 'size', 'size_class')
            ->orderBy(config('rl_forms.tables.forms_sections_elements').'.sort_order', 'asc')
            ->withTimestamps();
    }

    public function type() {
        return $this->belongsTo(config('rl_forms.models.forms_elements_types'), 'type_id', 'id');
    }

    public function table() {
        return $this->hasOne(config('rl_tables.models.tables'), 'id', 'table_id');
    }

    public function options() {
        return $this->hasMany(config('rl_forms.models.forms_elements_options'), 'id', 'options_id');
    }

    public function data()
    {
        return $this->hasMany(config('rl_forms.models.forms_responses_data'), 'element_id', 'id');
    }

}
