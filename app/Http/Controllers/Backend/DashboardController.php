<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    function dashboardPage(){
        return view("pages.dashboard.dashboard-page");
    }


    function summary(Request $request){
        try{
            $user_id=$request->header("id");
            $products=Product::where("user_id",$user_id)->count();
            $category=Category::where("user_id",$user_id)->count();
            $customer=Customer::where("user_id",$user_id)->count();
            $invoice=Invoice::where("user_id",$user_id)->count();
            $total=Invoice::where("user_id",$user_id)->sum("payable");
            $discount=Invoice::where("user_id",$user_id)->sum("discount");
            $vat=Invoice::where("user_id",$user_id)->sum("vat");

            return[
                "products"=>$products,
                "category"=>$category,
                "customer"=>$customer,
                "invoice"=>$invoice,
                "total"=>round($total,2),
                "discount"=>round($discount,2),
                "vat"=>round($vat,2),
            ];

        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()]);
        }
    }
}
