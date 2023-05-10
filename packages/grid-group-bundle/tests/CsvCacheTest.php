<?php

namespace Survos\GridGroupBundle\Tests;

use PHPUnit\Framework\TestCase;
use Survos\GridGroupBundle\Service\CsvCache;
use Survos\GridGroupBundle\Service\CsvDatabase;
use Symfony\Component\Yaml\Yaml;

class CsvCacheTest extends TestCase
{
    /**
     * @dataProvider csvSteps
     */
    public function testCsvCache(array $test): void
    {
        $csvDatabase = new CsvDatabase($test['db'], $test['key'], $test['headers'] ?? []);
        $csvDatabase->flushFile(); // purge?  reset? We need to start with a clean file.
        $csvDatabase->purge();
        $csvCache = new CsvCache($test['db'], $test['key'], $test['headers'] ?? [], $csvDatabase);

        foreach ($test['steps'] as $step) {
            $key = $step['key'] ?? null;
            $data = $step['data'] ?? [];
            $expects = $step['expects'] ?? null;
            $csv = $step['csv'] ?? null;

            $actual = match ($operation = $step['operation']) {
                'contains' => $csvCache->contains($key),
                'set' => $csvCache->set($key, $data),
                'get' => $csvCache->get($key),

                default =>
                assert(false, "Operation not supported " . $operation)
            };

            if (!is_null($expects)) {
                $this->assertSame($expects, $actual);
            }
            if (!is_null($csv)) {
                $this->assertSame($csv, file_get_contents($csvDatabase->getFilename()));
            }
        }
    }

    /**
     * @dataProvider csvSteps
     */
    public function testBaseMethods($test)
    {
        $csvCache = new CsvCache($test['db'], $test['key'], []);
        $this->assertSame($test['db'], $csvCache->getCsvFilename());
        $this->assertSame($test['key'], $csvCache->getKeyName());
        $this->assertSame([], $csvCache->getHeaders());

        $csvCache->setKeyName('testKey');
        $this->assertSame('testKey', $csvCache->getKeyName());

        $csvCache->setHeaders($test['headers']);
        $this->assertSame($test['headers'], $csvCache->getHeaders());
    }

    public static function csvSteps()
    {
        $data = Yaml::parseFile(__DIR__ . '/cache-test.yaml');
        foreach ($data['cache'] as $test) {
            yield [$test['db'] => $test];
        }
    }
}
