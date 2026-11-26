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

use Throwable;
use Wikimedia\IPUtils;

class IpMatcher
{
    /**
     * @param string $ip
     * @param string $matchPattern
     * @return bool
     */
    public function match(string $ip, string $matchPattern): bool
    {
        $matchPattern = trim($matchPattern, " \f\n\r\t\v\x00");
        if ($matchPattern === '*') {
            return true;
        }

        $ip = $this->canonicalize($ip);
        if ($ip === null) {
            return false;
        }

        $matchIp = $this->canonicalize($matchPattern);
        if ($matchIp !== null) {
            return IPUtils::toHex($ip) === IPUtils::toHex($matchIp);
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
        $ip = $this->canonicalize($ip);
        $rangeString = trim($rangeString, " \f\n\r\t\v\x00");
        if ($ip === null || !IPUtils::isValidRange($rangeString)) {
            return false;
        }

        try {
            return IPUtils::isInRange($ip, $rangeString);
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * @param string $ip
     * @return string|null
     */
    private function canonicalize(string $ip): ?string
    {
        $ip = trim($ip, " \f\n\r\t\v\x00");
        if ($ip === '') {
            return null;
        }

        $canonicalIp = IPUtils::canonicalize($ip);
        if (!is_string($canonicalIp) || !IPUtils::isValid($canonicalIp)) {
            return null;
        }

        return $canonicalIp;
    }
}
