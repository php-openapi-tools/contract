<?php

declare(strict_types=1);

namespace OpenAPITools\Contract\Voter;

use OpenAPITools\Representation;

use function array_key_exists;

abstract class AbstractListOperation implements ListOperation
{
    final public static function list(Representation\Operation $operation): bool
    {
        foreach ($operation->response as $response) {
            if ($response->code === 200 && $response->content instanceof Representation\Schema) {
                return false;
            }

            if ($response->code === 200 && $response->content instanceof Representation\Property\Type && $response->content->type !== 'array') {
                return false;
            }
        }

        $match = [];
        foreach (static::keys() as $key) {
            $match[$key] = false;
        }

        foreach ($operation->parameters as $parameter) {
            if (! array_key_exists($parameter->name, $match)) {
                continue;
            }

            if ($parameter->location !== 'query') {
                continue;
            }

            $match[$parameter->name] = true;
        }

        foreach ($match as $matched) {
            if ($matched === false) {
                return false;
            }
        }

        return true;
    }
}
