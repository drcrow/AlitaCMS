<?php

namespace App\Http\Middleware;

use Closure;

class CMSLoginMiddleware{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        unset($_SESSION['loginError']);

        if ($request->has('user') && $request->has('pass')){
            if($request->input('user') == env('ADMIN_USER') && $request->input('pass') == env('ADMIN_PASS')){
                $_SESSION['user'] = $user;
                $_SESSION['pass'] = $pass;
                unset($_SESSION['loginError']);
                return redirect('CMS/');
            }else{
                $_SESSION['user'] = null;
                $_SESSION['pass'] = null;
                $_SESSION['loginError'] = true;
                return redirect('CMS/login');
            }
        }

        //all ok, continue
        if(@$_SESSION['user'] == env('ADMIN_USER') && @$_SESSION['pass'] == env('ADMIN_PASS')){
            //die('aaaa');
            //return $next($request);
            return redirect('CMS/');
        }else{
            return redirect('CMS/login');
        }


    }

}
?>