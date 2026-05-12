<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    //
    protected $table = 'product_categories';
    protected $fillable = [
        'name',
        'description'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function productCategory(): HasMany {
        return $this->hasMany(Products::class,'category_id','id');
    }
}
