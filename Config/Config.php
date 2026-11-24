<?php declare(strict_types=1);

namespace Yireo\SalesBlock2ByIp\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * @return string[]
     */
    public function getTrustedProxies(): array
    {
        $value = (string)$this->scopeConfig->getValue('salesblock/settings/trusted_proxies');
        $proxies = [];
        foreach ((array)preg_split('/[\s,;]+/', $value) as $proxy) {
            $proxy = trim((string)$proxy, " \f\n\r\t\v\x00");
            if (filter_var($proxy, FILTER_VALIDATE_IP) !== false) {
                $proxies[] = $proxy;
            }
        }

        return array_values(array_unique($proxies));
    }
}
