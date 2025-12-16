<?php

namespace App\Http\Middleware;

use App\Support\RequestContextResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ForceWebContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add 'original' property to all responses to prevent NativePhp LivewireDispatcher errors
        if (! property_exists($response, 'original')) {
            $response->original = null;
        }

        // If this is a StreamedResponse, wrap it with a class that has the 'original' property
        if ($response instanceof StreamedResponse && ! property_exists($response, 'original')) {
            return new StreamedResponseWithOriginal($response);
        }

        return $response;
    }
}

/**
 * -> STINKING MONKEY PATCH <-
 * Wrapper class for StreamedResponse that adds the 'original' property
 * to prevent NativePhp LivewireDispatcher errors
 */
class StreamedResponseWithOriginal extends StreamedResponse
{
    public $original = null;

    private $wrappedResponse;

    public function __construct(StreamedResponse $response)
    {
        $this->wrappedResponse = $response;

        // Copy all the important properties from the original response
        parent::__construct(
            function () use ($response) {
                // Get the callback from the original response
                $reflection = new \ReflectionClass($response);
                $callbackProperty = $reflection->getProperty('callback');
                $callbackProperty->setAccessible(true);
                $callback = $callbackProperty->getValue($response);

                if ($callback) {
                    call_user_func($callback);
                }
            },
            $response->getStatusCode(),
            $response->headers->all()
        );
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->wrappedResponse, $method], $arguments);
    }
}
