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
     * @var string
     */
    private $ip = '';
    
    /**
     * @var RemoteAddress
     */
    private $remoteAddress;
    
    /**
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        RemoteAddress $remoteAddress
    ) {
        $this->remoteAddress = $remoteAddress;
    }
    
    /**
     * @param string $ip
     */
    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    /**
     * Get the current IP address
     *
     * @return string
     */
    public function getValue(): string
    {
        if (!empty($this->ip)) {
            return $this->ip;
        }

        $this->ip = $this->remoteAddress->getRemoteAddress();

        return (string)$this->ip;
    }
}
