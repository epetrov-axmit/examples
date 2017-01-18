<?php
/**
 * File contains class ExistsValidator
 */

namespace App\Service\Gravatar;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Request;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

/**
 * Class ExistsValidator
 *
 * @package App\Service\Gravatar
 */
class IsReachableValidator extends AbstractValidator
{

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * ExistsValidator constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     *
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        $uri = Uri::fromParts(parse_url($value));
        $uri = Uri::withQueryValue($uri, 'd', '404');

        try {
            $response = $this->client->request('GET', $uri);
        } catch (RequestException $ex) {
            $response = $ex->getResponse();
        }

        return $this->isSuccessfulResponse($response);
    }

    /**
     * Checks if provided response is successful
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private function isSuccessfulResponse(ResponseInterface $response)
    {
        return $response->getStatusCode() === 200;
    }
}
