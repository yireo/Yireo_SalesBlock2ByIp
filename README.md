# Yireo SalesBlock2ByIp for Magento 2
This module is a helper-module for the [Yireo_SalesBlock2](https://www.yireo.com/software/magento-extensions/salesblock2) extension, that allows you to block orders from being placed, based on specific rules defined in the Magento Admin Panel.

This specific module allows you to match by a specific IP pattern. The following patterns are supported:

- `192.168.1.1` or `2001:db8::1` = Direct IP matches (IPv4 and IPv6, in any notation)
- `192.168.1.0/24` or `2001:db8::/32` = IP ranges in CIDR notation
- `192.168.1.1 - 192.168.1.10` = Explicit IP ranges
- `*` = Every visitor

Be careful with `*`: a rule with this pattern blocks orders from every visitor of the store. Partial wildcards like `192.168.1.*` are not supported and never match. Use a CIDR range like `192.168.1.0/24` instead. Invalid patterns (like `192.168.1.0/33`) are skipped and never match.

### Installation
To install this module, use the following commands. First, install this module using composer. Note that this step will fail if the `Yireo_SalesBlock2` is not installed yet.
 
    composer require yireo/magento2-salesblock2-by-ip
    
Once this module is installed via composer, you can enable it:

    ./bin/magento module:enable Yireo_SalesBlock2ByIp

There are no further steps to take. The `Yireo_SalesBlock2` module automatically picks up on things.

### Client IP detection and reverse proxies
This module reads the client IP from `REMOTE_ADDR` only. The `X-Forwarded-For` header can be set to any value by a client, so it is ignored by default.

When Magento runs behind a reverse proxy (load balancer, CDN, Varnish), add the IP addresses of your proxies to the setting **Trusted proxies** under **Stores > Configuration > Yireo > Yireo SalesBlock**. When a request comes from a trusted proxy, the rightmost IP in the `X-Forwarded-For` header that is not a trusted proxy is used as the client IP.

### Upgrading to 2.1.3
Versions 2.1.1 and 2.1.2 configured Magento's `RemoteAddress` globally to trust the `X-Forwarded-For` header, which allowed visitors to fake their IP (see security advisory `YIREO-SALESBLOCK2-BY-IP-2026-001`). Version 2.1.3 removes this override. If your store is behind a reverse proxy, configure the **Trusted proxies** setting after upgrading. If other parts of your store relied on Magento core reading `X-Forwarded-For`, configure the `alternativeHeaders` argument of `Magento\Framework\HTTP\PhpEnvironment\RemoteAddress` in your own project, preferably with a header that your proxy overwrites.
