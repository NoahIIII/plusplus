<?php

namespace App\Pipelines\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataTableProductFilter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function handle($query, \Closure $next)
    {
        $searchTerm = $this->request->input('search.value');
        $query->where(function ($q) use ($searchTerm) {
            $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where(
                    DB::raw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.ar")))'),
                    'like',
                    '%' . strtolower($searchTerm) . '%'
                )->orWhere(
                    DB::raw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.en")))'),
                    'like',
                    '%' . strtolower($searchTerm) . '%'
                );
            });
            // filter by status (Active/Inactive)
            $normalizedTerm = strtolower(trim($searchTerm));
            if ($normalizedTerm === 'active') {
                $q->orWhere('status', 1);
            } elseif ($normalizedTerm === 'inactive') {
                $q->orWhere('status', 0);
            }

            // filter by created_at
            $q->orWhere(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s')"), 'like', "%$searchTerm%")
                ->orWhere('price', 'like', "%$searchTerm%")
                ->orWhere('quantity', 'like', "%$searchTerm%");
        });

        return $next($query);
    }
}
