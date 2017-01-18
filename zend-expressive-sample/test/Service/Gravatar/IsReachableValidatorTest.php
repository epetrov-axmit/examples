<?php

namespace AppTest\Service\Gravatar;

use App\Service\Gravatar\IsReachableValidator;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\UriInterface;

class IsReachableValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    private $clientMock;

    /**
     * @var IsReachableValidator
     */
    private $validator;

    protected function setUp()
    {
        $this->clientMock = Mockery::mock(ClientInterface::class);
        $this->validator  = new IsReachableValidator($this->clientMock);
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function Validator_AddsAdditional404QueryParameter_BeforeValidation()
    {
        $testUri                  = 'http://www.gravatar.com/avatar/kjsd382hcslkn38';
        $additionalParameterName  = 'd';
        $additionalParameterValue = '404';

        $this->clientMock->shouldReceive('request')->andReturnUsing(
            function ($method, UriInterface $uri) use ($additionalParameterName, $additionalParameterValue) {
                $params = [];
                parse_str($uri->getQuery(), $params);

                $this->assertSame('GET', $method);
                $this->assertArrayHasKey($additionalParameterName, $params);
                $this->assertEquals($additionalParameterValue, $params[$additionalParameterName]);

                return new Response();
            }
        );

        $this->validator->isValid($testUri);
    }

    /**
     * @test
     * @depends      Validator_AddsAdditional404QueryParameter_BeforeValidation
     * @dataProvider isValidValidationSuccessTestDataProvider
     */
    public function IsValid_ValidationSuccess_WhenResponseCodeIs200($expected, $responseCode)
    {
        $testUri  = 'http://www.gravatar.com/avatar/kjsd382hcslkn38';
        $response = new Response($responseCode);

        $this->clientMock->shouldReceive('request')->andReturn($response);

        $this->assertEquals($expected, $this->validator->isValid($testUri));
    }

    public function isValidValidationSuccessTestDataProvider()
    {
        return [
            [true, 200],
            [false, 201],
            [false, 300],
            [false, 301],
            [false, 400],
            [false, 401],
            [false, 100],
            [false, 101],
            [false, 500],
            [false, 501]
        ];
    }

}
