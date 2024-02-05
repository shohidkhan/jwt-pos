<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    //

    function InvoicePage()
    {
        return view('pages.dashboard.invoice-page');
    }  
    
    function salePage(){
        return view('pages.dashboard.sale-page');
    }


    function createInvoice(Request $request){
        DB::beginTransaction();
        try{
            $user_id=$request->header("id");
            $total=$request->input("total");
            $discount=$request->input('discount');
            $vat=$request->input('vat');
            $payable=$request->input('payable');
            $customer_id=$request->input('customer_id');

            $invoice=Invoice::create([
                "total"=>$total,
                "discount"=>$discount,
                "vat"=>$vat,
                "payable"=>$payable,
                "user_id"=>$user_id,
                "customer_id"=>$customer_id
            ]);

            $invoiceId=$invoice->id;

            $products=$request->input("products");

            foreach($products as $eachProduct){
                InvoiceProduct::create([
                    "product_id"=>$eachProduct["product_id"],
                    "qty"=>$eachProduct["qty"],
                    "sale_price"=>$eachProduct["sale_price"],
                    "invoice_id"=>$invoiceId,
                    "user_id"=>$user_id
                ]);
            }

            DB::commit();
            return response()->json(["status"=>"success","message"=>"Invoice Created Successfully"]);
        }
        
        catch(Exception $exception){
            DB::rollBack();
            return response()->json(["status"=>"error","message"=>$exception->getMessage()],200);
        }
    }

    function invoiceSelect(Request $request){
        try{
            $user_id=$request->header("id");
            $invoices=Invoice::where("user_id",$user_id)->with("customer")->get();
            return $invoices;

        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()],200);
        }
    }

    function invoiceDetail(Request $request){
        try{
            $user_id=$request->header("id");
            $customer=Customer::where("user_id",$user_id)->where("id",$request->input("customer_id"))->with("user")->first();
            $invoiceTotal=Invoice::where("user_id",$user_id)->where("id",$request->input("invoice_id"))->first();
            $invoiceProduct=InvoiceProduct::where("user_id",$user_id)->where("invoice_id",$request->input("invoice_id"))->with("product")->get();

            return array(
                "customer"=>$customer,
                "invoice"=>$invoiceTotal,
                "product"=>$invoiceProduct
            );

        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()],200);
        }
    }

    function invoiceDelete(Request $request){
        DB::beginTransaction();

        try{
            $user_id=$request->header("id");
            InvoiceProduct::where("user_id",$user_id)->where("invoice_id",$request->input("invoice_id"))->delete();
            Invoice::where("id",$request->input("invoice_id"))->delete();
            DB::commit();
            return response()->json(["status"=>"success","message"=>"Invoice Deleted Successfully"]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(["status"=>"error","message"=>$e->getMessage()],200);
        }
    }
}
