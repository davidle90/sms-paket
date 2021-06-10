<?php namespace Rocketlabs\Forms\App\Models\Forms\Sections;

use Illuminate\Database\Eloquent\Model;

class Elements extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_forms.tables.forms_sections_elements');
    }

    protected $fillable = [
        'section_id',
        'element_id',
        'required',
        'sort_order',
        'size',
        'size_class'
    ];

}
