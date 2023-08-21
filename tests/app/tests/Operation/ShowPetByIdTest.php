<?php

declare (strict_types=1);
namespace ApiClients\Tests\Client\PetStore\Operation;

use ApiClients\Client\PetStore\Error as ErrorSchemas;
use ApiClients\Client\PetStore\Hydrator;
use ApiClients\Client\PetStore\Operation;
use ApiClients\Client\PetStore\Operator;
use ApiClients\Client\PetStore\Schema;
use ApiClients\Client\PetStore\WebHook;
use ApiClients\Client\PetStore\Router;
use League\OpenAPIValidation;
use React\Http;
use ApiClients\Contracts;
/**
 * @covers \ApiClients\Client\PetStore\Operation\ShowPetById
 */
final class ShowPetByIdTest extends \WyriHaximus\AsyncTestUtilities\AsyncTestCase
{
    /**
     * @test
     */
    public function call_httpCode_default_responseContentType_application_json_zero()
    {
        $response = new \React\Http\Message\Response(999, array('Content-Type' => 'application/json'), json_encode(json_decode(Schema\Error::SCHEMA_EXAMPLE_DATA, true)));
        $auth = $this->prophesize(\ApiClients\Contracts\HTTP\Headers\AuthenticationInterface::class);
        $auth->authHeader(\Prophecy\Argument::any())->willReturn('Bearer beer')->shouldBeCalled();
        $browser = $this->prophesize(\React\Http\Browser::class);
        $browser->withBase(\Prophecy\Argument::any())->willReturn($browser->reveal());
        $browser->withFollowRedirects(\Prophecy\Argument::any())->willReturn($browser->reveal());
        $browser->request('GET', '/pets/{petId}', \Prophecy\Argument::type('array'), \Prophecy\Argument::any())->willReturn(\React\Promise\resolve($response))->shouldBeCalled();
        $client = new \ApiClients\Client\PetStore\Client($auth->reveal(), $browser->reveal());
        $result = $client->call(Operation\ShowPetById::OPERATION_MATCH, (static function (array $data) : array {
            return $data;
        })(array()));
    }
    /**
     * @test
     */
    public function operations_httpCode_default_responseContentType_application_json_zero()
    {
        $response = new \React\Http\Message\Response(999, array('Content-Type' => 'application/json'), json_encode(json_decode(Schema\Error::SCHEMA_EXAMPLE_DATA, true)));
        $auth = $this->prophesize(\ApiClients\Contracts\HTTP\Headers\AuthenticationInterface::class);
        $auth->authHeader(\Prophecy\Argument::any())->willReturn('Bearer beer')->shouldBeCalled();
        $browser = $this->prophesize(\React\Http\Browser::class);
        $browser->withBase(\Prophecy\Argument::any())->willReturn($browser->reveal());
        $browser->withFollowRedirects(\Prophecy\Argument::any())->willReturn($browser->reveal());
        $browser->request('GET', '/pets/{petId}', \Prophecy\Argument::type('array'), \Prophecy\Argument::any())->willReturn(\React\Promise\resolve($response))->shouldBeCalled();
        $client = new \ApiClients\Client\PetStore\Client($auth->reveal(), $browser->reveal());
        $result = $client->operations()->showPetById();
    }
}
