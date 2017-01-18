<?php
/**
 * File contains class GravatarMiddleware
 */

namespace App\Middleware;

use App\Service\Gravatar\GravatarService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

/**
 * Class GravatarMiddleware
 */
class GravatarMiddleware implements MiddlewareInterface
{

    /**
     * @var GravatarService
     */
    protected $service;

    /**
     * GravatarMiddleware constructor.
     *
     * @param GravatarService $service
     */
    public function __construct(GravatarService $service)
    {
        $this->service = $service;
    }

    /**
     * Process an incoming request and/or response.
     *
     * Accepts a server-side request and a response instance, and does
     * something with them.
     *
     * If the response is not complete and/or further processing would not
     * interfere with the work done in the middleware, or if the middleware
     * wants to delegate to another process, it can use the `$out` callable
     * if present.
     *
     * If the middleware does not return a value, execution of the current
     * request is considered complete, and the response instance provided will
     * be considered the response to return.
     *
     * Alternately, the middleware may return a response instance.
     *
     * Often, middleware will `return $out();`, with the assumption that a
     * later middleware will return a response.
     *
     * @param Request       $request
     * @param Response      $response
     * @param null|callable $out
     *
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $email = $request->getAttribute('email');

        $uri = $this->service->find($email);

        if (null !== $uri) {
            $body = $response->getBody();
            $body->write($uri);

            return $response->withStatus(200)->withBody($body);
        }

        $reasonPhrase = sprintf('Provided email `%s` have no associated gravatar', $email);
        $response     = $response->withStatus(422, $reasonPhrase);

        if (is_callable($out)) {
            return $out($request, $response);
        }

        return $response;
    }
}
