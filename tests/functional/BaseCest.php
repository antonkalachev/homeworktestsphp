<?php

/**
 * Base contains test cases for testing api endpoint
 * 
 * @see https://codeception.com/docs/modules/Yii2
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class BaseCest
{
    /**
     * Example test case
     *
     * @return void
     */
    public function cestExample(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'kfr',
            ],
            'platforms' => [
                'github',
            ]
        ]);
        $expected = json_decode('[
            {
                "name": "kfr",
                "platform": "github",
                "total-rating": 1.5,
                "repos": [],
                "repo": [
                    {
                        "name": "kf-cli",
                        "fork-count": 0,
                        "start-count": 2,
                        "watcher-count": 2,
                        "rating": 1
                    },
                    {
                        "name": "cards",
                        "fork-count": 0,
                        "start-count": 0,
                        "watcher-count": 0,
                        "rating": 0
                    },
                    {
                        "name": "UdaciCards",
                        "fork-count": 0,
                        "start-count": 0,
                        "watcher-count": 0,
                        "rating": 0
                    },
                    {
                        "name": "unikgen",
                        "fork-count": 0,
                        "start-count": 1,
                        "watcher-count": 1,
                        "rating": 0.5
                    }
                ]
            }
        ]');
        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with bad request params
     *
     * @return void
     */
    public function cestBadParams(\FunctionalTester $I)
    {
        $dataMap = ["users" => [
            'base/api',
            'user' => [
                'kfr',
            ],
            'platforms' => [
                'github',
            ]
        ],
        "platforms" => [
            'base/api',
            'users' => [
                'kfr',
            ],
            'platform' => [
                'github',
            ]
        ]
        ];

        foreach ($dataMap as $param => $data) {
            $I->amOnPage($data);
            $expected = sprintf('Bad Request: Missing required parameters: %s', $param);
            $actual = strip_tags($I->grabPageSource());
            $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
            $I->assertEquals($expected, $actual);
        }
    }

    /**
     * Test case for api with empty user list
     *
     * @return void
     */
    public function cestEmptyUsers(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [],
            'platforms' => [
                'github',
            ]
        ]);
        $expected = "Bad Request: Missing required parameters: users";
        $actual = strip_tags($I->grabPageSource());
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->assertEquals($expected, $actual);
    }

    /**
     * Test case for api with empty platform list
     *
     * @return void
     */
    public function cestEmptyPlatforms(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'kfr',
            ],
            'platforms' => []
        ]);
        $expected = "Bad Request: Missing required parameters: platforms";
        $actual = strip_tags($I->grabPageSource());
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::BAD_REQUEST);
        $I->assertEquals($expected, $actual);
    }

    /**
     * Test case for api with non empty platform list
     *
     * @return void
     */
    public function cestSeveralPlatforms(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'kfr',
            ],
            'platforms' => [
                'github',
                'bitbucket',
                'gitlab',
            ]
        ]);
        $expected = json_decode('[
            {
                "name": "kfr",
                "platform": "github",
                "total-rating": 1.5,
                "repos": [],
                "repo": [
                    {
                        "name": "kf-cli",
                        "fork-count": 0,
                        "start-count": 2,
                        "watcher-count": 2,
                        "rating": 1
                    },
                    {
                        "name": "cards",
                        "fork-count": 0,
                        "start-count": 0,
                        "watcher-count": 0,
                        "rating": 0
                    },
                    {
                        "name": "UdaciCards",
                        "fork-count": 0,
                        "start-count": 0,
                        "watcher-count": 0,
                        "rating": 0
                    },
                    {
                        "name": "unikgen",
                        "fork-count": 0,
                        "start-count": 1,
                        "watcher-count": 1,
                        "rating": 0.5
                    }
                ]
            }
        ]');
        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with non empty user list
     *
     * @return void
     */
    public function cestSeveralUsers(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                'kfr',
                'test',
            ],
            'platforms' => [
                'github',
            ]
        ]);
        $expected = json_decode(file_get_contents(__DIR__ . '/TestData/testResponseForSeveralUsers.json', FILE_USE_INCLUDE_PATH));
        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with unknown platform in list
     *
     * @return void
     */
    public function cestUnknownPlatforms(\FunctionalTester $I)
    {

        $I->expectThrowable(\yii\base\ErrorException::class, function() use ($I) {
            $I->amOnPage([
                'base/api',
                'users' => [
                    'kfr',
                ],
                'platforms' => [
                    'unknown',
                ]
            ]);
        });
    }

    /**
     * Test case for api with unknown user in list
     *
     * @return void
     */
    public function cestUnknownUsers(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                '%8*~~{}~(!',
            ],
            'platforms' => [
                'github',
            ]
        ]);
        $I->assertEmpty(json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with mixed (unknown, real) users and non empty platform list
     *
     * @return void
     */
    public function cestMixedUsers(\FunctionalTester $I)
    {
        $I->amOnPage([
            'base/api',
            'users' => [
                '%8*~~{}~(!',
                'test',
            ],
            'platforms' => [
                'github',
            ]
        ]);
        $expected = json_decode(file_get_contents(__DIR__ . '/TestData/testResponseForMixedUsers.json', FILE_USE_INCLUDE_PATH));
        $I->assertEquals($expected, json_decode($I->grabPageSource()));
    }

    /**
     * Test case for api with mixed (github, gitlab, bitbucket) platforms and non empty user list
     *
     * @return void
     */
    public function cestMixedPlatforms(\FunctionalTester $I)
    {
        $I->expectThrowable(\yii\base\ErrorException::class, function() use ($I) {
            $I->amOnPage([
                'base/api',
                'users' => [
                    'kfr',
                ],
                'platforms' => [
                    'bitbucket',
                    'unknown',
                    'gitlab',
                ]
            ]);
        });
    }
}