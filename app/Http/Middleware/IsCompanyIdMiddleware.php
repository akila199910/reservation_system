<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsCompanyIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $company_id = session()->get('_business_id');

        if (isset($company_id) && !empty($company_id)) {
            $company = Business::find($company_id);

            if ($company) {
                return $next($request);
            } else {
                return redirect()->route('admin.business');
            }
        } else {
            Auth::logout();
            return redirect()->route('login');
        }
    }
}
