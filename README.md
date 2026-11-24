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
This module reads the client IP from `REMOTE_ADDR` only. The `X-Forwarded-For` header can be set to any value by a client, so it is ignored by default, even when Magento core is configured to use it (Magento core uses the leftmost IP of that header, which a blocked visitor could fake to get past an IP rule).

When Magento runs behind a reverse proxy (load balancer, CDN, Varnish), add the IP addresses of your proxies to the setting **Trusted proxies** under **Stores > Configuration > Yireo > Yireo SalesBlock**. When a request comes from a trusted proxy, the rightmost IP in the `X-Forwarded-For` header that is not a trusted proxy is used as the client IP. This setting is shared with `Yireo_SalesBlock2ByGeo`.

## Current status

<!-- badges.test.start -->
![Static Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByIp/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByIp/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByIp/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByIp/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_SalesBlock2ByIp/compile.yml?label=compile)
<!-- badges.test.end -->
