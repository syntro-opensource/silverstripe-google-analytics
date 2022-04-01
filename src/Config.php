<?php
namespace Syntro\SilverstripeGoogleAnalytics;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Config\Config as SSConfig;
use Syntro\SilverstripeKlaro\Config as KlaroConfig;
use Syntro\SilverstripeKlaro\KlaroRequirements;

/**
 * A Config interface for inserting the google analytics snippet in a
 * customizeable way.
 * @author Matthias Leutenegger
 */
class Config
{
    use Configurable;

    /**
     * The token used to include analytics
     * @config
     */
    private static $google_token = null;

    /**
     * if true, a new purpose will be created
     * @config
     */
    private static $klaro_create_default_purpose = true;

    /**
     * Add the created service to additional purposes. If 'klaro_create_default_purpose'
     * is true, the 'analytics' purpose will be appended.
     * @config
     */
    private static $klaro_purposes = [];

    /**
     * if true, the generated service will be enabled by default
     * WARNING: enabling this will most likely violate GDPR rules
     * @config
     */
    private static $klaro_enabled_by_default = false;

    /**
     * includeKlaroRequirements - adds the service and purpose to the klaro config
     *
     * @return void
     */
    public static function includeKlaroRequirements()
    {
        $klaro_create_default_purpose = SSConfig::inst()->get(static::class, 'klaro_create_default_purpose');
        $klaro_purposes = SSConfig::inst()->get(static::class, 'klaro_purposes');
        $klaro_enabled_by_default = SSConfig::inst()->get(static::class, 'klaro_enabled_by_default');
        $klaro_opt_out = SSConfig::inst()->get(static::class, 'klaro_opt_out');

        if ($klaro_create_default_purpose) {
            SSConfig::modify()->merge(KlaroConfig::class, 'klaro_purposes', [
                'analytics' => ['title' => 'Analytics', 'description' => 'Tools used to gather usage statistics']
            ]);
            $klaro_purposes[] = 'analytics';
        }


        SSConfig::modify()->merge(KlaroConfig::class, 'klaro_services', [
            'googleanalytics' => [
                'title' => 'Google Analytics',
                'description' => 'Analytics software by Google',
                'default' => $klaro_enabled_by_default,
                'purposes' => $klaro_purposes,
                'cookies' => [ "/^_ga(_.*)?/" ]
            ]
        ]);
    }

    /**
     * requireAnalytics - Loads the required Code
     *
     * @return void
     */
    public static function requireAnalytics()
    {
        $google_token = SSConfig::inst()->get(static::class, 'google_token');
        KlaroRequirements::klaroJavascript(
            "https://www.googletagmanager.com/gtag/js?id=$google_token",
            'googleanalytics'
        );
        KlaroRequirements::customKlaroScript(
            <<<JS
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '$google_token');
            JS
            ,
            'googleanalytics'
        );
    }
}
