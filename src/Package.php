<?php

declare(strict_types=1);

namespace OpenAPITools\Contract;

use OpenAPITools\Contract\Package\Destination;
use OpenAPITools\Contract\Package\QA;
use OpenAPITools\Contract\Package\State;
use OpenAPITools\Contract\Package\Templates;
use OpenAPITools\Utils\Namespace_;

/**
 * @property string $vendor
 * @property string $name
 * @property string|null $repository
 * @property string|null $branch
 * @property string|null $targetVersion
 * @property Templates|null $templates
 * @property Destination $destination
 * @property Namespace_ $namespace
 * @property QA $qa
 * @property State $state
 * @property array<FileGenerator> $generators
 */
interface Package
{
}
