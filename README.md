# Silverstripe Google Analytics

[![🎭 Tests](https://github.com/syntro-opensource/silverstripe-google-analytics/workflows/%F0%9F%8E%AD%20Tests/badge.svg)](https://github.com/syntro-opensource/silverstripe-google-analytics/actions?query=workflow%3A%22%F0%9F%8E%AD+Tests%22+branch%3A%22master%22)
[![codecov](https://codecov.io/gh/syntro-opensource/silverstripe-google-analytics/branch/master/graph/badge.svg)](https://codecov.io/gh/syntro-opensource/silverstripe-google-analytics)
![Dependabot](https://img.shields.io/badge/dependabot-active-brightgreen?logo=dependabot)
[![phpstan](https://img.shields.io/badge/PHPStan-enabled-success)](https://github.com/phpstan/phpstan)
[![composer](https://img.shields.io/packagist/dt/syntro/silverstripe-google-analytics?color=success&logo=composer)](https://packagist.org/packages/syntro/silverstripe-google-analytics)
[![Packagist Version](https://img.shields.io/packagist/v/syntro/silverstripe-google-analytics?label=stable&logo=composer)](https://packagist.org/packages/syntro/silverstripe-google-analytics)

Adds Google Analytics to your Website. Uses [`syntro/silverstripe-klaro`](https://github.com/syntro-opensource/silverstripe-klaro)
for consent management.

## Installation
To install this module, run the following command:
```
composer require syntro/silverstripe-google-analytics
```

## Usage

After installing, you have to configure analytics to use your datastream:
```yml
Syntro\SilverstripeGoogleAnalytics\Config:
  google_token: G-XXXXXXXX
```

After that, Silverstripe will serve Google Analytics after the user has given
consent.

## Configuration

You can use the following options on `Syntro\SilverstripeGoogleAnalytics\Config`
to influence how the analytics script is loaded:

* `klaro_create_default_purpose`: if true, a new purpose will be created
* `klaro_purposes`: Add the created service to additional purposes. If 'klaro_create_default_purpose' is true, the 'analytics' purpose will be appended.
* `klaro_enabled_by_default`: if true, the generated service will be enabled by default. **WARNING**: enabling this will most likely violate GDPR rules

## Styling klaro!
This Module uses klaro! for consent management via the [`syntro/silverstripe-klaro`](https://github.com/syntro-opensource/silverstripe-klaro)
module. We recommend checking that module out for information on how to style the
consent-window.
