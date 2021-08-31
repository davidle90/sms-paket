<?php namespace Rocketlabs\Sms\App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_sms.tables.messages');
    }

    protected $fillable = [
        'text',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function sms() {
        return $this->hasMany(config('rl_sms.models.sms'), 'message_id', 'id');
    }

}

