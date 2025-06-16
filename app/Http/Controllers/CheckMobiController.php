<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use checkmobi\CheckMobiRest;
use Illuminate\Support\Collection;
/**
 * @group Settings
 */
class CheckMobiController
{

	public function __construct() {
		$this->client = new CheckMobiRest(config('constants.options.check_mobile'), [
		    "net.timeout" => 60, 
		    "net.ssl_verify_peer" => false
		]);
	}

	


	
	// function send verify
	//[type] -> //validate a number using "Missed call method". (type can be : sms, ivr, cli, reverse_cli)
	public function sendCodeVerify($type = 'reverse_cli', $phone_number) { 
		
        $response = $this->client->RequestValidation(array("type" => 'reverse_cli', "number" => $phone_number));
        if($response->is_success()) {
		    // success 
		    //print_r($response->payload());
		    return response()->json([
	            "data" => $response->payload(),
	            'message' => 'Code Sent',
	            'code' => 200
	        ], 201);
		}
		else
		{
		    // failure
		   // print "error code: ".$response->payload()["code"]." error message: ".$response->payload()["error"];
		    
	        return response()->json([
	            "error_code" => $response->payload()["code"],
	            'message' => $response->payload()["error"],
	            'code' => 400
	        ], 201);
		
		}
	}

	public function returnVerifyPin(Request $request){ 
	
		$response = $this->client->VerifyPin(array("id" => $request->id, "pin" => $request->pin));
		//dd($response);
		if($response->is_success()) { 
			if( $response->payload()['validated'])
				return 1;
			
			else 
				return 0;
			
		}
		else 
			return 0;
		
	}

	public function sendVerify(Request $request) { 
		
        $response = $this->client->RequestValidation(array("type" => 'reverse_cli', "number" => $request->phone));
        if($response->is_success()) {
		    // success 
		    return response()->json([
	            "data" => $response->payload(),
	            'message' => 'Code Sent',
	            'code' => 200
	        ], 201);
		}
		else
		{
		    
	        return response()->json([
	            "error_code" => $response->payload()["code"],
	            'message' => $response->payload()["error"],
	            'code' => 400
	        ], 201);
		
		}
	}
	
	public function sendCodeToPhone($phone_number, User $user) { 
		try {	
	        $response = $this->client->RequestValidation(array("type" => 'reverse_cli', "number" => $phone_number));
	        
	        if($response->is_success()) {
	           $user->update([
					'mobile_check_id' => $response->payload()['id'],
				]);
			    return 1;
			}
			else
			{
			    return 0;
			
			}
		}
		catch (\Exception $e) {
		
			return -1;
		}
	}
	
	public function sendResetPasswordToPhone($phone_number, User $user) { 
		try {	
	        $response = $this->client->RequestValidation(array("type" => 'reverse_cli', "number" => $phone_number));
	        if($response->is_success()) {
	           $user->update([
					'verify_code' => $response->payload()['id'],
				]);
			    return 1;
			}
			else
			{
			    return 0;
			
			}
		}
		catch (\Exception $e) {
			return -1;
		}
	}
	
	

	

	

}
