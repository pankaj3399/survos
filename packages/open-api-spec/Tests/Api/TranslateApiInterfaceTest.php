<?php
/**
 * TranslateApiInterfaceTest
 * PHP version 8.1.1
 *
 * @category Class
 * @package  OpenAPI\Server\Tests\Api
 * @author   openapi-generator contributors
 * @link     https://github.com/openapitools/openapi-generator
 */

/**
 * LibreTranslate
 *
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: 1.3.11
 * 
 * Generated by: https://github.com/openapitools/openapi-generator.git
 *
 */

/**
 * NOTE: This class is auto generated by the openapi generator program.
 * https://github.com/openapitools/openapi-generator
 * Please update the test case below to test the endpoint.
 */

namespace OpenAPI\Server\Tests\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * TranslateApiInterfaceTest Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Server\Tests\Api
 * @author   openapi-generator contributors
 * @link     https://github.com/openapitools/openapi-generator
 * @coversDefaultClass \OpenAPI\Server\Api\TranslateApiInterface
 */
class TranslateApiInterfaceTest extends WebTestCase
{
    private static ?KernelBrowser $client = null;

    /**
     * Setup before running any test cases
     */
    public static function setUpBeforeClass(): void
    {
    }

    /**
     * Setup before running each test case
     */
    public function setUp(): void
    {
        if (null === self::$client) {
            self::$client = static::createClient();
        }
    }

    /**
     * Clean up after running each test case
     */
    public function tearDown(): void
    {
        static::ensureKernelShutdown();
    }

    /**
     * Clean up after running all test cases
     */
    public static function tearDownAfterClass(): void
    {
    }

    /**
     * Test case for detectPost
     *
     * Detect the language of a single text.
     *
     */
    public function testDetectPost(): void
    {
        $client = self::$client;

        $path = '/detect';

        $crawler = $client->request('POST', $path);
        $this->markTestSkipped('Test for detectPost not implemented');
    }

    /**
     * Test case for languagesGet
     *
     * Retrieve list of supported languages.
     *
     */
    public function testLanguagesGet(): void
    {
        $client = self::$client;

        $path = '/languages';

        $crawler = $client->request('GET', $path);
        $this->markTestSkipped('Test for languagesGet not implemented');
    }

    /**
     * Test case for translateFilePost
     *
     * Translate file from a language to another.
     *
     */
    public function testTranslateFilePost(): void
    {
        $client = self::$client;

        $path = '/translate_file';

        $crawler = $client->request('POST', $path);
        $this->markTestSkipped('Test for translateFilePost not implemented');
    }

    /**
     * Test case for translatePost
     *
     * Translate text from a language to another.
     *
     */
    public function testTranslatePost(): void
    {
        $client = self::$client;

        $path = '/translate';

        $crawler = $client->request('POST', $path);
        $this->markTestSkipped('Test for translatePost not implemented');
    }

    /**
     * @param string $regexp
     * @return mixed
     */
    protected function genTestData(string $regexp)
    {
        $grammar  = new \Hoa\File\Read('hoa://Library/Regex/Grammar.pp');
        $compiler = \Hoa\Compiler\Llk\Llk::load($grammar);
        $ast      = $compiler->parse($regexp);
        $generator = new \Hoa\Regex\Visitor\Isotropic(new \Hoa\Math\Sampler\Random());

        return $generator->visit($ast);
    }
}
