<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Faker\Core\File;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //


    function productPage(Request $request){
        
        return view("pages.dashboard.product-page");
    }


    function createProduct(Request $request){
        $request->validate([
            "category_id"=>"required",
            "name"=>"required|string|max:100",
            "price"=>"required|numeric",
            "unit"=>"required",
            "img_url"=>"required|mimes:png,jpg,jpeg|image"
        ]);
        $user_id=$request->header("id");


        //prepare img path and name

        $img=$request->file("img_url");
        
        $time=time();
        $file_original_name=$img->getClientOriginalName();
        $img_name="{$user_id}-{$time}-{$file_original_name}";
        $img_url="uploads/products/{$img_name}";
        
        //file upload
        $upload_file=$img->move(public_path("uploads/products"),$img_name);

        Product::create([
            "category_id"=>$request->input("category_id"),
            "user_id"=>$user_id,
            "name"=>$request->input("name"),
            "price"=>$request->input("price"),
            "unit"=>$request->input("unit"),
            "img_url"=>$img_url
        ]);

        return response()->json(["status"=>"success","message"=>"product created successfully"],200);
    }


    function productList(Request $request){
        $user_id=$request->header("id");
        $products=Product::where("user_id",$user_id)->with("category")->get();
        return $products;
    }

    function productDelete(Request $request){
        $user_id=$request->header("id");
        $product_id=$request->input("id");
        $product=Product::where("id",$product_id)->where("user_id",$user_id)->first();
        $removedFile=unlink(public_path($product->img_url));
        if($removedFile){
            Product::where("id",$product_id)->where("user_id",$user_id)->delete();
            return response()->json(["status"=>"success","message"=>"product deleted successfully"],200);
        }
        Product::where("id",$product_id)->where("user_id",$user_id)->delete();
        return response()->json(["status"=>"success","message"=>"product deleted successfully"],200);
    }

    function productSingle(Request $request){
        $user_id=$request->header("id");
        $product_id=$request->input("id");
        $product=Product::where("id",$product_id)->where("user_id",$user_id)->first();
        return $product;
    }

    function productUpdate(Request $request){
         try{
            $user_id=$request->header("id");
            $product_id=$request->input("id");
            // $product=Product::where("id",$product_id)->where("user_id",$user_id)->first();
            // return $product;
            if($request->hasFile("img_url")){
                $request->validate([
                    "category_id"=>"required",
                    "name"=>"required|string|max:100",
                    "price"=>"required|numeric",
                    "unit"=>"required",
                    "img_url"=>"required|mimes:png,jpg,jpeg|image"
                ]);

                $product=Product::where("id",$product_id)->where("user_id",$user_id)->first();
                $removedFile=unlink(public_path($product->img_url));
                $img=$request->file("img_url");
                $time=time();
                $file_original_name=$img->getClientOriginalName();
                $img_name="{$user_id}-{$time}-{$file_original_name}";
                $img_url="uploads/products/{$img_name}";
                $img->move(public_path("uploads/products"),$img_name);

            Product::where("id",$product_id)->where("user_id",$user_id)->update([
                "category_id"=>$request->input("category_id"),
                "user_id"=>$user_id,
                "name"=>$request->input("name"),
                "price"=>$request->input("price"),
                "unit"=>$request->input("unit"),
                "img_url"=>$img_url
            ]);
            return response()->json(["status"=>"success","message"=>"product updated successfully with image" ],200);
            // return $img_name;
            }else{
                $request->validate([
                    "category_id"=>"required",
                    "name"=>"required|string|max:100",
                    "price"=>"required|numeric",
                    "unit"=>"required"
                ]);
                Product::where("id",$product_id)->where("user_id",$user_id)->update([
                    "category_id"=>$request->input("category_id"),
                    "user_id"=>$user_id,
                    "name"=>$request->input("name"),
                    "price"=>$request->input("price"),
                    "unit"=>$request->input("unit"),
                    "img_url"=>$request->input("oldImgFromDb")
                ]);
                return response()->json(["status"=>"success","message"=>"product updated successfully without image"],200);
            }
            // dd($request->all());
        }catch(Exception $ex){
            return response()->json(["status"=>"error","message"=>$ex->getMessage()],200);
        }
    }
}
