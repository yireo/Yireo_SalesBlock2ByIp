<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByIp\Test\Integration\Frontend;

use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Helper\Rule;
use Yireo\SalesBlock2\Match\RuleMatch;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;

class RuleHelperTest extends TestCase
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Setup dependencies
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     */
    public function testFindRuleWithAtLeastOneRuleThatMatchesByIp()
    {
        $this->setConfigValue('salesblock/settings/enabled', 1);
        $this->getRuleProvider()->createRule('ip', '192.168.1.1', true);
        $_SERVER['REMOTE_ADDR'] = '192.168.1.1';

        /** @var Rule $ruleHelper */
        $ruleHelper = $this->objectManager->get(Rule::class);
        $rules = $ruleHelper->getRules();
        $this->assertNotEmpty($rules);

        try {
            $match = $ruleHelper->findMatch();
            $this->assertInstanceOf(RuleMatch::class, $match);
        } catch (NotFoundException $e) {
            $this->fail('No match found: ' . $e->getMessage());
        }
    }

    /**
     * @return RuleProvider
     */
    private function getRuleProvider(): RuleProvider
    {
        return $this->objectManager->get(RuleProvider::class);
    }

    /**
     * @param string $configPath
     * @param mixed $value
     */
    private function setConfigValue(string $configPath, $value)
    {
        $this->objectManager->get(MutableScopeConfigInterface::class)
            ->setValue($configPath, $value);
    }
}
