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

use Wikimedia\IPSet;

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

        if ($matchPattern === '*') {
            return true;
        }

        return $this->matchIpRange($ip, $matchPattern);
    }

    /**
     * Match whether a certain IP matches a certain range string
     *
     * @param string $ip
     * @param string $rangeString
     *
     * @return bool
     */
    public function matchIpRange(string $ip, string $rangeString): bool
    {
        if (preg_match('/([0-9\.]+)\/([0-9]+)/', $rangeString)) {
            $ipset = new IPSet([$rangeString]);
            return (bool)$ipset->match($ip);
        }

        return false;
    }
}
