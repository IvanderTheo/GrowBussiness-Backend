<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HppResult extends Model
{
    //
    protected $table = 'hpp_results';
    protected $fillable = [
        'calculation_id',
        'variable_cost_per_product',
        'fixed_cost_per_product',
        'total_hpp_per_product'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function calculation() : BelongsTo {
        return $this->belongsTo(HppCalculation::class,'calculation_id');
    }
    public function recommendation() : HasOne {
        return $this->hasOne(HppPriceRecommendation::class,'result_id','id');
    }
}
