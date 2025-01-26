<?php
namespace App\Pipelines\Filters;

use Closure;
use Illuminate\Http\Request;

class SortingPipeline
{
    /**
     * Handle the sorting logic.
     *
     * @param  mixed  $query
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($query, Closure $next)
    {
        $request = request();

        if ($request->has('order')) {
            foreach ($request->input('order') as $order) {
                $columnIndex = (int)$order['column'];
                $columnName = $request->input('columns.' . $columnIndex . '.data');
                $direction = $order['dir'];

                // Apply sorting for allowed columns
                    $query->orderBy($columnName, $direction);
            }
        }

        return $next($query);
    }
}
