<?php
declare(strict_types=1);

namespace Werkspot\JiraDashboard\SharedKernel\Infrastructure\Messaging\CommandBus\Tactician;

use League\Tactician\Middleware;

class GraphQLMiddleware implements Middleware
{
    /**
     * @param object $command
     * @param callable $next
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function execute($command, callable $next)
    {
        $returnValue = $next($command);

        if (null === $returnValue) {
            $returnValue = true;
        }

        return $returnValue;
    }
}
