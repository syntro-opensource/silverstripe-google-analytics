<?php
namespace Syntro\SilverstripeGoogleAnalytics\Tests;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Core\Config\Config as SSConfig;
use Syntro\SilverstripeKlaro\Config as KlaroConfig;
use Syntro\SilverstripeKlaro\KlaroRequirements;
use Syntro\SilverstripeGoogleAnalytics\Config as GAConfig;

/**
 * Test the configuration
 * @author Matthias Leutenegger
 */
class ConfigTest extends FunctionalTest
{
    /**
     * Defines the fixture file to use for this test class
     * @var string
     */
    protected static $fixture_file = './defaultfixture.yml';

    /**
     * Test wether the default purpose is added
     * @return void
     */
    public function testAddsDefaultPurpose()
    {
        $purposes = SSConfig::withConfig(function ($config) {
            $config->set(GAConfig::class, 'klaro_create_default_purpose', true);
            GAConfig::includeKlaroRequirements();
            return $config->get(KlaroConfig::class, 'klaro_purposes');
        });

        $this->assertArrayHasKey('analytics', $purposes);
    }

    /**
     * Test wether the default purpose is omitted if configured
     * @return void
     */
    public function testOmitsDefaultPurpose()
    {
        $purposes = SSConfig::withConfig(function ($config) {
            $config->set(GAConfig::class, 'klaro_create_default_purpose', false);
            GAConfig::includeKlaroRequirements();
            return $config->get(KlaroConfig::class, 'klaro_purposes');
        });
        $this->assertFalse(isset($purposes['analytics']));
    }

    /**
     * Test wether the service is added to the config
     * @return void
     */
    public function testAddsServiceToConfig()
    {
        $services = SSConfig::withConfig(function ($config) {
            GAConfig::includeKlaroRequirements();
            return $config->get(KlaroConfig::class, 'klaro_services');
        });
        $this->assertTrue(isset($services['googleanalytics']));
    }

    /**
     * Test wether the default purpose is added to the service
     * @return void
     */
    public function testAddsDefaultPurposeToService()
    {
        $services = SSConfig::withConfig(function ($config) {
            $config->set(GAConfig::class, 'klaro_create_default_purpose', true);
            GAConfig::includeKlaroRequirements();
            return $config->get(KlaroConfig::class, 'klaro_services');
        });
        $this->assertContains('analytics', $services['googleanalytics']['purposes']);
    }

    /**
     * Test wether the default purpose is omitted from the service if configured
     * @return void
     */
    public function testOmitsDefaultPurposeToService()
    {
        $services = SSConfig::withConfig(function ($config) {
            $config->set(GAConfig::class, 'klaro_create_default_purpose', false);
            GAConfig::includeKlaroRequirements();
            return $config->get(KlaroConfig::class, 'klaro_services');
        });
        $this->assertNotContains('analytics', $services['googleanalytics']['purposes']);
    }

    /**
     * Test wether additional purposes are added to the service
     * @return void
     */
    public function testAddsCustomPurposeToService()
    {
        $services = SSConfig::withConfig(function ($config) {
            $config->set(GAConfig::class, 'klaro_purposes', ['somePurpose']);
            $config->set(GAConfig::class, 'klaro_create_default_purpose', true);
            GAConfig::includeKlaroRequirements();
            return $config->get(KlaroConfig::class, 'klaro_services');
        });
        $this->assertContains('analytics', $services['googleanalytics']['purposes']);
        $this->assertContains('somePurpose', $services['googleanalytics']['purposes']);
    }

    /**
     * Test wether the default is set correctly
     * @return void
     */
    public function testSetsEnabledByDefault()
    {
        $services = SSConfig::withConfig(function ($config) {
            $config->set(GAConfig::class, 'klaro_enabled_by_default', true);
            GAConfig::includeKlaroRequirements();
            return $config->get(KlaroConfig::class, 'klaro_services');
        });
        $this->assertTrue($services['googleanalytics']['default']);

        $services = SSConfig::withConfig(function ($config) {
            $config->set(GAConfig::class, 'klaro_enabled_by_default', false);
            GAConfig::includeKlaroRequirements();
            return $config->get(KlaroConfig::class, 'klaro_services');
        });
        $this->assertFalse($services['googleanalytics']['default']);
    }

    /**
     * Test wether the depending JS is required via Klaro
     * @return void
     */
    public function testRequiresDependencies()
    {
        $google_token = 'G-ABCDEFG';
        $backend = SSConfig::withConfig(function ($config) use ($google_token) {
            $config->set(GAConfig::class, 'google_token', $google_token);
            GAConfig::requireAnalytics();
            return KlaroRequirements::backend();
        });

        $this->assertArrayHasKey("https://www.googletagmanager.com/gtag/js?id=$google_token", $backend->getKlaroJavascript());
    }
}
