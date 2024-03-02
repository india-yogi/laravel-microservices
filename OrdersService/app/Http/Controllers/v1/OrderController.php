<?php
namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Models\Order;

class OrderController extends Controller
{
    const FAILED = 'Failed';
    const SUCCESS = 'Success';

    private $LOG_SERVICE_URL;
    
    const STATUS_NEW        = 1;
    const STATUS_SENT       = 2;
    const STATUS_RECEIVED   = 3;
    const STATUS_CANCELED   = 4;
    const STATUS_DELETED    = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
    * Return full list of orders
    * @return Response
    */
    public function index(Request $request)
    {
        $query = Order::where('1','=',1);        
        $orders = $query->orderBy($order_by, $order)->paginate($limit);

        return Helper::success(self::SUCCESS, $orders->toArray(), 200);
	}
    
	/**
     * Create one new orders
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, $this->rules);
        
        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return Helper::fail(self::FAILED, $errors);
        }

        $order = Order::create($input);        

        return Helper::success(self::SUCCESS, $order, 200);
    }


    /**
     * Show a specific order
     * @param Order $order
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $id = (int)$id;
        
        try{
            $order = Order::where('client_id', $client_id)->findOrFail($id);
        }catch(ModelNotFoundException $e){
            return Helper::fail(self::FAILED, []);
        }

        return Helper::success(self::SUCCESS, $order, 200);
    }

    /**
     * Update order information
     * @param Request $request
     * @param $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $id = (int)$id;
        
        $messages = array();
        $validator = Validator::make($request->all(), $this->rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return Helper::fail("Fail", $errors);            
        }

        try{
            $order = Order::where('client_id', $client_id)->findOrFail($id);
        }catch(ModelNotFoundException $e){
            return Helper::fail(self::FAILED, []);
        }

        $order->fill($request->all());
        $order->save();

        
        return Helper::success(self::SUCCESS, $order, 200);
    }

    /**
     * Delete order information
     * @param $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $id = (int)$id;

        try{
            $order = Order::where('client_id', $client_id)
                                 ->findOrFail($id);
        }catch(ModelNotFoundException $e){
            return Helper::fail(self::FAILED, []);
        }

        // relationship deleting is handeled by the Order through boot method and 'deleting' event.
        $order->delete();
        
        return Helper::success(self::SUCCESS, [], 200);
    }
}