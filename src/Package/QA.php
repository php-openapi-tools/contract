<?php

declare(strict_types=1);

namespace OpenAPITools\Contract\Package;

use OpenAPITools\Contract\Package\QA\Tool;

/**
 * @property Tool|null $phpcs,
 * @property Tool|null $phpstan,
 * @property Tool|null $psalm,
 */
interface QA
{
}
