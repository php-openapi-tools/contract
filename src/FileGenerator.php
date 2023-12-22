<?php

declare(strict_types=1);

namespace OpenAPITools\Contract;

use OpenAPITools\Representation\Representation;
use OpenAPITools\Utils\File;

interface FileGenerator
{
    /** @return iterable<File> */
    public function generate(Package $package, Representation $representation): iterable;
}
