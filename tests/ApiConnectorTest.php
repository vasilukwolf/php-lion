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
}
