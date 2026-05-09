<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumComments extends Model
{
    //
    protected $fillable = [
        'user_id',
        'forum_id',
        'comment',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function forum() : BelongsTo {
        return $this->belongsTo(Forum::class,'forum_id');
    }
    public function user() : BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }
}
