<?php namespace Rocketlabs\Forms\App\Models\Forms;

use Illuminate\Database\Eloquent\Model;
use Rocketlabs\Languages\App\Traits\Translatable;

class Sections extends Model
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
        return config('rl_forms.tables.forms_sections');
    }

    protected $fillable = [
        'form_id',
        'sort_order'
    ];

    public $translatable = [
        'label',
        'description'
    ];

    public function elements()
    {
        return $this->belongsToMany(config('rl_forms.models.forms_elements'), config('rl_forms.tables.forms_sections_elements'),'section_id','element_id')
            ->withPivot('required', 'sort_order', 'size', 'size_class')
            ->orderBy(config('rl_forms.tables.forms_sections_elements').'.sort_order', 'asc')
            ->withTimestamps();
    }

    public function form()
    {
        return $this->belongsTo(config('rl_forms.models.forms'), 'form_id', 'id');
    }

}
