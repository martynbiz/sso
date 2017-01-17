<?php
namespace Tests\Functional;

class SessionControllerTest extends BaseTestCase
{
    public function testGetLogin()
    {
        $response = $this->runApp('GET', '/login');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('form#login_form', (string)$response->getBody()); // has form
    }

    // public function testPostLoginWithValidCredentials()
    // {
    //     // mock authenticate to return true
    //     $this->container['auth']
    //         ->expects( $this->once() )
    //         ->method('authenticate')
    //         ->willReturn(true);
    //
    //     $response = $this->runApp('POST', '/login', [
    //         'email' => 'martyn@example.com',
    //         'password' => 'password1',
    //     ]);
    //
    //     // assertions
    //     $this->assertEquals(302, $response->getStatusCode());
    // }

    // public function testPostRegisterWithInvalidCredentials()
    // {
    //     $response = $this->runApp('POST', '/register', [
    //         'email' => 'martyn@example.com',
    //         'password' => 'password1',
    //     ]);
    //
    //     // assertions
    //     $this->assertEquals(200, $response->getStatusCode());
    //     $this->assertQuery('form#login_form', (string)$response->getBody()); // has form
    //     $this->assertQuery('.alert.alert-danger', (string)$response->getBody()); // showing errors
    // }
}
