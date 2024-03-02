<?php
namespace App\Traits;
use GuzzleHttp\Client;
use Helper;
use Exception;
use Throwable;
use JsonException;
use Log;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

trait ConsumeExternalService
{
    public function performRequest($method, $requestUrl, $formParams = [], $headers = [])
    {
        if($requestUrl == "/orderNotification?wsdl"){
            // ....
        }

        $formParams = (array)$formParams;
        if($requestUrl != "/orderNotification?wsdl" && $requestUrl != "/orderNotification"){
            if(!isset($formParams['dbconnection'])){
                $formParams['dbconnection'] = $this->new_headers??null;
            }
            
            if(!isset($formParams['dbconnection'])){
                $formParams['dbconnection'] = Config::get('app.dbconnection')??null;
            }
        }

        if(( strtolower($method)=="get" || strtolower($method)=="delete") && !empty($formParams))
        {           
            $requestUrl=$requestUrl."?".http_build_query($formParams);
        }
        try 
        {
            $client = new Client([
                'base_uri'  =>  $this->baseUri
            ]);
          
            if(isset($this->secret) && !empty($this->secret) )
            {
                $headers['Authorization'] = $this->secret;
            }

            $headers['Locale'] = $this->Locale;
            $headers['Clientid'] = $this->client_id;  
            $headers['Userid'] = $this->user_id??null;
            $headers['charset'] = "UTF-8";

            if(is_array($formParams) && !empty($formParams) && isset($formParams['uploadedFile'])){
                if(!array_key_exists('from_job',$formParams)){
                    $f = fopen($formParams['uploadedFile'], "r");
                    if(isset($formParams['billingfile_certificate']) && $formParams['billingfile_certificate'] != 'null'){
                        $billingfile_certificate = fopen($formParams['billingfile_certificate'], "r");
                        $request_data['multipart'][] = ['name'=>'billingfile_certificate', 'contents'=>$billingfile_certificate];
                    }
                    
                    $request_data['multipart'][] = ['name'=>'uploadedFile', 'contents'=>$f];
                    
                    foreach($formParams as $key=>$value){
                        $request_data['multipart'][] = ['name'=>$key, 'contents'=>$value];
                    }
                
                    $request_data['headers'] = $headers;
                    $request_data['http_errors'] = false;
                }else{

                    $request_data = [
                        'form_params' => $formParams,
                        'headers'     => $headers,
                        'http_errors' => false,
                    ];

                    if($requestUrl == "/orderNotification"){
                        $request_data = [];
                        $request_data = [
                            'body'    => $formParams,
                            'headers' => [
                                "Content-Type" => "text/xml",
                                "charset"   => "UTF-8",
                                "accept" => "*/*",
                                "accept-encoding" => "gzip, deflate, utf-8"
                            ]
                        ];
                    }
                }
               
            }else{
                $request_data = [
                    'form_params' => $formParams,
                    'headers'     => $headers,
                    'http_errors' => false,
                ];

                if($requestUrl == "/orderNotification"){
                    $request_data = [];
                    $request_data = [
                        'body'    => $formParams[0],
                        'headers' => [
                            "Content-Type" => "text/xml",
                            "charset"   => "UTF-8",
                            "accept" => "*/*",
                            "accept-encoding" => "gzip, deflate, utf-8"
                        ]
                    ];
                }
            }

            $request = $client->request($method, $this->baseUri.$requestUrl, $request_data);
            $result = $request->getBody()->getContents();
            $status_code = $request->getStatusCode();

            // $request = new \GuzzleHttp\Psr7\Request($method, $this->baseUri.$requestUrl, $request_data);
            
            // $promise = $client->sendAsync($request)->then(function ($response) {
            //    return $response;
            // });
            
            // // do anything here 
            
            // // wait for request to finish and display its response
            // $response   = $promise->wait();
            // $result     =   $response->getBody()->getContents();
            // $status_code=   $response->getStatusCode();
            
            try
            {  
                if($status_code == 200 && ($requestUrl == "/orderNotification?wsdl" || $requestUrl == "/orderNotification")){                   
                     return $result;                    
                }

                if($status_code==204){
                    http_response_code(200);
                    return $result;
                } 

                $obj=json_decode($result);
               
                $status=$obj->status;
                $msg=$obj->msg;
                $data=$obj->data;
                if(in_array($status_code,[401,403,400,500]))
                {   
                    http_response_code($status_code);
                }
                
                return $result;
            }catch(QueryException $e)
            {
                return Helper::fail($e->getMessage(),[],500);
            }catch (Exception $e)
            {
                return Helper::fail($e->getMessage(),[],500);
            }
        }catch (\GuzzleHttp\Exception\ConnectException $e){
            return Helper::fail($e->getMessage(),[],500);
            
        } catch (Exception $e) {
            return Helper::fail($e->getMessage(),[],500);
        } 
    }
}