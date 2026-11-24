<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByIp\Test\Unit\Utils;

use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use PHPUnit\Framework\TestCase;
use Yireo\SalesBlock2ByIp\Utils\CurrentIp;

class CurrentIpTest extends TestCase
{
    public function testGetValueReturnsRemoteAddress(): void
    {
        $currentIp = new CurrentIp($this->getRemoteAddress('192.168.1.1'));
        $this->assertSame('192.168.1.1', $currentIp->getValue());
    }

    public function testGetValueReturnsEmptyStringWhenNoAddressIsFound(): void
    {
        $currentIp = new CurrentIp($this->getRemoteAddress(false));
        $this->assertSame('', $currentIp->getValue());
    }

    public function testGetValueDoesNotKeepStateBetweenCalls(): void
    {
        $currentIp = new CurrentIp($this->getRemoteAddress('192.168.1.1', '10.0.0.1'));
        $this->assertSame('192.168.1.1', $currentIp->getValue());
        $this->assertSame('10.0.0.1', $currentIp->getValue());
    }

    private function getRemoteAddress(string|false ...$values): RemoteAddress
    {
        $remoteAddress = $this->getMockBuilder(RemoteAddress::class)->disableOriginalConstructor()->getMock();
        $remoteAddress->method('getRemoteAddress')->willReturnCallback(
            function () use (&$values) {
                return array_shift($values);
            }
        );

        return $remoteAddress;
    }
}
