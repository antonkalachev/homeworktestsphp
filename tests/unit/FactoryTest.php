<?php

namespace tests;

use app\components;
use yii\base\ErrorException;
use yii\base\Exception;

/**
 * FactoryTest contains test cases for factory component
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class FactoryTest extends \Codeception\Test\Unit
{
    /**
     * @dataProvider platformProvider
     * Test case for creating platform component
     * 
     * IMPORTANT NOTE:
     * Should cover succeeded and failed suites
     *
     * @return void
     */
    public function testCreate($expected, $platform)
    {
        $factory = new components\Factory();
        try {
            $actual = $factory->create($platform);
            $this->assertInstanceOf($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf($expected, $e);
        }
    }

    public function platformProvider()
    {
        return [
            [components\platforms\Github::class, 'github'],
            [components\platforms\Gitlab::class, 'gitlab'],
            [components\platforms\Bitbucket::class, 'bitbucket'],
            [\Exception::class, 'unknown']
        ];
    }
}