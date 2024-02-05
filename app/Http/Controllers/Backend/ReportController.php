<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    //


    function reportPage()
    {
        return view("pages.dashboard.report-page");
    }

    function salesReport(Request $request)
    {
        try{
            $FormDate = date("Y-m-d", strtotime($request->FormDate));
            $ToDate = date("Y-m-d", strtotime($request->ToDate));
            $subtotal=0;
            $user_id=$request->header("id");
            

            $discount=Invoice::where("user_id",$user_id)->whereDate("created_at",">=",$FormDate)->whereDate("created_at","<=",$ToDate)->sum("discount");

            $vat=Invoice::where("user_id",$user_id)->whereDate("created_at",">=",$FormDate)->whereDate("created_at","<=",$ToDate)->sum("vat");

            $payable=Invoice::where("user_id",$user_id)->whereDate("created_at",">=",$FormDate)->whereDate("created_at","<=",$ToDate)->sum("payable");

            $list=Invoice::where("user_id",$user_id)->whereDate("created_at",">=",$FormDate)->whereDate("created_at","<=",$ToDate)->with("customer")->get();

            $subtotal_list=Invoice::where("user_id",$user_id)->whereDate("created_at",">=",$FormDate)->whereDate("created_at","<=",$ToDate)->get();
         
            foreach ($subtotal_list as  $item) {

                $subtotal=$subtotal+$item->total;
            }


            $data=[
                "discount"=>$discount,
                "vat"=>$vat,
                "payable"=>$payable,
                "list"=>$list,
                "subtotal"=>$subtotal,
                "FormDate"=>$FormDate,
                "ToDate"=>$ToDate
            ];
            $pdf = Pdf::loadView('report.SalesReport',$data);
            return $pdf->download('invoice.pdf');

        }catch(Exception $e){
            return response()->json(["status"=>"error","message"=>$e->getMessage()]);
        }
    }
}
