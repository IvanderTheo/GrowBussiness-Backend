<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIChatMessages extends Model
{
    //
    protected $fillable = [
        'session_id',
        'sender',
        'message',
        'token',
    ];
    protected $hidden = [
        'created_at',
    ];
    public function session() : BelongsTo {
        return $this->belongsTo(AIChatSessions::class,'session_id');
    }
}
