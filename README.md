# contract

Shared interfaces for [OpenAPI Tools](https://github.com/php-openapi-tools) code generators. Defines the contracts that configuration value objects implement and that file generators, voters, and generated webhook handlers depend on.

![Continuous Integration](https://github.com/php-openapi-tools/contract/workflows/Continuous%20Integration/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/openapi-tools/contract/v/stable.png)](https://packagist.org/packages/openapi-tools/contract)
[![Total Downloads](https://poser.pugx.org/openapi-tools/contract/downloads.png)](https://packagist.org/packages/openapi-tools/contract/stats)
[![License](https://poser.pugx.org/openapi-tools/contract/license.png)](https://packagist.org/packages/openapi-tools/contract)

## Installation

To install via [Composer](https://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require openapi-tools/contract
```

## Components

| Class | Purpose |
| --- | --- |
| `Package` | Generated package definition: metadata, destination, namespace, QA, state, and file generators |
| `Package\Metadata` | Composer package name, description, and keywords |
| `Package\Destination` | Output directory layout for source and tests |
| `Package\Templates` | Template directory and optional variables |
| `Package\QA` | QA tool configuration for phpcs, phpstan, and psalm |
| `Package\QA\Tool` | Enable flag and optional config file path for a QA tool |
| `Package\State` | Per-package state, including additional files to preserve |
| `FileGenerator` | Generates `File` instances from a package and namespaced representation |
| `SectionGenerator` | Maps paths and webhooks to configuration section identifiers |
| `ContentType` | Declares supported content types and parses request/response expressions |
| `WebHookHandlerInterface` | Contract implemented by generated webhook event handlers |
| `Voter\ListOperation` | Identifies paginated list operations by required query parameters |
| `Voter\AbstractListOperation` | Base implementation for `ListOperation` voters |
| `OpenAPITools\Voter\StreamOperation` | Identifies streaming operations during gathering |

## Usage

### Package contract

[`openapi-tools/configuration`](https://github.com/php-openapi-tools/configuration) provides the concrete `Package` value object. Generators accept any object implementing `Contract\Package`:

```php
use OpenAPITools\Contract\Package;
use OpenAPITools\Representation\Namespaced\Representation;

/** @param Package $package */
function runGenerators(Package $package, Representation $representation): void
{
    foreach ($package->generators as $generator) {
        foreach ($generator->generate($package, $representation) as $file) {
            // write $file to $package->destination
        }
    }
}
```

The interface exposes `metadata`, `vendor`, `name`, `repository`, `branch`, `targetVersion`, `templates`, `destination`, `namespace`, `qa`, `state`, and `generators` properties.

### File generators

Implement `FileGenerator` to emit PHP source files from a namespaced representation. Generators such as `OpenAPITools\Generator\Schema\Schema` and `OpenAPITools\Generator\PSR15\WebHook\WebHookMiddleware` follow this contract:

```php
use OpenAPITools\Contract\FileGenerator;
use OpenAPITools\Contract\Package;
use OpenAPITools\Representation\Namespaced\Representation;
use OpenAPITools\Utils\File;

final readonly class ExampleGenerator implements FileGenerator
{
    /** @return iterable<File> */
    public function generate(Package $package, Representation $representation): iterable
    {
        foreach ($representation->schemas as $schema) {
            yield new File(
                $package->destination->source,
                $schema->className->fullyQualified->source,
                $classNode,
                File::DO_LOAD_ON_WRITE,
            );
        }
    }
}
```

Attach generators to a package configuration and run them through `OpenAPITools\Generator\Generator`.

### Webhook handlers

Generated webhook event classes implement `WebHookHandlerInterface`. Each handler receives a hydrated payload object and returns a PSR-7 response:

```php
use OpenAPITools\Contract\WebHookHandlerInterface;
use Psr\Http\Message\ResponseInterface;

final readonly class UserCreatedHandler implements WebHookHandlerInterface
{
    public function __construct(
        private ResponseFactory $responseFactory,
    ) {
    }

    public function handle(object $payload): ResponseInterface
    {
        // $payload is a generated schema instance
        return $this->responseFactory->createResponse(204);
    }
}
```

### List operation voters

Extend `AbstractListOperation` to mark operations as paginated lists during gathering. The base class checks that the 200 response is an array and that all query parameters from `keys()` are present:

```php
use OpenAPITools\Contract\Voter\AbstractListOperation;

final class PagePerPageListOperation extends AbstractListOperation
{
    public static function incrementorKey(): string
    {
        return 'page';
    }

    public static function incrementorInitialValue(): int
    {
        return 1;
    }

    /** @return array<string> */
    public static function keys(): array
    {
        return ['page', 'per_page'];
    }
}
```

Register voter class names on `OpenAPITools\Configuration\Gathering\Voter`:

```php
use OpenAPITools\Configuration\Gathering\Voter;

new Voter(
    listOperation: [PagePerPageListOperation::class],
    streamOperation: ['streamEvents'],
);
```

### Section generators

Implement `SectionGenerator` to split a spec into named configuration sections. Return a section identifier for a path or webhook, or `false` when the item should be skipped:

```php
use OpenAPITools\Contract\SectionGenerator;
use OpenAPITools\Representation\Path;
use OpenAPITools\Representation\WebHook;

final class ApiSectionGenerator implements SectionGenerator
{
    public static function path(Path $path): string|false
    {
        return str_starts_with($path->operations[0]->path, '/v2/')
            ? 'v2'
            : 'v1';
    }

    public static function webHook(WebHook ...$webHooks): string|false
    {
        return 'webhooks';
    }
}
```

### Content types

Implement `ContentType` when a generator needs custom parsing for non-JSON request or response bodies. Declare supported MIME types and transform AST expressions during code generation:

```php
use OpenAPITools\Contract\ContentType;
use PhpParser\Node\Expr;

final class JsonContentType implements ContentType
{
    /** @return iterable<string> */
    public static function contentType(): iterable
    {
        yield 'application/json';
    }

    public static function parse(Expr $expr): Expr
    {
        // return an AST expression that parses the payload
        return $expr;
    }
}
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT)

Copyright (c) 2026 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
