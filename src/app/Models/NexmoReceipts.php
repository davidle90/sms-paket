<?php namespace Rocketlabs\Sms\App\Models;

use Illuminate\Database\Eloquent\Model;

class NexmoReceipts extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_sms.tables.nexmo_receipts');
    }

    protected $fillable = [
        'message_id',
        'message_timestamp',
        'msisdn',
        'scts',
        'price',
        'network',
        'status',
        'error_code'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function response() {
        return $this->belongsTo(config('rl_sms.models.nexmo_responses'), 'message_id', 'message_id');
    }

}

