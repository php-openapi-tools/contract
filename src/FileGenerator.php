<?php

declare(strict_types=1);

namespace OpenAPITools\Contract;

use OpenAPITools\Representation\Namespaced\Representation;
use OpenAPITools\Utils\File;

/**
 *
 * @api
 */
interface FileGenerator
{
    /** @return iterable<File> */
    public function generate(Package $package, Representation $representation): iterable;
}
