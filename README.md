# Yireo SalesBlock2ByIp for Magento 2

<!-- badges.specs.start -->
![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/yireo/magento2-salesblock2-by-ip)
<!-- badges.specs.end -->

This module is a helper-module for the [Yireo_SalesBlock2](https://www.yireo.com/software/magento-extensions/salesblock2) extension, that allows you to block orders from being placed, based on specific rules defined in the Magento Admin Panel.

This specific module allows you to match by a specific IP pattern. The following patterns are supported:

- `192.168.1.1` or `2001:db8::1` = Direct IP matches (IPv4 and IPv6, in any notation)
- `192.168.1.0/24` or `2001:db8::/32` = IP ranges in CIDR notation
- `192.168.1.1 - 192.168.1.10` = Explicit IP ranges
- `*` = Every visitor

Multiple patterns can be separated by a newline, `,`, `;` or `|`.

Be careful with `*`: a rule with this pattern blocks orders from every visitor of the store. Partial wildcards like `192.168.1.*` are not supported and never match. Use a CIDR range like `192.168.1.0/24` instead. Invalid patterns (like `192.168.1.0/33`) are skipped and never match.

### Installation
To install this module, use the following commands. First, install this module using composer. Note that this step will fail if the `Yireo_SalesBlock2` is not installed yet.
 
    composer require yireo/magento2-salesblock2-by-ip
    
Once this module is installed via composer, you can enable it:

    ./bin/magento module:enable Yireo_SalesBlock2ByIp

There are no further steps to take. The `Yireo_SalesBlock2` module automatically picks up on things.

### Client IP detection and reverse proxies
This module uses the client IP as Magento detects it (`Magento\Framework\HTTP\PhpEnvironment\RemoteAddress`). It does not change how Magento reads that IP.

Magento core reads the `X-Forwarded-For` header before `REMOTE_ADDR`. The client can set this header to any value, unless a reverse proxy (load balancer, CDN, Varnish) in front of Magento overwrites it. Without trusted proxies configured, Magento uses the leftmost IP of the header, which is the one the client can fake. So a blocked visitor could get past an IP rule with a fake header.

When Magento runs behind a reverse proxy, list the IPs of your proxies in `app/etc/di.xml` (or in a custom module). Magento then uses the rightmost IP in the header that is not a trusted proxy:

```xml
<type name="Magento\Framework\HTTP\PhpEnvironment\RemoteAddress">
    <arguments>
        <argument name="trustedProxies" xsi:type="array">
            <item name="proxy1" xsi:type="string">10.0.0.10</item>
        </argument>
    </arguments>
</type>
```

If your proxy sends the client IP in a different header (like `CF-Connecting-IP`), override the `x-forwarded-for` item of the `alternativeHeaders` argument with that header. When Magento is not behind a proxy, point the item to `REMOTE_ADDR` instead, so that forwarded headers are ignored:

```xml
<type name="Magento\Framework\HTTP\PhpEnvironment\RemoteAddress">
    <arguments>
        <argument name="alternativeHeaders" xsi:type="array">
            <item name="x-forwarded-for" xsi:type="string">REMOTE_ADDR</item>
        </argument>
    </arguments>
</type>
```
## Current status

<!-- badges.test.start -->
![Static Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByIp/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByIp/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByIp/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByIp/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByIp/compile.yml?label=compile)
<!-- badges.test.end -->
