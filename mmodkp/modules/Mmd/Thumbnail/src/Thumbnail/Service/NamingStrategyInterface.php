<?php

namespace Mmd\Thumbnail\Service;

interface NamingStrategyInterface
{

    /**
     * Normalizes name due to specific rules
     *
     * @param string $name
     *
     * @return string
     */
    public function normalize($name);

}