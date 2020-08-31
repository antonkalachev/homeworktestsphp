<?php

namespace tests;

use app\models;

/**
 * GitlabRepoTest contains test cases for gitlab repo model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class GitlabRepoTest extends \Codeception\Test\Unit
{
    /**
     * @dataProvider countProvider
     * Test case for counting repo rating
     *
     * @param int $forkCount
     * @param int $startCount
     * @param int $expected
     * @return void
     */
    public function testRatingCount($forkCount, $startCount, $expected)
    {
        $repo = new models\GitlabRepo("testRating", $forkCount, $startCount);
        $this->assertEquals($expected, $repo->getRating());
    }

    /**
     * @dataProvider countProvider
     * Test case for repo model data serialization
     *
     * @param int $forkCount
     * @param int $startCount
     * @param int $ratingCount
     * @return void
     */
    public function testData($forkCount, $startCount, $ratingCount)
    {
        $repo = new models\GitlabRepo("testData", $forkCount, $startCount);
        $expected = [
            'name' => 'testData',
            'fork-count' => $forkCount,
            'start-count' => $startCount,
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
     * @return void
     */
    public function testStringify($forkCount, $startCount)
    {
        $repo = new models\GitlabRepo("testStringify", $forkCount, $startCount);
        $expected = sprintf(
            "%-75s %4d ⇅ %4d ★",
            "testStringify",
            $forkCount,
            $startCount
        );
        $this->assertEquals($expected, $repo->__toString());
    }

    public function countProvider()
    {
        return [
            [0, 0, 0],
            [10, 20, 15.0],
            [1, 1, 1.25],
            [1, null, 1]
        ];
    }
}