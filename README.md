# Yireo SalesBlock2ByIp for Magento 2
This module is a helper-module for the [Yireo_SalesBlock2](https://www.yireo.com/software/magento-extensions/salesblock2) extension, that allows you to block orders from being placed, based on specific rules defined in the Magento Admin Panel.

This specific module allows you to match by a specific IP pattern. The following patterns are supported:

- `192.168.1.1` = Direct IP matches
- `192.168.1.0/24` = IP ranges

### Installation
To install this module, use the following commands. First, install this module using composer. Note that this step will fail if the `Yireo_SalesBlock2` is not installed yet.
 
    composer require yireo/magento2-salesblock2-by-ip
    
Once this module is installed via composer, you can enable it:

    ./bin/magento module:enable Yireo_SalesBlock2ByIp

There are no further steps to take. The `Yireo_SalesBlock2` module automatically picks up on things.