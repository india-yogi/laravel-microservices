<?php
namespace App\Services;

use App\Traits\ConsumeExternalService;
use Illuminate\Http\Request;

class ProductService
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
        $this->baseUri      = config('services.products.base_uri');
        $this->secret       = config('services.products.secret');
        $this->token        = $request->header('Authorization');
        $this->request      = $request;
    }
    
    
    /**
     * Get the full list of products
     * 
    */
    public function index($data)
    {
        return $this->performRequest('GET', "/products", $data);
    }
    
    /**
     * Create Order
     */
    public function store($data)
    {
        return $this->performRequest('POST', '/products', $data);
    }
    
    /**
     * Get a single order
     */
    public function show($id)
    {
        return $this->performRequest('GET', "/products/{$id}");
    }
    
    /**
     * Edit a single order data
     */
    public function update($data, $id)
    {
        return $this->performRequest('PUT', "/products/{$id}", $data);
    }
    
    /**
     * Delete an Order
     */
    public function destroy($id)
    {
        return $this->performRequest('DELETE', "/products/{$id}", $this->request->all());
    }    
}
