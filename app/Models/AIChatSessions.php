<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AIChatSessions extends Model
{
    //
    protected $table = 'ai_chat_sessions';
    protected $fillable = [
        'user_id',
        'title',
    ];
    protected $hidden = [
        'updated_at',
        'created_at',
    ];
    public function messages() : HasMany {
        return $this->hasMany(AIChatMessages::class,'session_id','id');
    }
    protected function user() :BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }
}
