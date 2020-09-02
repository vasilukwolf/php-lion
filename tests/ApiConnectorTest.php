<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ApiConnectorTest extends TestCase
{
    public function testApiLoginSuccess(): void
    {
        $mock = new MockHandler(
            [
                new Response(200, [], '{"status": "OK", "token": "dsfd79843r32d1d3dx23d32d"}'),
            ]
        );
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $c = new Lion\ApiConnector(['base_uri' => 'https://testapi.org']);

        // Set mocked guzzle client into API connector.
        $reflection = new ReflectionClass($c);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($c, $client);

        $c->login('tests', '12345');
        $property = $reflection->getProperty('token');
        $property->setAccessible(true);
        $token = $property->getValue($c);
        $this->assertEquals($token, 'dsfd79843r32d1d3dx23d32d');

        /** @var Request $request */
        $request = $mock->getLastRequest();
        $this->assertSame('GET', $request->getMethod(), 'method not equal');
        $this->assertSame('/auth', $request->getUri()->getPath(), 'URI path not equal');
        $this->assertIsBool(
            $request->hasHeader('Authorization'),
            'request must be contains authorization header'
        );
        $this->assertSame(
            'Basic dGVzdHM6MTIzNDU=', // tests:12345
            $request->getHeader('Authorization')[0],
            'authorization not equal'
        );
    }

    public function testGetUserSuccess(): void
    {
        $user = new Lion\User();
        $user->id = 42;
        $user->active = '1';
        $user->blocked = false;
        $user->name = 'tests';
        $user->permissions = [
            new \Lion\Permission(['id' => 105]),
            new \Lion\Permission(['id' => 106]),
        ];

        $mock = new MockHandler(
            [
                new Response(200, [], '{"status": "OK", "token": "dsfd79843r32d1d3dx23d32d"}'),
                new Response(200, [], self::makeUserResponse($user)),
            ]
        );
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $c = new Lion\ApiConnector(['base_uri' => 'https://testapi.org']);

        // Set mocked guzzle client into API connector.
        $reflection = new ReflectionClass($c);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($c, $client);

        $c->login('tests', '123456');
        $this->assertEquals($user, $c->getUser($user->name));

        /** @var Request $request */
        $request = $mock->getLastRequest();
        $this->assertSame('GET', $request->getMethod(), 'method not equal');
        $this->assertSame(
            '/get-user/' . $user->name . '?token=dsfd79843r32d1d3dx23d32d',
            (string)$request->getUri(),
            'URI not equal'
        );
    }

    private static function makeUserResponse($user = [], $status = 'OK'): string
    {
        $array = json_decode(json_encode($user), true);
        $array['status'] = $status;
        return json_encode($array);
    }
}
