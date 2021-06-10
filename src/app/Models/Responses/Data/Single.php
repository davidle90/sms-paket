<?php namespace Rocketlabs\Forms\App\Models\Responses\Data;

use Illuminate\Database\Eloquent\Model;

class Single extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_forms.tables.forms_responses_data_single');
    }

    protected $fillable = [
        'table_data_id',
        'value'
    ];

    public function table_data()
    {
        return $this->hasOne(config('rl_tables.models.tables_data'), 'id', 'table_data_id');
    }

}
