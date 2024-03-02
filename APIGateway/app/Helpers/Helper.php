<?php
use App\Jobs\LoggingJob;
use Illuminate\Http\Request;

class Helper {
    const FAILED = 'Failed';
    const SUCCESS = 'Success';

    public static function success($msg = "success", $data = [], $status_code = 200) {
        return Helper::response($msg, $data, $status_code);
    }
    public static function fail($msg = "fail", $data = [], $status_code = 400) {
        return Helper::response($msg, $data, $status_code);
    }
    public static function response($msg, $data, $status_code) {
        if ($status_code == 403) {
            $response = [
                'status' => $status_code,
                'msg' => app('translator')->get('msg.' . $msg) . " " . implode(',', $data),
                'data' => $data,
            ];
        } else {
            $response = [
                'status' => $status_code,
                'msg' => app('translator')->get('msg.' . $msg),
                'data' => $data,
            ];

        }
        http_response_code($status_code);
        return response($response, $status_code);
    }
}