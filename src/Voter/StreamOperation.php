<?php

declare(strict_types=1);

namespace OpenAPITools\Voter;

use OpenAPITools\Representation\Operation;

/**
 *
 * @api
 */
interface StreamOperation
{
    public static function stream(Operation $operation): bool;
}
