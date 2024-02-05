<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    
    function categoryPage(){
        return view("pages.dashboard.category-page");
    }
    function categoryList(Request $request){
        $user_id=$request->header("id");
        $categories=Category::where("user_id","=",$user_id)->with("user")->get();
        // dd($categories);
        return $categories;
    }

    function categoryCreate(Request $request){
        try{

            $request->validate([
                "name"=>"required|string|max:100",
            ]);
            $user_id=$request->header("id");
            Category::create([
                "name"=>$request->input("name"),
                "user_id"=>$user_id,
            ]);
            return response()->json(["status"=>"success","message"=>"category created successfully"],200);
        }catch(Exception $exception){
            return response()->json(["status"=>"error","message"=>$exception->getMessage()],200);
        }
    }

    function categoryDelete(Request $request){
        $user_id=$request->header("id");
        $category_id=$request->input("id");
        Category::where("id",$category_id)->where("user_id",$user_id)->delete();
        return response()->json(["status"=>"success","message"=>"category deleted successfully"],200);
    }
    function singleCategory(Request $request){
        // sleep(3);
        $user_id=$request->header("id");
        $category_id=$request->input("id");
        $category=Category::where("id",$category_id)->where("user_id",$user_id)->first();
        return $category;
    }
    function categoryUpdate(Request $request){
    
        $request->validate([
            "name"=>"required|string|max:100",
        ]);

        $category_id=$request->input("id");
        $user_id=$request->header("id");

        Category::where("id",$category_id)->where("user_id",$user_id)->update([
            "name"=>$request->input("name"),
        ]);

        return response()->json(["status"=>"success","message"=>"category updated successfully"],200);

    }
}
