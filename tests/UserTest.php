<?php

use App\User;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    const SUT = 'App\User';

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserHasOneToManyWorkdaysRelation()
    {
        $mockBuilder = $this->getMockBuilder(self::SUT);
        $mockBuilder->setMethods(array('hasMany'));
        $user = $mockBuilder->getMock();

        $user->expects($this->once())
            ->method('hasMany')
            ->with('App\Workday')
            ->will($this->returnValue('WorkdaysMock'));

        /** @var User $user */
        $this->assertSame('WorkdaysMock', $user->workdays());
    }

    public function testTestRunnerUserExistsInDatabase()
    {
        $this->seeInDatabase('users', ['name' => 'testrunner']);
    }

    public function testUserCanLogInAndLogOut()
    {
        $this->visit('/auth/login')
            ->type('testrunner@opentimetracker.org', 'email')
            ->type('tester123', 'password')
            ->press('login-button')
            ->seePageIs('/home')
			->click('Logout')
			->seePageIs('/')
			->dontSee('Settings'); // Settings page is only visible when you are logged in.
    }
}
