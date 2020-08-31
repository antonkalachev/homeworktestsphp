<?php

namespace tests;

use app\models;

/**
 * UserTest contains test cases for user model
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class UserTest extends \Codeception\Test\Unit
{
    /**
     * @dataProvider userProvider
     * Test case for adding repo models to user model
     *
     * IMPORTANT NOTE:
     * Should cover succeeded and failed suites
     *
     * @param string $identifier
     * @param string $name
     * @param string $platform
     * @param int $forkCount
     * @param int $watcherCount
     * @param int $startCount
     * @param int $totalRating
     * @return void
     */
    public function testAddingRepos($identifier, $name, $platform, $forkCount, $watcherCount, $startCount, $totalRating)
    {
        $user = new models\User($identifier, $name, $platform);
        $repoBitbucket = new models\BitbucketRepo("bitbucket", $forkCount, $watcherCount);
        $repoGitlab = new models\GitlabRepo("gitlab", $forkCount, $startCount);
        $repos = [
            $repoBitbucket,
            $repoGitlab
        ];
        $user->addRepos($repos);
        $actual = $user->getData();
        $expected = [
            [
                "name" => "bitbucket",
                "fork-count" => $forkCount,
                "watcher-count" => $watcherCount,
                "rating" => $totalRating / 2
            ],
            [
                "name" => "gitlab",
                "fork-count" => $forkCount,
                "start-count" => $startCount,
                "rating" => $totalRating / 2
            ]
        ];
        $this->assertEquals($expected, $actual["repo"]);
    }

    /**
     * @dataProvider userProvider
     * Test case for counting total user rating
     *
     * @param string $identifier
     * @param string $name
     * @param string $platform
     * @param int $forkCount
     * @param int $watcherCount
     * @param int $startCount
     * @param int $expected
     * @return void
     */
    public function testTotalRatingCount($identifier, $name, $platform, $forkCount, $watcherCount, $startCount, $expected)
    {
        $user = new models\User($identifier, $name, $platform);
        $repoBitbucket = new models\BitbucketRepo("bitbucket", $forkCount, $watcherCount);
        $repoGitlab = new models\GitlabRepo("gitlab", $forkCount, $startCount);
        $repos = [
            $repoBitbucket,
            $repoGitlab
        ];
        $user->addRepos($repos);
        $this->assertEquals($expected, $user->getTotalRating());
    }

    /**
     * @dataProvider userProvider
     * Test case for user model data serialization
     *
     * @param string $identifier
     * @param string $name
     * @param string $platform
     * @param int $forkCount
     * @param int $watcherCount
     * @param int $startCount
     * @param int $totalRating
     * @return void
     */
    public function testData($identifier, $name, $platform, $forkCount, $watcherCount, $startCount, $totalRating)
    {
        $user = new models\User($identifier, $name, $platform);
        $repoBitbucket = new models\BitbucketRepo("bitbucket", $forkCount, $watcherCount);
        $repoGitlab = new models\GitlabRepo("gitlab", $forkCount, $startCount);
        $repos = [
            $repoBitbucket,
            $repoGitlab
        ];
        $user->addRepos($repos);
        $expected = [
            "name" => $name,
            "platform" => $platform,
            "total-rating" => $totalRating,
            "repos" => [],
            "repo" => [
                [
                    "name" => "bitbucket",
                    "fork-count" => $forkCount,
                    "watcher-count" => $watcherCount,
                    "rating" => $totalRating / 2
                ],
                [
                    "name" => "gitlab",
                    "fork-count" => $forkCount,
                    "start-count" => $startCount,
                    "rating" => $totalRating / 2
                ]
            ]
        ];
        $this->assertEquals($expected, $user->getData());
    }

    /**
     * @dataProvider userProvider
     * Test case for user model __toString verification
     *
     * @param string $identifier
     * @param string $name
     * @param string $platform
     * @param int $forkCount
     * @param int $watcherCount
     * @param int $startCount
     * @param int $totalRating
     * @return void
     */
    public function testStringify($identifier, $name, $platform, $forkCount, $watcherCount, $startCount, $totalRating)
    {
        $user = new models\User($identifier, $name, $platform);
        $repoBitbucket = new models\BitbucketRepo("bitbucket", $forkCount, $watcherCount);
        $repoGitlab = new models\GitlabRepo("gitlab", $forkCount, $startCount);
        $repos = [
            $repoBitbucket,
            $repoGitlab
        ];
        $user->addRepos($repos);
        $fullName = $user->getFullName();
        $expected = sprintf(
            "%-75s %19d 🏆\n%'=98s\n%s\n%s\n",
            $fullName,
            $totalRating,
            "",
            (string)$repoBitbucket,
            (string)$repoGitlab
        );

        $this->assertEquals($expected, $user->__toString());
    }

    public function userProvider()
    {
        return [
            ["test", "test", "gitlab", 10, 10, 20, 30],
            ["test", "kfr", "bitbucket", 0, 0, 0, 0]
        ];
    }
}