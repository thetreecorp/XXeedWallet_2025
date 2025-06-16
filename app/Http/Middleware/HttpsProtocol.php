<?php
namespace App\Http\Middleware;
use App\Http\Traits\CommonFunctionTrait;
use Closure;

class HttpsProtocol {
    use CommonFunctionTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if($request->get('token') && !$request->get('grant_id')){
            $token = $request->get('token');
         //   $this->decodeKey($token);
  
           // try{    
                if(!$this->decodeKey($token)){
                    
                    //abort(404);
                    return response()->redirectToRoute('register.verifyEmail.info', ['token' => $token]);
                }
                
            /*}catch (\Throwable $e) {
                return redirect()->route('/');
            }*/

        }
        
        if (env('FORCE_HTTPS') == "On" && !$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}
