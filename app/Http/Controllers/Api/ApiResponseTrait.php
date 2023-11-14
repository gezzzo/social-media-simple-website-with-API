<?php

namespace App\Http\Controllers\Api;

trait ApiResponseTrait
{

    public function apiresponse($data=null,$status=null,$message=null){

        $array = [
            'status'=>$status,
            'data'=>$data,
            'message'=>$message
        ];
        return response($array,$status);

    }
}
