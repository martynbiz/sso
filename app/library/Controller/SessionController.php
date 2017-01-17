<?php
namespace App\Controller;

use App\Model\User;

class SessionController extends BaseController
{
    public function login($request, $response, $args)
    {
        // if authenticated, return to the homepage 
        $container = $this->getContainer();
        if ($container->get('auth')->isAuthenticated()) {
            return $this->redirect('/');
        }

        return $this->render('session/login');

        // // check for remember me cookie.
        // // if the auth_token (remember me) cookie is set, and the user is not
        // // authenticated - handle the token (e.g. auto sign in)
        // $request = $container->get('request');
        // $rememberMe = $request->getCookieParam('auth_token');
        // if ($rememberMe and !$isAuthenticated) {
        //
        //     @list($selector, $token) = explode('_', $rememberMe);
        //
        //     // check validity of token
        //     try {
        //
        //         // test 1. find a valid token by selector
        //         $authToken = $container->get('model.auth_token')->findValidTokenBySelector($selector);
        //         if (! $authToken) {
        //
        //             // maybe the auth_token cookie has expired, and been cleaned from the
        //             // database. in any case, we'll just remove it from the client's machine
        //             $container->get('auth')->deleteAuthTokenCookie();
        //
        //             // throwing an exception will be caught and an error message displayed
        //             throw new InvalidAuthTokenException('Could not automatically sign in with \'Remember me\' token (0). Please login again.');
        //
        //         }
        //
        //         // test 2. ensure that this token matches the hashed token we have stored
        //         if (! $authToken->verifyToken($token)) {
        //
        //             // token string is invalid, this could be an attack at someone's user (or not)
        //             // remove the token from the database and the auth token and the client cookie
        //             $container->get('auth')->deleteAuthTokenCookie();
        //             $authToken->delete();
        //
        //             // throwing an exception will be caught and an error message displayed
        //             throw new InvalidAuthTokenException('Could not automatically sign in with \'Remember me\' token (1). Please login again.');
        //
        //         }
        //
        //         // test 3. get the user for this auth_token
        //         $user = $authToken->user;
        //         if (! $user) {
        //
        //             // user not found
        //             // remove the token from the database and the auth token and the client cookie
        //             $container->get('auth')->deleteAuthTokenCookie();
        //             $authToken->delete();
        //
        //             // throwing an exception will be caught and an error message displayed
        //             throw new InvalidAuthTokenException('Could not automatically sign in with \'Remember me\' token (2). Please login again.');
        //
        //         }
        //
        //
        //         // all good :) sign this person in using their auth_token...
        //
        //         // update remember me with new token
        //         $container->get('auth')->remember($user);
        //
        //         // set attributes. valid_attributes will only set the fields we
        //         // want to be avialable (e.g. not password)
        //         $container->get('auth')->setAttributes( array_merge($user->toArray(), array(
        //             'backend' => User::BACKEND_JAPANTRAVEL,
        //         )) );
        //
        //         // // redirect back to returnTo, or /session (logout page) if not provided
        //         // isset($params['returnTo']) or $params['returnTo'] = '/session';
        //         // return $this->returnTo($params['returnTo']);
        //
        //     } catch (\Exception $e) {
        //
        //         // delete any token that is associated with this $selector as it's invalid
        //         $container->get('model.auth_token')->deleteBySelector($selector);
        //
        //         // show error on next page - if passive login is used, this will be
        //         // returned in the returnTo url
        //         if (@$params['passive']) {
        //
        //             // // if we want to pass the error to the returnTo app then we ought to
        //             // // put it in the url here
        //             // if (parse_url($params['returnTo'], PHP_URL_QUERY)) {
        //             //     $params['returnTo'] .= '&loginError=' . urlencode($e->getMessage());
        //             // } else {
        //             //     $params['returnTo'] .= '?loginError=' . urlencode($e->getMessage());
        //             // }
        //
        //         } else {
        //
        //             // this will set an error message and continue to the login form
        //             $container->get('flash')->addMessage('errors', array(
        //                 $e->getMessage(),
        //             ));
        //
        //         }
        //     }
        // }

        // // at this stage the user may have been set here as a passive login request
        // // now that they have been autosigned in with the cookie perhaps, or we just
        // // wanna check if they were logged in anyway, we'll check if "passive" param
        // // is present and return them to the required url (loginreturnTo if not)
        // if (isset($params['passive'])) {
        //
        //     // this is passive login, so the return will differ depending on whether
        //     // the user is signed in or not.
        //     if ($isAuthenticated) {
        //
        //         // user is authenticated, so return them back to the loginCallback
        //         return $this->returnTo($params['returnTo']);
        //     } else {
        //
        //         // user is not logged in, so extract the returnTo from the returnTo
        //         // e.g. returnTo=jt.com/login?returnTo=http%3A%2F%2Fjt.com/here > jt.com/here
        //         $queryString = parse_url($params['returnTo'], PHP_URL_QUERY);
        //         parse_str($queryString, $queryArray);
        //         $returnTo = $queryArray['returnTo'];
        //
        //         // user is NOT authenticated, so return them back to returnTo
        //         return $this->returnTo($returnTo);
        //     }
        // }

        // if (@$params['passive']) {
        //
        //     // return user
        //     isset($params['returnTo']) or $params['returnTo'] = $settings->get('defaultLogoutRedirect', '/session');
        //     return $this->returnTo($params['returnTo']);
        //
        // } else {
        //
        //     // if the user is authenticated then we will show the logout page which
        //     // will serve as a landing page, although most typically apps will send
        //     // a DELETE request which will be handled by the delete() method
        //     // if the user is not authenticated, the show the login page
        //     if ($isAuthenticated) {
        //         return $this->render('session/logout', compact('params'));
        //     } else {
        //         return $this->render('session/login', compact('params'));
        //     }
        //
        // }
    }

    // /**
    //  * POST /session -- login
    //  */
    // public function post($request, $response, $args)
    // {
    //     // GET and POST
    //     $params = $request->getParams();
    //     $container = $this->getContainer();
    //     $settings = $container->get('settings');
    //
    //     // authentice with the email (might even be username, which is fine) and pw
    //     if ($container->get('auth')->authenticate($params['email'], $params['password'])) {
    //
    //         // as authentication has passed, get the user by email OR username
    //         $user = $container->get('model.user')
    //             ->where('email', $params['email'])
    //             ->orWhere('username', $params['email'])
    //             ->first();
    //
    //         // if requested (remember me checkbox), create remember me token cookie
    //         // else, remove the cookie (if exists)
    //         if (isset($params['remember_me'])) {
    //             $container->get('auth')->remember($user);
    //         } else {
    //             $container->get('auth')->forget($user);
    //         }
    //
    //         // set attributes. valid_attributes will only set the fields we
    //         // want to be avialable (e.g. not password)
    //         $container->get('auth')->setAttributes($user->toArray());
    //         // array_merge($user->toArray(), array(
    //         //     'backend' => User::BACKEND_JAPANTRAVEL,
    //         // )) );
    //
    //         // redirect back to returnTo, or /session (logout page - default) if not provided
    //         isset($params['returnTo']) or $params['returnTo'] = $settings->get('defaultLoginRedirect', '/');
    //         return $this->returnTo($params['returnTo']);
    //
    //     } else {
    //
    //         // forward them to the login page with errors to try again
    //         $container->get('flash')->addMessage('errors', array(
    //             $container->get('i18n')->translate('invalid_username_password'),
    //         ));
    //
    //         return $this->forward('login', func_get_args());
    //
    //     }
    // }

}
