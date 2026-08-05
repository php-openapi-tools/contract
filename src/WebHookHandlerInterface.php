<?php

declare(strict_types=1);

namespace OpenAPITools\Contract;

use Psr\Http\Message\ResponseInterface;

/**
 * @api
 */
interface WebHookHandlerInterface
{
    public function handle(
        object $payload,
    ): ResponseInterface;
}
