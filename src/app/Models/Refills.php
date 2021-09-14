<?php namespace Rocketlabs\Sms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Rocketlabs\Languages\App\Traits\Translatable;

class Refills extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public function getTable()
    {
        return config('rl_sms.tables.refills');
    }

    protected $fillable = [
        'quantity',
        'remains',
        'total',
        'count',
        'sms_unit_count',
        'vat_rate',
        'price_incl_vat',
        'price_excl_vat',
        'price_vat'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

}

