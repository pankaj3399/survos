<?php

namespace Survos\GridGroupBundle\Tests;

use Generator;
use Hoa\File\Read;
use Survos\GridGroupBundle\Service\GridGroupService;
use Survos\GridGroupBundle\Service\Reader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Yaml\Yaml;

class GridGroupServiceTest extends KernelTestCase
{
    /**
     * @dataProvider steps
     */
    public function testTrim(array $test)
    {
        $result = GridGroupService::trim($test['raw']);

        $this->assertSame($result, $test['expected']);
    }

    public static function steps(): Generator
    {
        $data = Yaml::parseFile(__DIR__ . '/excel-trim-test.yaml');
        foreach ($data['trim'] as $test) {
            yield [$test['file'] => $test];
        }
    }
}
