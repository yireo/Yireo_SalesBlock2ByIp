<?php declare(strict_types=1);

/**
 * Yireo SalesBlock2ByIp for Magento
 *
 * @package     Yireo_SalesBlock2ByIp
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2018 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

namespace Yireo\SalesBlock2ByIp\Matcher;

use Yireo\SalesBlock2\Api\MatcherInterface;
use Yireo\SalesBlock2\Exception\NoMatchException;
use Yireo\SalesBlock2\Helper\Data;
use Yireo\SalesBlock2\Logger\Debugger;
use Yireo\SalesBlock2\RuleMatch\RuleMatch;
use Yireo\SalesBlock2ByIp\Utils\CurrentIp;
use Yireo\SalesBlock2ByIp\Utils\IpMatcher;

/**
 * Class Matcher
 * @package Yireo\SalesBlock2ByIp\Matcher
 */
class Matcher implements MatcherInterface
{
    /**
     * @var CurrentIp
     */
    private $currentIp;

    /**
     * @var IpMatcher
     */
    private $ipMatcher;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * Matcher constructor.
     * @param CurrentIp $currentIp
     * @param IpMatcher $ipMatcher
     * @param Data $helper
     * @param Debugger $debugger
     */
    public function __construct(
        CurrentIp $currentIp,
        IpMatcher $ipMatcher,
        Data $helper,
        Debugger $debugger
    ) {
        $this->currentIp = $currentIp;
        $this->ipMatcher = $ipMatcher;
        $this->helper = $helper;
        $this->debugger = $debugger;
    }

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
     * @param string $matchString
     * @return RuleMatch
     * @throws NoMatchException
     */
    public function match(string $matchString): RuleMatch
    {
        $currentIp = $this->currentIp->getValue();
        $matchStrings = $this->helper->stringToArray($matchString);
        foreach ($matchStrings as $matchString) {
            if (!$this->ipMatcher->match($currentIp, $matchString)) {
                continue;
            }

            $this->debugger->debug(sprintf('IP %s matched pattern %s', $currentIp, $matchString));

            $message = (string)__('Your IP address %1 is not allowed to place orders', $currentIp);
            $match = new RuleMatch($message);
            $match->setVariables(['ip' => $currentIp]);
            return $match;
        }

        throw new NoMatchException(__('No match found'));
    }
}
