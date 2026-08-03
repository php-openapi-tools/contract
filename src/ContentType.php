<?php

declare(strict_types=1);

namespace OpenAPITools\Contract;

use PhpParser\Node\Expr;

/**
 *
 * @api
 */
interface ContentType
{
    /** @return iterable<string> */
    public static function contentType(): iterable;

    public static function parse(Expr $expr): Expr;
}
