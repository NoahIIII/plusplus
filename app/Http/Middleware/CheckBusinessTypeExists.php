<?php

namespace App\Http\Middleware;

use App\Models\BusinessType;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBusinessTypeExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $businessTypeId = $request->route('businessTypeId');
        // Check if the business type ID exists in the database
        if (!BusinessType::find($businessTypeId)) {
            return ApiResponseTrait::errorResponse('Business type not found', 404);
        }

        return $next($request);
    }
}
