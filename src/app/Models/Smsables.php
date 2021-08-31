<?php namespace Rocketlabs\Sms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Rocketlabs\Languages\App\Traits\Translatable;

class Smsables extends Model
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
        return config('rl_sms.tables.smsables');
    }

    protected $fillable = [
        'sourceable_type',
        'source',
        'number_columns',
        'search_fields',
        'critera'
    ];

    public $translatable = [
        'label',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

}

