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

use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Api\Data\RuleInterface;
use Yireo\SalesBlock2\Exception\NoMatchException;
use Yireo\SalesBlock2\Helper\Data;
use Yireo\SalesBlock2\Logger\Debugger;
use Yireo\SalesBlock2\RuleMatch\RuleMatch;
use Yireo\SalesBlock2ByIp\Matcher\Matcher as Target;
use Yireo\SalesBlock2ByIp\Utils\CurrentIp;
use Yireo\SalesBlock2ByIp\Utils\IpMatcher;

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
            $match = $target->match($matchPattern);
            $this->assertInstanceOf(RuleMatch::class, $match, $message);
            $this->assertMessageDoesNotRevealPattern($match, $matchPattern);
        } else {
            $this->expectException(NoMatchException::class);
            $target->match($matchPattern);
        }
    }

    /**
     * @param RuleMatch $match
     * @param string $matchPattern
     */
    private function assertMessageDoesNotRevealPattern(RuleMatch $match, string $matchPattern): void
    {
        $rule = $this->createMock(RuleInterface::class);
        $rule->method('getFrontendLabel')->willReturn('');
        $match->setRule($rule);

        $message = $match->getMessage();
        $this->assertStringNotContainsString('Matched IP with', $message);
        $matchPattern = trim($matchPattern, " \f\n\r\t\v\x00");
        if (!str_contains($this->currentIpValue, $matchPattern)) {
            $this->assertStringNotContainsString($matchPattern, $message);
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

        $debugger = $this->createMock(Debugger::class);

        $target = new Target($currentIp, $ipMatcher, $helper, $debugger);

        return $target;
    }

    /**
     * @return CurrentIp
     */
    protected function getCurrentIp(): CurrentIp
    {
        $currentIp = $this->createMock(CurrentIp::class);
        $currentIp->method('getValue')->willReturn($this->currentIpValue);

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
            ->willReturn([$this->currentMatchPattern]);

        return $helper;
    }
}
