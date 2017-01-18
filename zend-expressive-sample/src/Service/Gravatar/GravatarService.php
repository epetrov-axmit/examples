<?php
/**
 * File contains class GravatarService
 */

namespace App\Service\Gravatar;

use Zend\Validator\ValidatorInterface;

/**
 * Class GravatarService
 *
 * @package App\Service\Gravatar
 */
class GravatarService
{
    const AVATAR_PATH = '/avatar';

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * GravatarService constructor.
     *
     * @param string             $endpoint
     * @param ValidatorInterface $validator
     */
    public function __construct($endpoint, ValidatorInterface $validator)
    {
        $this->endpoint  = $endpoint;
        $this->validator = $validator;
    }

    /**
     * Assembles gravatar uri by provided email
     *
     * @param string $email
     * @param string $extension
     *
     * @return null|string Returns NULL if there is no gravatar avatar associated with given email
     */
    public function find($email, $extension = 'jpg')
    {
        $uri = sprintf('%s.%s', $this->makeAvatarUri($email), $extension);

        if (!$this->validator->isValid($uri)) {
            return null;
        }

        return $uri;
    }

    /**
     * Generates Gravatar hash by provided email
     *
     * @param string $email
     *
     * @return string
     */
    private function hash($email)
    {
        return md5(strtolower(trim($email)));
    }

    /**
     * Assembles URI to gravatar avatar by provided email
     *
     * @param string $email
     *
     * @return string
     */
    private function makeAvatarUri($email)
    {
        $pattern = '%s/%s/%s';

        return sprintf(
            $pattern,
            rtrim($this->endpoint, '/'),
            trim(static::AVATAR_PATH, '/'),
            trim($this->hash($email), '/')
        );
    }
}
