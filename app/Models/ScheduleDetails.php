<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleDetails extends Model
{
    //
    protected $table = 'schedule_details';
    protected $fillable = [
        'start_datetime',
        'end_datetime',
        'location',
        'status',
    ];
    protected $hidden = [
        'updated_at',
        'created_at',
    ];
    protected $casts = [
        'start_datetime'=>'datetime',
        'end_datetime'=>'datetime',
        'notification'=>'boolean'
    ];
    public function schedule() : BelongsTo {
        return $this->belongsTo(Schedules::class,'schedule_id');
    }
}
