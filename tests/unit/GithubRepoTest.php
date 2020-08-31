<?php

namespace tests;

use app\models;

/**
 * GithubRepoTest contains test cases for github repo model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class GithubRepoTest extends \Codeception\Test\Unit
{
    /**
     * @dataProvider countProvider
     * Test case for counting repo rating
     *
     * @param int $forkCount
     * @param int $startCount
     * @param int $watcherCount
     * @param int $expected
     * @return void
     */
    public function testRatingCount($forkCount, $startCount, $watcherCount, $expected)
    {
        $repo = new models\GithubRepo("testRating", $forkCount, $startCount, $watcherCount);
        $this->assertEquals($expected, $repo->getRating());
    }

    /**
     * @dataProvider countProvider
     * Test case for repo model data serialization
     *
     * @param int $forkCount
     * @param int $startCount
     * @param int $watcherCount
     * @param int $ratingCount
     * @return void
     */
    public function testData($forkCount, $startCount, $watcherCount, $ratingCount)
    {
        $repo = new models\GithubRepo("testData", $forkCount, $startCount, $watcherCount);
        $expected = [
            'name' => 'testData',
            'fork-count' => $forkCount,
            'start-count' => $startCount,
            'watcher-count' => $watcherCount,
            'rating' => $ratingCount,
        ];
        $this->assertEquals($expected, $repo->getData());
    }

    /**
     * @dataProvider countProvider
     * Test case for repo model __toString verification
     *
     * @param int $forkCount
     * @param int $startCount
     * @param int $watcherCount
     * @return void
     */
    public function testStringify($forkCount, $startCount, $watcherCount)
    {
        $repo = new models\GithubRepo("testStringify", $forkCount, $startCount, $watcherCount);
        $expected = sprintf(
            "%-75s %4d ⇅ %4d ★ %4d 👁️",
            "testStringify",
            $forkCount,
            $startCount,
            $watcherCount
        );
        $this->assertEquals($expected, $repo->__toString());
    }

    public function countProvider()
    {
        return [
            [0, 0, 0, 0],
            [10, 20, 20, 16.666666666666668],
            [1, 1, 1.5, 1.3333333333333333],
            [1, null, 1, 1.0]
        ];
    }
}