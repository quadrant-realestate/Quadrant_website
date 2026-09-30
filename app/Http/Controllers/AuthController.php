<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Stripe;
use Stripe\Charge;
use App\Models\Payment;
use App\Services\CrestService;
use App\Services\crest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function signinrequest(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email_address' => 'email|max:220',
            'password' => 'string|required|min:8|max:50'
        ]);

        
        if (($validator->fails())) {
            // dd($validator);
            return Redirect::back()->withInput()->with('errors', 'Check the list of errors you got!')->withErrors($validator);
        }
        else {
           

            $user = DB::table("admins")->where('email',$request->email_address)->first();
           
            if ($user != null) {
                if ($user->password != sha1($request->password)) {
                    return redirect()->back()->with('noerrors', 'Incorrect password. Please try again.')->withInput();
                }
                // dd($user);
                $sesion_array = [
                    'id' => $user->id,            
                    'name' => $user->name,
                    'email' => $user->email,
                ];
                session()->put($sesion_array);
                return redirect()->route('admin.dashboard');
            } else {            
                return redirect()->back()->with('noerrors', 'Your account does not exist in our system.');            
            }
        }
        
    }

    public function signout()
    {
        session()->flush();
        Session::flush();
        Session::forget('id');
        // Session::forget('role_id');

        return redirect()->route('signin');
    }
    

  
}
