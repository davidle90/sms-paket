<?php namespace Rocketlabs\Sms\App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerStatus extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_sms.tables.server_status');
    }

    protected $fillable = [
        'status'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

}

