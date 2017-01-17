<?php
namespace Tests\Functional;

class UsersControllerTest extends BaseTestCase
{
    public function testGetRegister()
    {
        $response = $this->runApp('GET', '/register');

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('form#register_form', (string)$response->getBody()); // has form
    }

    public function testPostRegisterWithValidData()
    {
        $response = $this->runApp('POST', '/register', static::getUserValues());

        // assertions
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testPostRegisterWithInvalidData($firstName, $lastName, $email, $password, $agreement, $moreInfo)
    {
        $userValues = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
            'agreement' => $agreement,
            'more_info' => $moreInfo,
        ];
        if (!$agreement) unset($userValues['agreement']);

        $response = $this->runApp('POST', '/register', $userValues);

        // assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertQuery('form#register_form', (string)$response->getBody()); // has form
        $this->assertQuery('.alert.alert-danger', (string)$response->getBody()); // showing errors
    }



    private static function getUserValues($values=array())
    {
        return array_merge([
            'first_name' => 'Martyn',
            'last_name' => 'Bissett',
            'email' => 'martynbissett@yahoo.co.uk',
            'password' => 'T3st!ng123',
            'agreement' => '1',
            'more_info' => '',
        ], $values);
    }

    public function getInvalidData()
    {
        return [
            static::getUserValues(['first_name' => '']),
            static::getUserValues(['last_name' => '']),
            static::getUserValues(['email' => '']),
            static::getUserValues(['email' => 'martyn']),
            static::getUserValues(['email' => 'martyn@']),
            static::getUserValues(['email' => 'martyn@yahoo']),
            static::getUserValues(['password' => '']),
            static::getUserValues(['password' => 'easypass']),
            static::getUserValues(['agreement' => null]),
            static::getUserValues(['more_info' => 'hello']),
        ];
    }
}
