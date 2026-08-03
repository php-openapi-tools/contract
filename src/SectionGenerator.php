<?php

declare(strict_types=1);

namespace OpenAPITools\Contract;

use OpenAPITools\Representation;

/**
 *
 * @api
 */
interface SectionGenerator
{
    public static function path(Representation\Path $path): string|false;

    public static function webHook(Representation\WebHook ...$webHooks): string|false;
}
