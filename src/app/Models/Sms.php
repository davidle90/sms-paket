<?php namespace Rocketlabs\Sms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Rocketlabs\Languages\App\Traits\Translatable;

class Sms extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_sms.tables.sms');
    }

    protected $fillable = [
        'nexmo_id',
        'sender_title',
        'sender_phone',
        'receiver_title',
        'receiver_phone',
        'country',
        'sent_at'
    ];

    protected $dates = [
        'sent_at',
        'created_at',
        'updated_at'
    ];

}

