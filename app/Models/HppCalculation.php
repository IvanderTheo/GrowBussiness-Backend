<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HppCalculation extends Model
{
    //
    protected $table = 'hpp_calculations';
    protected $fillable = [
        'product_id',
        'total_variable_cost',
        'total_fixed_cost_allocation',
        'total_hpp',
        'hpp_per_products',
    ];
    protected $hidden = [
        'updated_at',
        'created_at'
    ];
    public function product() : BelongsTo {
        return $this->belongsTo(Products::class,'product_id');
    }
    public function variable() : HasMany {
        return $this->hasMany(HppVariableCost::class,'calculation_id','id');
    }
    public function fixed() : HasMany {
        return $this->hasMany(HppFixedCost::class,'calculation_id','id');
    }
    public function result() : HasOne {
        return $this->hasOne(HppResult::class,'calculation_id','id');
    }
}
