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
 * Class CurrentIp
 * @package Yireo\SalesBlock2ByIp\Utils
 */
class CurrentIp
{
    /**
     * @var string
     */
    private $ip = '';

    /**
     * @param $ip
     */
    public function setIp($ip)
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

        $ip = $_SERVER['REMOTE_ADDR'];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        $this->ip = $ip;

        return (string) $this->ip;
    }
}
