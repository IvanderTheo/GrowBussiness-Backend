<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumCategory extends Model
{
    //
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    public function forums():HasMany {
        return $this->hasMany(Forum::class,'category_id','id');
    }
}
