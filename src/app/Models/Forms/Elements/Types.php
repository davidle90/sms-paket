<?php namespace Rocketlabs\Forms\App\Models\Forms\Elements;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_forms.tables.forms_elements_types');
    }

    protected $fillable = [
        'label',
        'description',
        'slug',
        'sort_order',
        'enabled',
        'data_type'
    ];

    public function elements() {
        return $this->hasMany(config('rl_forms.models.forms_elements'), 'type_id', 'id');
    }

}
