<?php declare(strict_types=1);
/**
 * Yireo SalesBlock2ByIp for Magento
 *
 * @package     Yireo_SalesBlock2ByIp
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2ByIp\Test\Unit\Utils;

use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2ByIp\Utils\IpMatcher as Target;

class IpMatcherTest extends TestCase
{
    /**
     * Test whether basic matching of Ip addresses works
     *
     * @dataProvider \Yireo\SalesBlock2ByIp\Test\Unit\DataProvider\IpPatterns::getData()
     */
    public function testMatch(string $ip, string $matchPattern, bool $returnValue)
    {
        $target = $this->getTargetObject();
        $message = sprintf('Comparing "%s" with "%s"', $ip, $matchPattern);
        $this->assertEquals($target->match($ip, $matchPattern), $returnValue, $message);
    }

    /**
     * @return Target
     */
    protected function getTargetObject(): Target
    {
        return new Target();
    }
}
