<?php
namespace App\Http\Controllers\v1\Product;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * The service to consume the Products micro-service
     * @var productService
     */
    public $productservice;

    public function __construct(ProductService $productservice)
    {
        $this->productService = $productservice;
    }

    /**
     * Get Product data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $products = $this->productService->index($request->all());
    }

    /**
     * Save an Product data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->productService->store($request->all());
    }

    /**
     * Show a single Product details
     * @param $Product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $Product_details = $this->productService->show($id, $request->all());
        $response = json_decode($Product_details);

        return $this->successResponse(json_encode($response));
    }


    /**
     * Update a single Product data
     * @param Request $request
     * @param $Product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return $this->successResponse($this->productService->update($request->all(), $id));
    }


    /**
     * Delete a single Product details
     * @param $Product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return $this->successResponse($this->productService->destroy($id));
    }
}