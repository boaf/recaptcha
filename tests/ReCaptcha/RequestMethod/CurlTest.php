<?php
/**
 * Created by PhpStorm.
 * User: christophschmidt
 * Date: 29.04.15
 * Time: 18:52
 */

namespace ReCaptcha\RequestMethod;


use ReCaptcha\RequestParameters;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    public static $curlInfo = null;

    public static $curlExecCalled = false;

    public static $curlCloseCalled = false;

    /**
     * @var RequestParameters
     */
    private $parameters = null;

    public function setUp()
    {
        if (!extension_loaded('curl')) {
            $this->markTestSkipped('curl extension not loaded, skipping test.');
        }

        $this->parameters = new RequestParameters("secret", "response", "remoteip", "version");
    }

    public function tearDown()
    {
        self::$curlInfo = null;
        self::$curlExecCalled = false;
        self::$curlCloseCalled = false;
        $this->parameters = null;
    }

    public function testRequest()
    {
        $curl = new Curl();
        $curl->submit($this->parameters);

        $this->assertEquals(Curl::SITE_VERIFY_URL, self::$curlInfo['url']);
        $this->assertTrue(self::$curlExecCalled, 'curl_exec is expected to be called.');
        $this->assertTrue(self::$curlCloseCalled, 'curl_close is expected to be called.');
    }
}

function curl_exec($handle)
{
    if (is_resource($handle)) {
        CurlTest::$curlExecCalled = true;
        CurlTest::$curlInfo = curl_getinfo($handle);
    }
}

function curl_close($handle)
{
    if (is_resource($handle)) {
        CurlTest::$curlCloseCalled = true;
        \curl_close($handle);
    }
}
