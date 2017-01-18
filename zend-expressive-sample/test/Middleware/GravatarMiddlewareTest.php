<?php

namespace Middleware;

use App\Middleware\GravatarMiddleware;
use App\Service\Gravatar\GravatarService;
use Mockery;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class GravatarMiddlewareTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GravatarMiddleware
     */
    protected $middleware;

    /**
     * @var Mockery\MockInterface
     */
    protected $serviceMock;

    protected function setUp()
    {
        $this->serviceMock = Mockery::mock(GravatarService::class);
        $this->middleware  = new GravatarMiddleware($this->serviceMock);
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function Middleware_ReturnsOkResponseWithBodyContainsUri_WhenValidEmailProvided()
    {
        $email    = 'some.associated.with.gravatar@mail.com';
        $request  = new ServerRequest();
        $request  = $request->withAttribute('email', $email);
        $response = new Response();

        $expectedCode = 200;
        $expectedBody = 'https://gravatar.com/avatar/some-generated-hash.jpg';

        $this->serviceMock->shouldReceive('find')->with($email)->andReturn($expectedBody);

        $result = $this->middleware->__invoke($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals($expectedCode, $result->getStatusCode());
        $this->assertEquals($expectedBody, (string)$result->getBody());
    }

    /**
     * @test
     */
    public function Middleware_ReturnsErrorResponseWithEmptyBody_WhenInvalidEmailProvided()
    {
        $email    = 'some.not.associated.with.gravatar@mail.com';
        $request  = new ServerRequest();
        $request  = $request->withAttribute('email', $email);
        $response = new Response();

        $expectedCode = 422;

        $this->serviceMock->shouldReceive('find')->with($email)->andReturnNull();

        $result = $this->middleware->__invoke($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals($expectedCode, $result->getStatusCode());
        $this->assertEmpty((string)$result->getBody());
    }

    /**
     * @test
     * @depends Middleware_ReturnsErrorResponseWithEmptyBody_WhenInvalidEmailProvided
     */
    public function Middleware_MarshalToNextCallable_WhenInvalidEmailProvidedAndNextCallableDefined()
    {
        $email    = 'some.not.associated.with.gravatar@mail.com';
        $request  = new ServerRequest();
        $request  = $request->withAttribute('email', $email);
        $response = new Response();

        $expectedCode = 422;

        $this->serviceMock->shouldReceive('find')->with($email)->andReturnNull();

        $nextCallable = function (RequestInterface $request, ResponseInterface $response) use ($expectedCode) {
            $this->assertEquals($expectedCode, $response->getStatusCode());
        };

        $result = $this->middleware->__invoke($request, $response, $nextCallable);
    }

}
