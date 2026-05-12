<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HppPriceRecommendation extends Model
{
    //
    protected $table = 'hpp_price_recommendations';
    protected $fillable = [
        'result_id',
        'category',
        'selling_price',
        'profit_amount',
        'profit_margin_precentage',
    ];
    protected $hidden = [
        'updated_at',
        'created_at'
    ];
    public function result() : BelongsTo {
        return $this->belongsTo(HppResult::class,'result_id');
    }
}
