<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    
    public function handle(Request $request, Closure $next)
    {
        $token=$request->cookie("token");
        $result=JWTToken::verifyToken($token);
        // return $result;
        if($result==="Unauthorized"  ){
            return redirect("/user-login");
        }else{
            $request->headers->set("email",$result->email);
            $request->headers->set("id",$result->id);
            return $next($request);
        }
        
    }

    
}
