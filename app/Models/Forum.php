<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Forum extends Model
{
    //
    protected $table = 'forums';
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
    ];
    protected $hidden = [
        'updated_at'
    ];
    public function category(): BelongsTo {
        return $this->belongsTo(ForumCategory::class,'category_id');
    }
    public function comments(): HasMany {
        return $this->hasMany(ForumComment::class,'forum_id','id');
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }
}
