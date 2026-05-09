<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Schedules extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
    ];
    protected $hidden = [
        'updated_at',
        'created_at',
    ];
    
    public function user() : BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }
    public function detail() : HasOne {
        return $this->hasOne(ScheduleDetails::class,'schedule_id');
    }
}
