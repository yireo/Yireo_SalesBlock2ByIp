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

namespace Yireo\SalesBlock2ByIp\Matcher;

use Yireo\SalesBlock2\Api\MatcherInterface;

/**
 * Class Matcher
 * @package Yireo\SalesBlock2ByIp\Matcher
 */
class Matcher implements MatcherInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 'ip';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'IP address';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Match by IP address';
    }

    /**
     * @return bool
     */
    public function match(): bool
    {
        return false;
    }
}
