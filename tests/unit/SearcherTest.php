<?php

namespace tests;

use app\components;
use app\models\BitbucketRepo;
use app\models\GithubRepo;
use app\models\GitlabRepo;
use app\models\User;

/**
 * SearcherTest contains test cases for searcher component
 * 
 * IMPORTANT NOTE:
 * All test cases down below must be implemented
 * You can add new test cases on your own
 * If they could be helpful in any form
 */
class SearcherTest extends \Codeception\Test\Unit
{
    /**
     * Test case for searching via several platforms
     * 
     * IMPORTANT NOTE:
     * Should cover succeeded and failed suites
     *
     * @return void
     */
    public function testSearcher()
    {
        $searcher = new components\Searcher();
        $platformGithub = $this->createMock(components\platforms\Github::class);
        $platformGitlab = $this->createMock(components\platforms\Gitlab::class);
        $platforms = [
            $platformGithub,
            $platformGitlab,
        ];
        $users = [
            'test',
            'kfr',
        ];
        $user = $this->createMock(User::class);
        $repoGithub = $this->createMock(GithubRepo::class);
        $repoGitlab = $this->createMock(GitlabRepo::class);
        $platformGithub->expects($this->any())->method('findUserInfo')->will($this->returnValue($user));
        $platformGitlab->expects($this->any())->method('findUserInfo')->will($this->returnValue($user));
        $user->expects($this->exactly(4))->method('getIdentifier')->willReturnOnConsecutiveCalls("test", "test", "kfr", "kfr");
        $platformGithub->expects($this->any())->method('findUserRepos')->willReturn([$repoGithub]);
        $platformGitlab->expects($this->any())->method('findUserRepos')->willReturn([$repoGitlab]);
        $user->expects($this->any())->method('addRepos');
        $user->expects($this->exactly(6))->method('getTotalRating');
        $actual = $searcher->search($platforms, $users);
        $this->assertEquals(4, count($actual));
    }
}