<?php
/**
 * Yireo SalesBlock2ByIp for Magento
 *
 * @package     Yireo_SalesBlock2ByIp
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types = 1);

namespace Yireo\SalesBlock2ByIp\Utils;

/**
 * Class IpMatcher
 * @package Yireo\SalesBlock2ByIp\Utils
 */
class IpMatcher
{
    /**
     * @param string $ip
     * @param string $matchPattern
     * @return bool
     */
    public function match(string $ip, string $matchPattern): bool
    {
        if ($ip === $matchPattern) {
            return true;
        }

        return false;
    }

    /**
     * Match whether a certain IP matches a certain range string
     *
     * @param $ip
     * @param $rangeString
     *
     * @return bool
     */
    public function matchIpRange(string $ip, string $rangeString): bool
    {
        // Convert subnet ranges
        if (!preg_match('/([0-9\.]+)\/([0-9]+)/', $rangeString, $rangeMatch)) {
            return false;
        }

        $rip = ip2long($rangeMatch[1]);
        $ipStart = long2ip((float)$rip);
        $ipEnd = long2ip((float)($rip | (1 << (32 - $rangeMatch[2])) - 1));
        $rangeString = $ipStart . '-' . $ipEnd;

        // Check for IP-ranges
        if (!preg_match('/([0-9\.]+)-([0-9\.]+)/', $rangeString, $ipMatch)) {
            return false;
        }

        if (version_compare($ip, $ipMatch[1], '>=') && version_compare($ip, $ipMatch[2], '<=')) {
            return true;
        }

        return false;
    }
}
