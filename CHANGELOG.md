# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.0.0] - 25 November 2026
### Changed
- BREAKING: No longer overrides Magento's RemoteAddress to trust X-Forwarded-For globally, which let visitors fake their IP and weakened core features (maintenance allowlist, CAPTCHA, rate limits); stores behind a reverse proxy must now configure RemoteAddress themselves (see README)
- Drop PHP 8.1 support, add PHP 8.5 support
- Declare magento/framework dependency and Magento_Backend module sequence

### Added
- New "Trusted proxies" setting: X-Forwarded-For is only used when the request comes from a listed proxy IP, read from the right
- IPv6 range matching and recognition of alternative notations of the same IP (case, zero compression, IPv4-mapped)
- Documentation of the IP pattern syntax, wildcards and the trusted proxies setting

### Fixed
- Visitors could bypass IP rules by faking the X-Forwarded-For header
- An invalid IP range (like 10.0.0.0/33) caused an exception on add-to-cart and checkout for every visitor
- The customer message revealed the configured blocklist pattern; it now shows a generic message with the visitor's own IP
- A cached IP could be reused across requests on long-running app servers, and any code could overwrite the detected IP via setIp()

## [2.1.2] - 21 November 2025
### Fixed
- Switch from wikimedia/ip-set to wikimedia/ip-utils [#3 @Krinkle]

## [2.1.1] - 10 April 2024
### Fixed
- Rename RuleMatch namespace because of compat PHP 8.2

## [2.1.0] - 25 April 2022
### Added
- This CHANGELOG

### Removed
- Removed unneeded module version

## [2.1.0] - 16 April 2022
### Fixed
- Remove PHP dep from composer
- PHP 8 compatibility (and therefore Magento 2.4.4)

Previous versions were not added to CHANGELOG
