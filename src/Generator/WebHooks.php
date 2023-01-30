<?php

namespace ApiClients\Tools\OpenApiClientGenerator\Generator;

use ApiClients\Contracts\HTTP\Headers\AuthenticationInterface;
use ApiClients\Contracts\OpenAPI\WebHookInterface;
use ApiClients\Tools\OpenApiClientGenerator\File;
use cebe\openapi\spec\Operation as OpenAPiOperation;
use cebe\openapi\spec\PathItem;
use Jawira\CaseConverter\Convert;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use PhpParser\Builder\Param;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Browser;
use RingCentral\Psr7\Request;

final class WebHooks
{
    /**
     * @param string $path
     * @param string $namespace
     * @param string $baseNamespace
     * @param string $className
     * @param PathItem $pathItem
     * @return iterable<Node>
     * @throws \Jawira\CaseConverter\CaseConverterException
     */
    public static function generate(string $namespace, string $baseNamespace, array $eventClassNameMapping): iterable
    {
        $factory = new BuilderFactory();
        $stmt = $factory->namespace($namespace);

        $class = $factory->class('WebHooks')->makeFinal()->addStmt(
            new Node\Stmt\ClassConst(
                [
                    new Node\Const_(
                        new Node\Name('EVENT_CLASS_MAPPING'),
                        new Node\Expr\Array_((static function (array $eventClassNameMapping): array {
                            $array = [];

                            foreach ($eventClassNameMapping as $key => $value) {
                                $array[] = new Node\Expr\ArrayItem(new Node\Scalar\String_($value), new Node\Scalar\String_($key));
                            }

                            return $array;
                        })($eventClassNameMapping))
                    ),
                ],
                Class_::MODIFIER_PUBLIC
            )
        )->addStmt(
            $factory->property('hydrator')->setType('\\' . $namespace . 'OptimizedHydratorMapper')->makeReadonly()->makePrivate()
        )->addStmt(
            $factory->method('__construct')->makePublic()->addStmt(
                new Node\Expr\Assign(
                    new Node\Expr\PropertyFetch(
                        new Node\Expr\Variable('this'),
                        'hydrator'
                    ),
                    new Node\Expr\New_(
                        new Node\Name('\\' . $namespace . 'OptimizedHydratorMapper'),
                        [
                        ]
                    ),
                )
            )
        )->addStmt(
            $factory->method('resolve')->makePublic()->makeStatic()->setReturnType('\\' . WebHookInterface::class)->addParam(
                (new Param('event'))->setType('string')
            )->addStmt(
                new Node\Stmt\If_(
                    new Node\Expr\BooleanNot(
                        new Node\Expr\FuncCall(
                            new Node\Name('array_key_exists'),
                            [
                                new Node\Arg(
                                    new Node\Expr\Variable('event')
                                ),
                                new Node\Expr\ClassConstFetch(
                                    new Node\Name('self'),
                                    new Node\Name('EVENT_CLASS_MAPPING'),
                                ),
                            ]
                        )
                    ),
                    [
                        'stmts' => [
                            new Node\Stmt\Throw_(
                                new Node\Expr\New_(
                                    new Node\Name('\InvalidArgumentException')
                                )
                            )
                        ],
                    ]
                )
            )->addStmt(
                new Node\Expr\Assign(
                    new Node\Expr\Variable(
                        new Node\Name('class')
                    ),
                    new Node\Expr\ArrayDimFetch(
                        new Node\Expr\ClassConstFetch(
                            new Node\Name('self'),
                            new Node\Name('EVENT_CLASS_MAPPING'),
                        ),
                        new Node\Expr\Variable('event')
                    )
                )
            )->addStmt(new Node\Stmt\Return_(
                new Node\Expr\New_(
                    new Node\Expr\Variable(
                        new Node\Name('class')
                    )
                )
            ))
        )->addStmt(
            $factory->method('hydrate')->makePublic()->setReturnType('object')->addParam(
                (new Param('event'))->setType('string')
            )->addParam(
                (new Param('data'))->setType('array')
            )->addStmt(new Node\Stmt\Return_(
                new Node\Expr\MethodCall(
                    new Node\Expr\PropertyFetch(
                        new Node\Expr\Variable('this'),
                        'hydrator'
                    ),
                    new Node\Name('hydrateObject'),
                    [
                        new Node\Arg(new Node\Expr\MethodCall(
                            new Node\Expr\StaticCall(
                                new Node\Name('self'),
                                new Node\Name('resolve'),
                                [
                                    new Node\Arg(new Node\Expr\Variable('event')),
                                ]
                            ),
                            new Node\Name('resolve'),
                            [
                                new Node\Arg(new Node\Expr\Variable('data')),
                            ]
                        )),
                        new Node\Arg(new Node\Expr\Variable('data')),
                    ]
                )
            ))
        );
        yield new File($namespace . '\\' . 'WebHooks', $stmt->addStmt($class)->getNode());
    }
}
