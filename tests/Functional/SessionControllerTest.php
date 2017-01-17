<?php
namespace Tests\Functional;

class SessionControllerTest extends BaseTestCase
{
    public function testGetLoginShowsFormWhenNotAuthenticated()
    {
        $response = $this->runApp('GET', '/login');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('form#login_form', (string)$response->getBody()); // has form
    }

    public function testGetLoginRedirectsWhenAuthenticated()
    {
        // mock authenticate to return true
        $this->app->getContainer()['auth']
            ->expects( $this->once() )
            ->method('isAuthenticated')
            ->willReturn(true);

        $response = $this->runApp('GET', '/login');

        // assertions
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testPostLoginWithValidCredentials()
    {
        // mock authenticate to return true
        $this->app->getContainer()['auth']
            ->expects( $this->once() )
            ->method('authenticate')
            ->willReturn(true);

        $response = $this->runApp('POST', '/login', [
            'email' => 'martyn@example.com',
            'password' => 'password1',
        ]);

        // assertions
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testPostLoginWithInvalidCredentials()
    {
        // mock authenticate to return true
        $this->app->getContainer()['auth']
            ->expects( $this->once() )
            ->method('authenticate')
            ->willReturn(false);

        $response = $this->runApp('POST', '/login', [
            'email' => 'martyn@example.com',
            'password' => 'password1',
        ]);

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('form#login_form', (string)$response->getBody()); // has form
        $this->assertQuery('.alert.alert-danger', (string)$response->getBody()); // showing errors
    }

    public function testGetLogoutShowsFormWhenAuthenticated()
    {
        // mock authenticate to return true
        $this->app->getContainer()['auth']
            ->expects( $this->once() )
            ->method('isAuthenticated')
            ->willReturn(true);

        $response = $this->runApp('GET', '/logout');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('form#logout_form', (string)$response->getBody()); // has form
    }

    public function testGetLogoutRedirectsWhenAuthenticated()
    {
        $response = $this->runApp('GET', '/logout');

        // assertions
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testPostLogout()
    {
        // mock authenticate to return true
        $this->app->getContainer()['auth']
            ->expects( $this->once() )
            ->method('clearAttributes');

        $response = $this->runApp('POST', '/logout', [
            '_METHOD' => 'DELETE',
        ]);

        // assertions
        $this->assertEquals(302, $response->getStatusCode());
    }
}
