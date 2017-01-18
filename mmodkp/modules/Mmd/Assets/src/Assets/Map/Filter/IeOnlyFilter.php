<?php

namespace Mmd\Assets\Map\Filter;

use Application\Helper\IeHeaderAnalyzer;
use Mmd\Assets\Map\File;
use Zend\Http\Request as HttpRequest;
use Zend\Stdlib\RequestInterface as Request;

/**
 * Class IeOnlyFilter
 *
 * @package Mmd\Assets\Map\Filter
 */
class IeOnlyFilter implements FilterInterface
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * IeOnlyFilter constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Checks if current filter is satisfies for current conditions
     *
     * @param File $asset
     *
     * @return bool
     */
    public function isSatisfy(File $asset)
    {
        if (!$this->request instanceof HttpRequest) {
            return false;
        }

        $userAgent = $this->request->getHeader('User-Agent');

        if (!$userAgent) {
            return false;
        }

        return IeHeaderAnalyzer::isInternetExplorer($userAgent);
    }
}
