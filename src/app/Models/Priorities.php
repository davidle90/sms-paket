<?php namespace Rocketlabs\Sms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Rocketlabs\Languages\App\Traits\Translatable;

class Priorities extends Model
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
        return config('rl_sms.tables.priorities');
    }

    protected $fillable = [
        'priority',
        'slug'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public $translatable = [
        'label'
    ];

}

