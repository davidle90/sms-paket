<?php namespace Rocketlabs\Sms\App\Models;

use Illuminate\Database\Eloquent\Model;

class NexmoResponses extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_sms.tables.nexmo_responses');
    }

    protected $fillable = [
        'sms_id',
        'nexmo_id',
        'status',
        'to',
        'balance',
        'price',
        'network',
        'error_message',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function sms() {
        return $this->belongsTo(config('rl_sms.models.sms'), 'sms_id', 'id');
    }

}

