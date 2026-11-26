<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByIp\Test\Integration\Frontend;

use Magento\Framework\App\Config\MutableScopeConfigInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\ObjectManagerInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2\Helper\Rule;
use Yireo\SalesBlock2\RuleMatch\RuleMatch;
use Yireo\SalesBlock2\Test\Integration\RuleProvider;
use Yireo\SalesBlock2ByIp\Utils\CurrentIp;

/**
 * @magentoAppIsolation enabled
 * @magentoDbIsolation enabled
 */
class RuleHelperTest extends TestCase
{
    private ObjectManagerInterface $objectManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = Bootstrap::getObjectManager();
        $this->setConfigValue('salesblock/settings/enabled', 1);
    }

    public function testFindRuleWithAtLeastOneRuleThatMatchesByIp(): void
    {
        $this->setServer('192.168.1.1', '');
        $this->getRuleProvider()->createRule('ip', '192.168.1.1', true);

        $this->assertInstanceOf(RuleMatch::class, $this->findMatch());
    }

    public function testSpoofedForwardedForHeaderDoesNotBypassRule(): void
    {
        $this->setServer('203.0.113.5', '8.8.8.8');
        $this->getRuleProvider()->createRule('ip', '203.0.113.5', true);

        $this->assertSame('203.0.113.5', $this->objectManager->get(CurrentIp::class)->getValue());
        $this->assertInstanceOf(RuleMatch::class, $this->findMatch());
    }

    public function testForwardedForHeaderFromTrustedProxyIsUsed(): void
    {
        $this->setConfigValue('salesblock/settings/trusted_proxies', '10.0.0.1');
        $this->setServer('10.0.0.1', '8.8.8.8, 203.0.113.5');
        $this->getRuleProvider()->createRule('ip', '203.0.113.5', true);

        $this->assertSame('203.0.113.5', $this->objectManager->get(CurrentIp::class)->getValue());
        $this->assertInstanceOf(RuleMatch::class, $this->findMatch());
    }

    private function findMatch(): ?RuleMatch
    {
        $ruleHelper = $this->objectManager->get(Rule::class);
        $this->assertNotEmpty($ruleHelper->getRules());

        try {
            return $ruleHelper->findMatch();
        } catch (NotFoundException) {
            return null;
        }
    }

    private function setServer(string $remoteAddress, string $forwardedFor): void
    {
        /** @var HttpRequest $request */
        $request = $this->objectManager->get(RequestInterface::class);
        $server = $request->getServer();
        $server->set('REMOTE_ADDR', $remoteAddress);
        $server->set('HTTP_X_FORWARDED_FOR', $forwardedFor);
        $request->setServer($server);
    }

    private function getRuleProvider(): RuleProvider
    {
        return $this->objectManager->get(RuleProvider::class);
    }

    private function setConfigValue(string $configPath, mixed $value): void
    {
        $this->objectManager->get(MutableScopeConfigInterface::class)->setValue($configPath, $value);
    }
}
