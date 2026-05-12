<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HppFixedCost extends Model
{
    //
    protected $table = 'hpp_fixed_costs';
    protected $fillable = [
        'calculation_id',
        'cost_name',
        'total_monthly_cost',
        'allocated_cost_per_product',
    ];
    protected $hidden = [
        'updated_at',
        'created_at'
    ];
    public function calculation() : BelongsTo {
        return $this->belongsTo(HppCalculation::class,'calculation_id');
    }
}
