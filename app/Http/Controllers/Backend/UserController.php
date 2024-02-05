<?php

namespace App\Http\Controllers\Backend;

use App\Helper\JWTToken;
use App\Http\Controllers\Controller;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    //user registration
    function userRegistration(Request $request){
        try{
            $user=User::create([
                "firstName"=>$request->input("firstName"),
                "lastName"=>$request->input("lastName"),
                "email"=>$request->input("email"),
                "mobile"=>$request->input("mobile"),
                "password"=>$request->input("password"),
            ]);
            return response()->json(["status"=>"success","message"=>"user created successfully"],200);
        }catch(Exception $e){
            return response()->json(["status"=>"faild","message"=>$e->getMessage()]);
        }
        
    }
    // user login
    function UserLogin(Request $request){
        try{
            // return $request->email;
           $userCount=User::where("email","=",$request->input("email"))
                    ->where("password","=",$request->input("password"))
                    ->select("id")
                    ->first();
                    if($userCount !== null){
                        $token=JWTToken::createToken($request->input("email"),$userCount->id);
                        return response()->json(["status"=>"success","message"=>"user login successfully"],200)->cookie("token",$token,60*60*24);
                        
                    }else{
                        return response()->json(["status"=>"faild","message"=>"user credintials doesn't match"],200);
                    }                      
        }catch(Exception $exception){
            return response()->json(["status"=>"faild","message"=>$exception->getMessage()]);
        }
    }
    // send to verification code to email
    function sendOTPCode(Request $request){
        $email=$request->input("email");
        $count=User::where("email",$email)->count();
        $otp=rand(1000,9999);
        if($count===1){
            // send otp to user email address
            Mail::to($email)->send(new OTPMail($otp));
            // update otp to user table
            User::where("email",$email)->update(["otp"=>$otp]);

            return response()->json(["status"=>"success","message"=>"Please check your mail for OTP"]);
            
        }else{
            return response()->json(["status"=>"faild","message"=>"user not found"],200);
        }
        
    }
    // verify otp
    function verifyOTP(Request $request){
        try{
            $email=$request->input("email");
            $otp=$request->input("otp");
            $count=User::where("email",$email)->where("otp",$otp)->count();
            // echo  $count;
            // dd();
            if($count===1){
                //Database otp update
                User::where("email",$email)->update(["otp"=>"0"]);
                //token issue for password reset
                $token=JWTToken::createTokenForResetPassowrd($email);
                return response()->json([
                    "status"=>"success",
                    "message"=>"OTP verified successfully",
                ],200)->cookie("token",$token,60*60*24);
            }else{
                return response()->json(["status"=>"faild","message"=>"Invalid OTP"],200);
            }
        }catch(Exception $exception){
            return response()->json(["status"=>"faild","message"=>$exception->getMessage()]);
        }

    }

    function passwordReset(Request $request){
       try{
        $password=$request->input("password");
        $email=$request->header("email");
        User::where("email","=",$email)->update(["password"=>$password]);
        return response()->json([
            "status"=>"success",
            "message"=>"password reset successfully"
        ],200);
       }catch(Exception $exception){
            return response()->json([
                "status"=>"faild",
                "message"=>$exception->getMessage()
            ],200);
       }
    }

    function userLogout(){
        return redirect("/user-login")->cookie("token",'',-1);
    }


    function userDetails(Request $request){
        $email=$request->header("email");
        $user=User::where("email","=",$email)->first();
        return response()->json([
            "status"=>"success",
            "data"=>$user
        ],200);
    }

    function userDetailsUpdate(Request $request){
       try{
        $email=$request->header("email");
        $firstName=$request->input("firstName");
        $lastName=$request->input("lastName");
        $mobile=$request->input("mobile");
        $password=$request->input("password");
        User::where("email","=",$email)->update([
            "firstName"=>$firstName,
            "lastName"=>$lastName,
            "mobile"=>$mobile,
            "password"=>$password,
        ]);
        return response()->json([
            "status"=>"success",
            "message"=>"user details update successfully"
        ],200);
       }catch(Exception $exception){
            return response()->json([
                "status"=>"faild",
                "message"=>$exception->getMessage()
            ],200);
       }
    }

//Pages method

    function registrationPage(){
        return view("pages.auth.registration-page");
    }
    function loginPage(){
        return view("pages.auth.login-page");
    }
    function sendOtpPage(){
        return view("pages.auth.send-otp-page");
    }
    function verifyOtpPage(){
        return view("pages.auth.verify-otp-page");
    }
    function resetPasswordPage(){
        return view("pages.auth.reset-pass-page");
    }

    function profilePage(){
        return view("pages.dashboard.profile-page");
    }
}
