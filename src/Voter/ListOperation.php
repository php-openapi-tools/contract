<?php

declare(strict_types=1);

namespace OpenAPITools\Contract\Voter;

use OpenAPITools\Representation\Operation;

interface ListOperation
{
    public static function incrementorKey(): string;

    public static function incrementorInitialValue(): int;

    /** @return array<string> */
    public static function keys(): array;

    public static function list(Operation $operation): bool;
}
