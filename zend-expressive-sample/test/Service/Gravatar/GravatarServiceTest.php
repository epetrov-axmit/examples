<?php

namespace Service\Gravatar;

use App\Service\Gravatar\GravatarService;
use Mockery;
use PHPUnit_Framework_TestCase;
use Zend\Validator\ValidatorInterface;

class GravatarServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GravatarService
     */
    private $service;

    /**
     * @var string
     */
    private $endpoint = 'http://www.gravatar.com';

    /**
     * @var Mockery\MockInterface
     */
    private $validatorMock;

    protected function setUp()
    {
        $this->validatorMock = Mockery::mock(ValidatorInterface::class);
        $this->service       = new GravatarService($this->endpoint, $this->validatorMock);
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function Find_ReturnsNull_WhenAvatarAssociatedWithProvidedEmailNotFound()
    {
        $email = 'some.invalid@mail.com';
        $this->validatorMock->shouldReceive('isValid')->andReturn(false);

        $this->assertNull($this->service->find($email));
    }

    /**
     * @test
     */
    public function Find_ReturnsUriToGravatarContainingHash_WhenAvatarAssociatedWithEmailExists()
    {
        $email = 'some.valid@mail.com';
        $this->validatorMock->shouldReceive('isValid')->andReturn(true);

        $this->assertEquals($this->createHashedGravatarUri($email, 'jpg'), $this->service->find($email));
    }

    /**
     * @param string $email
     *
     * @return string
     */
    private function createHashedGravatarUri($email, $extension)
    {
        $pattern = '%s/%s/%s.%s';
        return sprintf(
            $pattern,
            rtrim($this->endpoint, '/'),
            trim(GravatarService::AVATAR_PATH, '/'),
            md5(strtolower(trim($email))),
            $extension
        );
    }

}
