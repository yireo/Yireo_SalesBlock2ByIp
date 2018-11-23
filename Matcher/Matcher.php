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

namespace Yireo\SalesBlock2ByIp\Matcher;

use Yireo\SalesBlock2\Api\MatcherInterface;
use Yireo\SalesBlock2\Helper\Data;
use Yireo\SalesBlock2\Match\Match;
use Yireo\SalesBlock2\Match\MatchHolder;
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
     * @var MatchHolder
     */
    private $matchHolder;

    /**
     * Matcher constructor.
     * @param CurrentIp $currentIp
     * @param IpMatcher $ipMatcher
     * @param Data $helper
     * @param MatchHolder $matchHolder
     */
    public function __construct(
        CurrentIp $currentIp,
        IpMatcher $ipMatcher,
        Data $helper,
        MatchHolder $matchHolder
    ) {
        $this->currentIp = $currentIp;
        $this->ipMatcher = $ipMatcher;
        $this->helper = $helper;
        $this->matchHolder = $matchHolder;
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
     * @return bool
     */
    public function match(string $matchString): bool
    {
        $matchStrings = $this->helper->stringToArray($matchString);
        foreach ($matchStrings as $matchString) {
            if ($this->ipMatcher->match($this->currentIp->getValue(), $matchString)) {
                $this->addMatch(sprintf('Matched IP with %s', $matchString));
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $message
     */
    private function addMatch(string $message)
    {
        $this->matchHolder->setMatch(new Match($message));
    }
}
