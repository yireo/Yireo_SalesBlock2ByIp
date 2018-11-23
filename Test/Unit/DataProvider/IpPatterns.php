<?php
/**
 * Yireo SalesBlock2ByIp for Magento
 *
 * @package     Yireo_SalesBlock2ByIp
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types=1);

namespace Yireo\SalesBlock2ByIp\Test\Unit\DataProvider;

/**
 * Class IpPatterns
 * @package Yireo\SalesBlock2ByIp\Test\Unit\DataProvider
 */
class IpPatterns
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            ['192.168.1.5', '192.168.1.5', true],
            ['192.168.1.5', '192.168.1.0/24', true],
            ['192.168.1.5', '192.168.2.5', false],
            ['192.168.1.5', '192.168.1.*', false],
            ['192.168.1.5', '192.168.2', false],
        ];
    }
}
