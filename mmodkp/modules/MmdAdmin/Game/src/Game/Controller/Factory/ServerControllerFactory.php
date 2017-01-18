<?php

namespace MmdAdmin\Game\Controller\Factory;

use Interop\Container\ContainerInterface;
use Mmd\Game\Service\ServerService;
use Mmd\Util\ServiceLocator\ExtractServiceLocatorTrait;
use MmdAdmin\Game\Controller\ServerController;
use Epos\UserCore\Service\UserService;

/**
 * Class ServerControllerFactory
 *
 * @package MmdAdmin\Game\Controller\Factory
 */
class ServerControllerFactory
{
    use ExtractServiceLocatorTrait;

    /**
     * @param ContainerInterface $container
     *
     * @return ServerController
     */
    public function __invoke(ContainerInterface $container)
    {
        $sm = $this->extractServiceLocator($container);

        $userService   = $sm->get(UserService::class);
        $serverService = $sm->get(ServerService::class);
        $formContainer = $sm->get('FormElementManager');

        return new ServerController($userService, $serverService, $formContainer);
    }
}
