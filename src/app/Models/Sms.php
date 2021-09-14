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

    protected $casts = [
        'variables' => 'array'
    ];

    protected $fillable = [
        'nexmo_id',
        'sender_title',
        'sender_phone',
        'receiver_title',
        'receiver_phone',
        'country',
        'quantity',
        'sent_at',
        'sms_unit_price',
        'vat_rate',
        'price_incl_vat',
        'price_excl_vat',
        'price_vat',
    ];

    protected $dates = [
        'sent_at',
        'created_at',
        'updated_at'
    ];

    public function nexmo() {
        return $this->hasMany(config('rl_sms.models.nexmo_responses'), 'sms_id', 'id');
    }

    public function message() {
        return $this->belongsTo(config('rl_sms.models.messages'), 'message_id', 'id');
    }

}

