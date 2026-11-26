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
    public static function getData(): array
    {
        return [
            ['192.168.1.5', '192.168.1.5', true],
            ['192.168.1.5', '192.168.1.0/24', true],
            ['192.168.1.5', '192.168.2.5', false],
            ['192.168.1.5', '192.168.1.*', false],
            ['192.168.1.5', '192.168.2', false],
            ['192.168.1.5', '*', true],
            ['192.168.1.5', ' 192.168.1.5 ', true],
            ['192.168.1.5', '192.168.1.1 - 192.168.1.10', true],
            ['192.168.1.5', '192.168.1.6 - 192.168.1.10', false],
            ['192.168.1.5', '0.0.0.0/0', true],
            ['192.168.1.5', '192.168.1.0/33', false],
            ['192.168.1.5', '192.168.1.0/24x', false],
            ['192.168.1.5', 'x192.168.1.0/24', false],
            ['192.168.1.5', '192.168.1/24', false],
            ['192.168.1.5', 'foo/24', false],
            ['192.168.1.5', '', false],
            ['192.168.1.5', '2001:db8::/32', false],
            ['', '192.168.1.0/24', false],
            ['', '', false],
            ['not-an-ip', 'not-an-ip', false],
            ['192.168.1.5', '::ffff:192.168.1.5', true],
            ['::ffff:192.168.1.5', '192.168.1.5', true],
            ['::ffff:192.168.1.5', '192.168.1.0/24', true],
            ['2001:db8::1', '2001:db8::1', true],
            ['2001:db8::1', '2001:DB8::1', true],
            ['2001:db8::1', '2001:0db8:0000:0000:0000:0000:0000:0001', true],
            ['2001:db8::1', '2001:db8::2', false],
            ['2001:db8::1', '2001:db8::/32', true],
            ['2001:db8::1', '2001:db8::0/32', true],
            ['2001:db9::1', '2001:db8::/32', false],
            ['2001:db8::1', '2001:db8::/129', false],
            ['2001:db8::1', '192.168.1.0/24', false],
            ['2001:db8::1', '::/0', true],
        ];
    }
}
