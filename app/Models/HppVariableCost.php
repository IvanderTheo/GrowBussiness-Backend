<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HppVariableCost extends Model
{
    //
    protected $table = 'hpp_variable_costs';
    protected $fillable = [
        'calculation_id',
        'material_name',
        'usage_amount',
        'usage_unit',
        'purchase_total_price',
        'purchase_quantity',
        'purchase_unit',
        'cost_per_producuts',
        'cost_per_product'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function calculation() : BelongsTo {
        return $this->belongsTo(HppCalculation::class,'calculation_id');
    }
}
