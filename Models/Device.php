<?php

namespace YuraDev\NotifyDispatcher\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Device
 *
 * @property int $id
 * @property string $token
 * @property int $deviceble_id
 * @property string $deviceble_type
 * @property string $device_type
 * @property \Carbon\Carbon $last_activity_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @property \Illuminate\Database\Eloquent\Model $deviceable
 */
class Device extends Model
{
    use SoftDeletes;
//    use DateSerializer;

    const ID = 'id';
    const TOKEN = 'token';
    const DEVICEBLE_ID = 'deviceble_id';
    const DEVICEBLE_TYPE = 'deviceble_type';
    const DEVICE_TYPE = 'device_type';
    const LAST_ACTIVITY_AT = 'last_activity_at';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $table = 'devices';

    public function deviceable()
    {
        return $this->morphTo();
    }

    protected $casts = [
        self::ID => 'int',
        self::DEVICEBLE_ID => 'int',
        self::LAST_ACTIVITY_AT => 'datetime',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime',
    ];

    protected $fillable = [
        self::TOKEN,
        self::DEVICEBLE_ID,
        self::DEVICEBLE_TYPE,
        self::DEVICE_TYPE,
        self::LAST_ACTIVITY_AT,
    ];

    protected $dates = [
        self::LAST_ACTIVITY_AT,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::DELETED_AT,
    ];

    protected $hidden = [
        self::TOKEN,
    ];


}
