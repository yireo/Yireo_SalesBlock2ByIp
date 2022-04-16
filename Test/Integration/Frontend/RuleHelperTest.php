<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByIp\Test\Integration\Frontend;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Helper\Rule;
use Yireo\SalesBlock2\Match\RuleMatch;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;

class RuleHelperTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Rule
     */
    private $ruleHelper;

    /**
     * Setup dependencies
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = Bootstrap::getObjectManager();
        $this->ruleHelper = $this->objectManager->get(Rule::class);
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
            $this->assertTrue(false, 'No match found: ' . $e->getMessage());
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
     * @param $value
     */
    private function setConfigValue(string $configPath, $value)
    {
        $this->objectManager->get(MutableScopeConfigInterface::class)
            ->setValue($configPath, $value);
    }
}
