<?php

namespace Mmd\Thumbnail\Options\Factory;

use Mmd\Thumbnail\Entity\Enum\ScaleEnum;
use Mmd\Thumbnail\Options\ImageOptions;
use RuntimeException;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ThumbnailOptionsAbstractFactory implements AbstractFactoryInterface
{

    const PREFIX = 'mmd.thumbnail.options';

    protected $defined = [];

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if ($options = $this->defineRequestedOptions($requestedName)) {
            $this->defined[$name] = $options;
        }

        return (bool)$options;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $serviceLocator->get('mmd-assets-config')['thumbnails'];
        $config = isset($config[$this->defined[$name]]) && is_array($config[$this->defined[$name]])
            ? $config[$this->defined[$name]]
            : [];

        if(empty($config)) {
            throw new RuntimeException(
                sprintf(
                    'Config not found for [%s] options. Must be defined under [%s]',
                    $this->defined[$name],
                    'mmd-assets -> thumbnails -> ' . $this->defined[$name]
                )
            );
        }

        $options = new ImageOptions();

        if (isset($config['width'])) {
            $options->setWidth($config['width'])->setHeight($config['width']);
        }

        if (isset($config['height'])) {
            $options->setHeight($config['height']);
        }

        if (!isset($config['dir'])) {
            throw new RuntimeException(
                sprintf(
                    'Source dir must be defined under [%s] config',
                    'mmd-assets -> thumbnails -> ' . $this->defined[$name] . ' -> dir'
                )
            );
        }

        if (isset($config['format'])) {
            $options->setFormat($config['format']);
        }

        $options->setDir($config['dir']);

        return $options;
    }

    /**
     * Defines if requested name has valid prefix
     *
     * @param string $requestedName
     *
     * @return null|string
     */
    protected function defineRequestedOptions($requestedName)
    {
        if (!preg_match('@^' . static::PREFIX . '@iu', $requestedName)) {
            return null;
        }

        $parts   = explode('.', $requestedName);
        $options = array_pop($parts);

        return in_array($options, ScaleEnum::getSupportedScales()) ? $options : null;
    }

}