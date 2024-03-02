<?php 
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class Helper
{
	public static function success($msg="success",$data=[],$status_code=200)
	{
		return Helper::response($msg,$data,$status_code);
	}
	
	public static function fail($msg="fail",$data=[],$status_code=400)
	{
		return Helper::response($msg,$data,$status_code);
	}

	public static function response($msg,$data,$status_code)
	{
	    if($data["per_page"]??"")
	    {
	        $respose=[
	            'status'   =>  $status_code,
	            'msg'      =>  app('translator')->get('msg.'.$msg),
	            'data'     =>  $data["data"],
	            'paginator'=>[
	                "total"        =>  $data["total"],
	                "currentPage"  =>  $data["current_page"],
	                "perPage"      =>  $data["per_page"],
	                "pages"        =>  ceil($data["total"]/$data["per_page"])-1,
	                "first_page_url"=>"https://domain.com?page=1"
	            ]
	        ];
	    }
	    else
	    {
	        $respose=[
	            'status'   =>  $status_code,
	            'msg'      =>  app('translator')->get('msg.'.$msg),
	            'data'     =>  $data,
	        ];
	    }
	    return response()->json($respose,$status_code);
	}
}