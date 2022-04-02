<?php
namespace Syntro\SilverstripeGoogleAnalytics\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Control\Director;
use SilverStripe\Versioned\Versioned;
use SilverStripe\Core\Config\Config as SSConfig;
use Syntro\SilverstripeGoogleAnalytics\Config;

/**
 * Extends the default content controller to include the analytics dependencies
 *
 * @author Matthias Leutenegger <hello@syntro.ch>
 * @codeCoverageIgnore
 */
class ContentControllerExtension extends Extension
{

    /**
     * onBeforeInit - Handler executed before init
     *
     * @return void
     */
    public function onBeforeInit()
    {
        /** @var null|string $google_token */
        $google_token = SSConfig::inst()->get(Config::class, 'google_token');
        if (!is_null($google_token)) {
            Config::includeKlaroRequirements();
            if (Director::isLive() && Versioned::get_stage() == Versioned::LIVE) {
                Config::requireAnalytics();
            }
        }
    }
}
