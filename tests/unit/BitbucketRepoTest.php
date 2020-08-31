<?php

namespace tests;

use app\models;

/**
 * BitbucketRepoTest contains test cases for bitbucket repo model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class BitbucketRepoTest extends \Codeception\Test\Unit
{
    /**
     * @dataProvider countProvider
     * Test case for counting repo rating
     *
     * @param int $forkCount
     * @param int $watcherCount
     * @param int $expected
     * @return void
     */
    public function testRatingCount($forkCount, $watcherCount, $expected)
    {
        $repo = new models\BitbucketRepo("testRating", $forkCount, $watcherCount);
        $this->assertEquals($expected, $repo->getRating());
    }

    /**
     * @dataProvider countProvider
     * Test case for repo model data serialization
     *
     * @param int $forkCount
     * @param int $watcherCount
     * @param int $ratingCount
     * @return void
     */
    public function testData($forkCount, $watcherCount, $ratingCount)
    {
        $repo = new models\BitbucketRepo("testData", $forkCount, $watcherCount);
        $expected = [
            'name' => 'testData',
            'fork-count' => $forkCount,
            'watcher-count' => $watcherCount,
            'rating' => $ratingCount,
        ];
        $this->assertEquals($expected, $repo->getData());
    }

    /**
     * @dataProvider countProvider
     * Test case for repo model __toString verification
     *
     * @return void
     */
    public function testStringify($forkCount, $watcherCount)
    {
        $repo = new models\BitbucketRepo("testStringify", $forkCount, $watcherCount);
        $expected = sprintf(
            "%-75s %4d ⇅ %6s %4d 👁️",
            "testStringify",
            $forkCount,
            "",
            $watcherCount
        );
        $this->assertEquals($expected, $repo->__toString());
    }

    public function countProvider()
    {
        return [
            [0, 0, 0],
            [10, 20, 20],
            [1, 1, 1.5],
            [1, null, 1]
        ];
    }
}