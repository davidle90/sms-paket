<?php namespace Rocketlabs\Sms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Rocketlabs\Languages\App\Traits\Translatable;

class Senders extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_sms.tables.senders');
    }

    protected $fillable = [
        'name',
        'sms_label',
        'slug',
        'description'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

}

