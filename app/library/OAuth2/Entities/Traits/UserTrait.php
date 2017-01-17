<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\OAuth2\Entities\Traits;

trait UserTrait
{
    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $email;

    /**
     * Get the user's first name.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the user's first name.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get the user's last name.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set the user's last name.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get the user's username.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the user's username.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get the user's email.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getEmail()
    {
        return $this->last_name;
    }

    /**
     * Set the user's email.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}
