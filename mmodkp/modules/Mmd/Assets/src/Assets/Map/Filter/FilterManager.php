<?php

namespace Mmd\Assets\Map\Filter;

use Mmd\Assets\Map\Filter\Factory\IeOnlyFilterFactory;
use RuntimeException;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;

/**
 * Class FilterManager
 *
 * @package Mmd\Assets\Map\Filter
 */
class FilterManager extends AbstractPluginManager
{

    /**
     * @var array
     */
    protected $aliases
        = [
            'ieonly' => IeOnlyFilter::class,
        ];

    /**
     * @var array
     */
    protected $factories
        = [
            IeOnlyFilter::class => IeOnlyFilterFactory::class,
        ];

    /**
     * @var bool
     */
    protected $shareByDefault = true;

    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     *
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof FilterInterface) {
            return;
        }

        throw new RuntimeException(
            sprintf(
                'Instance of `%s` expected, got `%s`',
                FilterInterface::class,
                is_object($plugin) ? get_class($plugin) : gettype($plugin)
            )
        );
    }
}
