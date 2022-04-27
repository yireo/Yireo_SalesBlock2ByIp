<?php declare(strict_types=1);

/**
 * Yireo SalesBlock2ByIp for Magento
 *
 * @package     Yireo_SalesBlock2ByIp
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2ByIp\Test\Unit\Matcher;

use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Exception\NoMatchException;
use Yireo\SalesBlock2\Helper\Data;
use Yireo\SalesBlock2\Match\RuleMatch;
use Yireo\SalesBlock2ByIp\Matcher\Matcher as Target;
use Yireo\SalesBlock2ByIp\Utils\CurrentIp;
use Yireo\SalesBlock2ByIp\Utils\IpMatcher;

/**
 * Class MatcherTest
 *
 * @package Yireo\SalesBlock2ByIp\Test\Unit\Matcher
 */
class MatcherTest extends TestCase
{
    /**
     * @var string
     */
    private $currentIpValue = '';

    /**
     * @var string
     */
    private $currentMatchPattern = '';

    /**
     * Test the code that is used in the rules conditions
     */
    public function testGetCode()
    {
        $target = $this->getTargetObject();
        $this->assertSame($target->getCode(), 'ip');
    }

    /**
     * Test whether the name makes sense
     */
    public function testGetName()
    {
        $target = $this->getTargetObject();
        $this->assertNotEmpty($target->getName());
    }

    /**
     * Test whether the description makes sense
     */
    public function testGetDescription()
    {
        $target = $this->getTargetObject();
        $this->assertNotEmpty($target->getDescription());
    }

    /**
     * Test whether basic matching of IP addresses works
     *
     * @dataProvider \Yireo\SalesBlock2ByIp\Test\Unit\DataProvider\IpPatterns::getData()
     * @param string $ipValue
     * @param string $matchPattern
     * @param bool $expectedReturnValue
     * @throws NoMatchException
     */
    public function testMatch(string $ipValue, string $matchPattern, bool $expectedReturnValue)
    {
        $this->currentIpValue = $ipValue;
        $this->currentMatchPattern = $matchPattern;

        $target = $this->getTargetObject();

        if ($expectedReturnValue === true) {
            $currentValue = $this->getCurrentIp()->getValue();
            $message = sprintf('Comparing "%s" with "%s"', $currentValue, $matchPattern);
            $this->assertInstanceOf(RuleMatch::class, $target->match($matchPattern), $message);
        } else {
            $this->expectException(NoMatchException::class);
            $target->match($matchPattern);
        }
    }

    /**
     * @return Target
     */
    protected function getTargetObject(): Target
    {
        $currentIp = $this->getCurrentIp();
        $helper = $this->getHelperMock();
        $ipMatcher = new IpMatcher();

        $target = new Target($currentIp, $ipMatcher, $helper);

        return $target;
    }

    /**
     * @return CurrentIp
     */
    protected function getCurrentIp(): CurrentIp
    {
        $currentIp = new CurrentIp($this->getMockBuilder(RemoteAddress::class)->getMock());
        $currentIp->setIp($this->currentIpValue);

        return $currentIp;
    }

    /**
     * @return Data
     */
    protected function getHelperMock(): Data
    {
        $helper = $this->createMock(
            Data::class
        );

        $helper
            ->method('stringToArray')
            ->will($this->returnValue([$this->currentMatchPattern]));

        return $helper;
    }
}
