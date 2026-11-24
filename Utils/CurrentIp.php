<?php declare(strict_types=1);

/**
 * Yireo SalesBlock2ByIp for Magento
 *
 * @package     Yireo_SalesBlock2ByIp
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2ByIp\Utils;

use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class CurrentIp
{
    /**
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        private readonly RemoteAddress $remoteAddress
    ) {
    }

    /**
     * Get the current IP address
     *
     * @return string
     */
    public function getValue(): string
    {
        return (string)$this->remoteAddress->getRemoteAddress();
    }
}
