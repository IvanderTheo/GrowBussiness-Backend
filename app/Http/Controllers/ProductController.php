<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\ProductCategory;
use App\Services\HPP\ProductModelingService;
use App\Services\HPP\ProductFixedCostService;
use Exception;
use Illuminate\Http\Request;
use App\Models\Products;

class ProductController extends Controller
{
    //
    public function __construct(
        protected ProductModelingService $productModelingService,
        protected ProductFixedCostService $productFixedCostService
    ) {}

    public function categories() {
        $result = ProductCategory::all();
        return response()->json([
            'status'=>'success',
            'message'=>'Retrieved data Successfully',
            'data'=>$result,
        ]);
    }
    public function index(Request $request) {
        $query = Products::with([
            'user_id',
            'category_id'
        ]);

        if($request->filled('category')){
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $product = $query->latest()->paginate(10);

        return response()->json([
            'status'=>'sucess',
            'data'=>$product
        ],201);
    }

    public function show($id) {
        $query = Products::with([
            'user',
            'category',
            'hppCalculation.user',
            'variable.user',
            'fixed.user',
            'result.user',
            'recommendation.user',
        ])->findOrFail($id);

        $product = $query->latest();

        return response()->json([
            'status'=>'sucess',
            'data'=>$product
        ],201);
    }

    //get product data
    public function productModeling(ProductRequest $request) {
        try {
            $validated = $request->validated();
            
            $result = $this->productModelingService
                ->searchProduct(
                    $validated['product_name'],
                    $validated['category']
                );

            if ($result['data']->isEmpty()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Product template not found',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product template found',
                'data' => $result['data']
            ]);

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function productFixedCost (ProductRequest $request) {
        try {
            $validated = $request->validated();
            
            $result = $this->productFixedCostService
                ->searchFixedCost($validated['category']);

            if (empty($result['data'])) {

                return response()->json([
                    'success' => false,
                    'message' => 'Fixed cost template not found',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Fixed cost template found',
                'data' => $result['data']
            ]);

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
