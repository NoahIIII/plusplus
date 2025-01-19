<?php
namespace App\Pipelines\Filters;

use Illuminate\Http\Request;

class AdminFilter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle($query, \Closure $next)
    {
        $validated = $this->request->validate([
            'search' => 'nullable|string|min:3|max:255',
        ]);

        if (!empty($validated['search'])) {
            $query->where('email', 'like', '%' . $validated['search'] . '%')
                ->orWhere('name', 'like', '%' . $validated['search'] . '%');
        }

        return $next($query);
    }
}
