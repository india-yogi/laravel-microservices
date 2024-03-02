<?php
namespace App\Services;

use App\Traits\ConsumeExternalService;
use Illuminate\Http\Request;

class OrderService
{
    use ConsumeExternalService;
    
    /**
     * The base uri of the service
     * @var string
     */
    public $baseUri;
    public $secret;
    public $token;
    public $request;    


    public function __construct(Request $request)
    {
        $this->baseUri      = config('services.orders.base_uri');
        $this->secret       = config('services.orders.secret');
        $this->token        = $request->header('Authorization');
        $this->request      = $request;
    }
    
    
    /**
     * Get the full list of orders
     * 
    */
    public function index($data)
    {
        return $this->performRequest('GET', "/orders", $data);
    }
    
    /**
     * Create Order
     */
    public function store($data)
    {
        return $this->performRequest('POST', '/orders', $data);
    }
    
    /**
     * Get a single order
     */
    public function show($id)
    {
        return $this->performRequest('GET', "/orders/{$id}");
    }
    
    /**
     * Edit a single order data
     */
    public function update($data, $id)
    {
        return $this->performRequest('PUT', "/orders/{$id}", $data);
    }
    
    /**
     * Delete an Order
     */
    public function destroy($id)
    {
        return $this->performRequest('DELETE', "/orders/{$id}", $this->request->all());
    }    
}
