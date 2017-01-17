<?php
namespace App\Auth\Adapter;

// use Zend\Authentication\Adapter\AdapterInterface;
// use Zend\Authentication\Result;

use App\Model\User;

class Eloquent implements AdapterInterface
{
    /**
     * @var string
     */
    protected $identity;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var App\Model\User
     */
    protected $model;

    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Performs an authentication attempt
     */
    public function authenticate($identity, $password)
    {
        // look up $user from the database
        $user = $this->model->where('email', $identity)
            ->orWhere('username', $identity)
            ->first();
        if (!$user) return false;

        // $level = "08";
		// $salt = '$2a$' . $level . '$' . $user->salt . '$';
		// $hashed = crypt($password, $salt);

        return password_verify($password, $user->password);
        // return ($user and ($hashed === $user->password));
    }
}
