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
    protected $appends = [
        'current_status'
    ];
    
    public function user() : BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }

    //update status
    public function getCurrentStatusAttribute()
    {
        $now = now();

        // status manual khusus
        if ($this->status === ScheduleEnums::Cancelled) {
            return ScheduleEnums::Cancelled->value;
        }

        // belum mulai
        if ($now->lt($this->start_datetime)) {
            return ScheduleEnums::Pending->value;
        }

        // jika ada end_datetime dan sudah lewat
        if (
            $this->end_datetime &&
            $now->gt($this->end_datetime)
        ) {
            return ScheduleEnums::Completed->value;
        }

        return ScheduleEnums::Ongoing->value;
    }
}
