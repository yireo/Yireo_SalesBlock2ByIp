<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByIp\Test\Unit\Utils;

use Magento\Framework\App\Request\Http as HttpRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2ByIp\Config\Config;
use Yireo\SalesBlock2ByIp\Utils\CurrentIp;

class CurrentIpTest extends TestCase
{
    /**
     * @return array<string, array{string, string, string[], string}>
     */
    public static function getRequests(): array
    {
        return [
            'remote address' => ['203.0.113.5', '', [], '203.0.113.5'],
            'spoofed header without trusted proxies' => ['203.0.113.5', '8.8.8.8', [], '203.0.113.5'],
            'spoofed header from untrusted peer' => ['203.0.113.5', '8.8.8.8', ['10.0.0.1'], '203.0.113.5'],
            'header from trusted proxy' => ['10.0.0.1', '198.51.100.7', ['10.0.0.1'], '198.51.100.7'],
            'spoofed leftmost entry behind trusted proxy' => [
                '10.0.0.1',
                '8.8.8.8, 198.51.100.7',
                ['10.0.0.1'],
                '198.51.100.7',
            ],
            'chain of trusted proxies' => [
                '10.0.0.1',
                '198.51.100.7, 10.0.0.2',
                ['10.0.0.1', '10.0.0.2'],
                '198.51.100.7',
            ],
            'invalid entry stops the chain' => ['10.0.0.1', '198.51.100.7, foo', ['10.0.0.1'], '10.0.0.1'],
            'only trusted proxies in header' => ['10.0.0.1', '10.0.0.1', ['10.0.0.1'], '10.0.0.1'],
            'trusted proxy without header' => ['10.0.0.1', '', ['10.0.0.1'], '10.0.0.1'],
            'ipv6' => ['2001:db8::1', '', [], '2001:db8::1'],
            'invalid remote address' => ['foo', '8.8.8.8', [], ''],
            'empty remote address' => ['', '8.8.8.8', [], ''],
        ];
    }

    /**
     * @param string[] $trustedProxies
     */
    #[DataProvider('getRequests')]
    public function testGetValue(
        string $remoteAddress,
        string $forwardedFor,
        array $trustedProxies,
        string $expected
    ): void {
        $currentIp = new CurrentIp($this->getRequest($remoteAddress, $forwardedFor), $this->getConfig($trustedProxies));
        $this->assertSame($expected, $currentIp->getValue());
    }

    public function testGetValueDoesNotKeepStateBetweenRequests(): void
    {
        $request = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getServer'])
            ->getMock();
        $request->method('getServer')->willReturnOnConsecutiveCalls('192.168.1.1', '10.0.0.1');

        $currentIp = new CurrentIp($request, $this->getConfig([]));
        $this->assertSame('192.168.1.1', $currentIp->getValue());
        $this->assertSame('10.0.0.1', $currentIp->getValue());
    }

    private function getRequest(string $remoteAddress, string $forwardedFor): HttpRequest
    {
        $request = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getServer'])
            ->getMock();
        $request->method('getServer')->willReturnCallback(
            fn (string $name) => ['REMOTE_ADDR' => $remoteAddress, 'HTTP_X_FORWARDED_FOR' => $forwardedFor][$name] ?? null
        );

        return $request;
    }

    /**
     * @param string[] $trustedProxies
     */
    private function getConfig(array $trustedProxies): Config
    {
        $config = $this->createMock(Config::class);
        $config->method('getTrustedProxies')->willReturn($trustedProxies);

        return $config;
    }
}
