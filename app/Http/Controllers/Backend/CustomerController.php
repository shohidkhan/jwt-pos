<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //

    function customerPage(){
        return view("pages.dashboard.customer-page");
    }

    function createCustomer(Request $request){
        try{
            $request->validate([
                "name"=>"string|required|max:100",
                "mobile"=>"string|required|min:11",
                "email"=>"string|required|max:100"
            ]);
            Customer::create([
                "name"=>$request->input("name"),
                "mobile"=>$request->input("mobile"),
                "email"=>$request->input("email"),
                "user_id"=>$request->header("id"),
            ]);
            return response()->json(["status"=>"success","message"=>"Customer created successfully"],200);
        }catch(Exception $exception){
            return response()->json(["status"=>"error","message"=>$exception->getMessage()],200);
        }
    }
    function customerList(Request $request){
        $user_id=$request->header("id");
        $customers=Customer::where("user_id",$user_id)->get();
        return $customers;
    }
     function singleCustomer(Request $request){
        $user_id=$request->header("id");
        $customer_id=$request->input("id");
        $customer=Customer::where("id",$customer_id)->where("user_id",$user_id)->first();
        return $customer;
     }
     function customerUpdate(Request $request){

        $request->validate([
            "name"=>"string|required|max:100",
            "mobile"=>"numeric|required|min:11",
            "email"=>"string|required|max:100"
        ]);
        $customer_id=$request->input("id");
        $user_id=$request->header("id");
        Customer::where("id",$customer_id)->where("user_id",$user_id)->update([
            "name"=>$request->input("name"),
            "mobile"=>$request->input("mobile"),
            "email"=>$request->input("email"),
        ]);
        return response()->json(["status"=>"success","message"=>"Customer updated successfully"],200);
     }
    function customerDelete(Request $request){
        $id=$request->input("id");
        $user_id=$request->header("id");

        Customer::where("id",$id)->where("user_id",$user_id)->delete();
        return response()->json(["status"=>"success","message"=>"Customer deleted successfully"],200);
    }

}
