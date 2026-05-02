<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Torann\GeoIP\Facades\GeoIP;

class CheckCountry
{
    
    protected $allowedCountries = ['IT', 'BD'];

    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        
        if ($this->isPrivateIp($ip)) {
            return $next($request);
        }

        try {
            $location = GeoIP::getLocation($ip);
            $country = $location->iso_code ?? null;
        } catch (\Exception $e) {
            
            abort(403, 'Impossibile determinare la tua posizione.');
        }

        if (!$country || !in_array($country, $this->allowedCountries)) {
            abort(403, 'Accesso consentito solo da Italia e Bangladesh.');
        }



        return $next($request);
    }

    protected function isPrivateIp($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}