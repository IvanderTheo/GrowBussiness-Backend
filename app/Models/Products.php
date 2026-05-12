<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Products extends Model
{
    //
    protected $table = 'products';
    protected $fillable = [
        'user_id',
        'category_id',
        'product_name',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function user() : BelongsTo {
        return $this->belongsTo(User::class,'user_id');
    }
    public function category() : BelongsTo {
        return $this->belongsTo(ProductCategory::class,'category_id');
    }
    public function hppCalculation() : HasOne {
        return $this->hasOne(HppCalculation::class,'product_id','id');
    }
}
