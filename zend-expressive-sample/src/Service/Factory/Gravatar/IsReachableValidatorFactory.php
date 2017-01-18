<?php
/**
 * File contains class IsReachableValidatorFactory
 */

namespace App\Service\Factory\Gravatar;

use App\Service\Gravatar\IsReachableValidator;
use GuzzleHttp\Client;
use Interop\Container\ContainerInterface;

/**
 * Class IsReachableValidatorFactory
 *
 * @package App\Service\Factory\Gravatar
 */
class IsReachableValidatorFactory
{
    /**
     * Validator factory
     *
     * @param ContainerInterface $container
     *
     * @return IsReachableValidator
     */
    public function __invoke(ContainerInterface $container)
    {
        $config        = $container->has('config') ? $container->get('config') : [];
        $clientOptions = $this->extractClientOption($config);
        $client        = new Client($clientOptions);

        return new IsReachableValidator($client);
    }

    /**
     * @param $config
     *
     * @return array
     */
    private function extractClientOption($config)
    {
        $options = [];

        if (isset($config['gravatar']['http_client']) && is_array($config['gravatar']['http_client'])) {
            $options = $config['gravatar']['http_client'];
        }

        return $options;
    }
}
