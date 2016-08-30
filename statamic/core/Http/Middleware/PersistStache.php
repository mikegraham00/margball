<?php

namespace Statamic\Http\Middleware;

use Closure;
use Statamic\API\Str;
use Statamic\Stache\Persister;
use Statamic\Stache\Stache;

class PersistStache
{
    /**
     * @var \Statamic\Stache\Stache
     */
    private $stache;

    /**
     * @var \Statamic\Stache\Persister
     */
    private $persister;

    public function __construct(Stache $stache, Persister $persister)
    {
        $this->stache = $stache;
        $this->persister = $persister;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        // Get the keys of the repos that have been updated. If an aggregate repo
        // was updated, we'll just grab the base repo key (before the ::).
        $updates = $this->stache->updated()->map(function ($key) {
            if (Str::contains($key, '::')) {
                $key = explode('::', $key)[0];
            }

            return $key;
        });

        if ($updates->count()) {
            $this->persister->persist($updates);
        }
    }
}
