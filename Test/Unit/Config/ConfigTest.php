<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByIp\Test\Unit\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2ByIp\Config\Config;

class ConfigTest extends TestCase
{
    /**
     * @return array<string, array{mixed, string[]}>
     */
    public static function getTrustedProxyValues(): array
    {
        return [
            'empty' => ['', []],
            'null' => [null, []],
            'single' => ['10.0.0.1', ['10.0.0.1']],
            'comma separated' => ['10.0.0.1, 10.0.0.2', ['10.0.0.1', '10.0.0.2']],
            'new lines' => ["10.0.0.1\r\n10.0.0.2\n", ['10.0.0.1', '10.0.0.2']],
            'semicolons' => ['10.0.0.1;10.0.0.2', ['10.0.0.1', '10.0.0.2']],
            'ipv6' => ['2001:db8::1', ['2001:db8::1']],
            'invalid entries are skipped' => ['10.0.0.1, foo, 10.0.0.0/8, 999.1.1.1', ['10.0.0.1']],
            'duplicates' => ['10.0.0.1,10.0.0.1', ['10.0.0.1']],
        ];
    }

    /**
     * @param string[] $expected
     */
    #[DataProvider('getTrustedProxyValues')]
    public function testGetTrustedProxies(mixed $value, array $expected): void
    {
        $this->assertSame($expected, $this->getConfig($value)->getTrustedProxies());
    }

    private function getConfig(mixed $value): Config
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturn($value);

        return new Config($scopeConfig);
    }
}
