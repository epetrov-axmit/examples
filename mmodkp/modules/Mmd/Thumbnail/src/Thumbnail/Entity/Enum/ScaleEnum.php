<?php

namespace Mmd\Thumbnail\Entity\Enum;

use Application\Enum\EnumType;

/**
 * Class ScaleEnum
 *
 * @package Mmd\Thumbnail\Entity\Enum
 */
class ScaleEnum extends EnumType
{

    const VALUE_ICON   = 'icon';
    const VALUE_SMALL  = 'small';
    const VALUE_MEDIUM = 'medium';
    const VALUE_LARGE  = 'large';

    /**
     * @var string
     */
    protected $default = self::VALUE_ICON;

    /**
     * @var string
     */
    protected $name = 'ThumbnailScaleEnum';

    /**
     * Returns supported scales
     *
     * @return array
     */
    public static function getSupportedScales()
    {
        return [
            static::VALUE_ICON,
            static::VALUE_SMALL,
            static::VALUE_MEDIUM,
            static::VALUE_LARGE
        ];
    }

}