<?php
namespace Syntro\SilverstripeGoogleAnalytics\Tests;

use SilverStripe\Dev\FunctionalTest;

/**
 * Test some demo
 * @author Matthias Leutenegger
 */
class DemoTest extends FunctionalTest
{
    /**
     * Defines the fixture file to use for this test class
     * @var string
     */
    protected static $fixture_file = './defaultfixture.yml';

    /**
     * Test Footer on Homepage
     * @return void
     */
    public function testAddition()
    {
        $this->assertEquals(1+1, 2);
    }
}
