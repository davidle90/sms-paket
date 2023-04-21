<?php namespace Rocketlabs\Sms\App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_sms.tables.queue');
    }

    protected $fillable = [
        'message_id',
        'priority',
        'sender_title',
        'receiver_title',
        'receiver_phone',
        'country',
        'quantity',
    ];

    protected $dates = [
        'sent_at',
        'created_at',
        'updated_at'
    ];

}

