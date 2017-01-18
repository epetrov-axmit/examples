<?php

namespace Mmd\Thumbnail\Helper;

use AwsModule\View\Helper\S3Link;
use RuntimeException;

/**
 * Class AwsS3SourceRenderer
 *
 * @package Mmd\Thumbnail\Helper
 */
class AwsS3SourceRenderer implements SourceRendererInterface
{
    /**
     * @var string
     */
    protected $bucket;

    /**
     * @var S3Link
     */
    protected $s3Helper;

    /**
     * AwsS3SourceRenderer constructor.
     *
     * @param S3Link $s3Helper
     * @param string $bucket
     */
    public function __construct(S3Link $s3Helper, $bucket = null)
    {
        $this->s3Helper = $s3Helper;

        if ($bucket) {
            $this->bucket = $bucket;
        }
    }

    /**
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param string $bucket
     *
     * @return AwsS3SourceRenderer
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;

        return $this;
    }

    public function assembleWebPath($source)
    {
        $helper = $this->s3Helper;

        if (empty($this->bucket)) {
            throw new RuntimeException('Bucket is not defined');
        }

        return $helper($source, $this->bucket);
    }
}
