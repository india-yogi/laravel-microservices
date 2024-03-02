<?php
namespace App\Http\Controllers\v1\Order;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * The service to consume the Orders micro-service
     * @var orderService
     */
    public $productservice;

    public function __construct(OrderService $orderservice)
    {
        $this->orderService = $orderservice;
    }

    /**
     * Get Order data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $products = $this->orderService->index($request->all());
    }

    /**
     * Save an Order data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return $this->orderService->store($request->all());
    }

    /**
     * Show a single Order details
     * @param $Order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $order_details = $this->orderService->show($id, $request->all());
        $response = json_decode($order_details);

        return $this->successResponse(json_encode($response));
    }


    /**
     * Update a single Order data
     * @param Request $request
     * @param $Order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return $this->successResponse($this->orderService->update($request->all(), $id));
    }


    /**
     * Delete a single Order details
     * @param $Order
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return $this->successResponse($this->orderService->destroy($id));
    }
}