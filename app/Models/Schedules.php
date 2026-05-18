<?php

namespace App\Models;

use App\Enums\ScheduleEnums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Schedules extends Model
{
    //
    protected $table = 'schedules';
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'status'
    ];
    protected $hidden = [
        'updated_at',
        'created_at',
    ];
    
    protected $casts = [
        'start_datetime'=>'datetime',
        'end_datetime'=>'datetime',
        'status'=> ScheduleEnums::class,
    ];
    
    public function user() : BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }
}
