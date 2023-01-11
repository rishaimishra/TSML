<?php

namespace App\Http\Controllers\Api\Modules\Temp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TemporaryController extends Controller
{
    public function scExcelSubmit(Request $request)
    {
    	 echo "<pre>";print_r($request->all());exit();

    	 foreach ($request->Items as $key => $value) {
    	 	
    	 	  echo "<pre>";print_r($value['item']);exit();
    	 }
    }
}
