<?php
namespace App\Controller;

use Zend\Diactoros\Stream;

use App\OAuth2\Entities\UserEntity;

class OauthController extends BaseController
{
    /**
     * Will grant auth_code to then request access token
     * GET /oauth/authorize
     */
    public function authorize($request, $response, $args)
    {
        $container = $this->getContainer();
        $server = $container->get('authorization_server');

        try {

            // Validate the HTTP request and return an AuthorizationRequest object.
            $authRequest = $server->validateAuthorizationRequest($request);

            // The auth request object can be serialized and
            // saved into a user's session. You will probably want
            // to redirect the user at this point to a login endpoint.

            if (!$container->get('auth')->isAuthenticated()) {
                // oauth params (e.g. state) will be set here
                $params = $request->getParams();

                // redirect to the login page
                return $this->redirect('/login?' . http_build_query(array(
                    'returnTo' => '/oauth/authorize?' . http_build_query($params),
                )));
            }

            // get the authenticated user
            $user = $this->getCurrentUser();
            $UserEntity = new UserEntity();
            $UserEntity->setIdentifier($user->id);
            $UserEntity->setFirstName($user->first_name);
            $UserEntity->setLastName($user->last_name);
            $UserEntity->setUsername($user->username);
            $UserEntity->setEmail($user->email);

            // Once the user has logged in set the user on the AuthorizationRequest
            $authRequest->setUser($UserEntity); // an instance of UserEntityInterface

            // At this point you should redirect the user to an authorization page.
            // This form will ask the user to approve the client and the scopes requested.

            // Once the user has approved or denied the client update the status
            // (true = approved, false = denied)
            $authRequest->setAuthorizationApproved(true);

            // Return the HTTP redirect response
            return $server->completeAuthorizationRequest($authRequest, $response);

        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $body = new Stream('php://temp', 'r+');
            $body->write($exception->getMessage());
            return $response->withStatus(500)->withBody($body);
        }

        // } catch (OAuthServerException $exception) {
        //
        //     // All instances of OAuthServerException can be formatted into a HTTP response
        //     return $exception->generateHttpResponse($response);
        //
        // } catch (\Exception $exception) {
        //
        //     // // Unknown exception
        //     // $body = new Stream('php://temp', 'r+');
        //     // $body->write($exception->getMessage());
        //     // return $response->withStatus(500)->withBody($body);
        //
        //     return $exception->getMessage();
        //
        // }



        // $container = $this->getContainer();
        // $server = $container->get('authorization_server');
        // $params = $request->getParams();
        //
        // // First ensure the parameters in the query string are correct
        // try {
        //
        //     $authParams = $server->getGrantType('authorization_code')->checkAuthorizeParams();
        //
        // } catch (\Exception $e) {
        //
        //     return $this->renderJson( array(
        //         'error'     =>  $e->errorType,
        //         'message'   =>  $e->getMessage(),
        //     ), $e->httpStatusCode);
        // }
        //
        // // Normally at this point you would show the user a sign-in screen and ask
        // // them to authorize the requested scopes. In this system, if they are not
        // // logged in then redirect to the login screen.
        // if ($container->get('auth')->isAuthenticated()) {
        //
        //     // get account
        //     $attributes = $this->get('auth')->getAttributes();
        //
        // } else {
        //
        //     // redirect to the login page
        //     return $this->redirect('/session?' . http_build_query(array(
        //         'returnTo' => '/oauth/authorize?' . http_build_query($params),
        //     )));
        // }
        //
        // // Create a new authorize request which will respond with a redirect URI that the user will be redirected to
        //
        // // here we wanna pass the owner type and id.
        // // TODO does redirect uri also contain returnTo?
        // // TODO we should use username perhaps for id, or maybe we want a sso_id used by client apps ?
        // $redirectUri = $server->getGrantType('authorization_code')->newAuthorizeRequest('user', $attributes['id'], $authParams);
        //
        // return $this->redirect($redirectUri);
    }

    // /**
    //  * Will grant access token to then use APIs (e.g. accounts)
    //  * GET /oauth/authorize
    //  */
    // public function accessToken()
    // {
    //     $server = $this->get('authorization_server');
    //
    //     // TODO at this stage we may just be receiving client credentials, which we wanna look
    //     // up in the db if a user has any client credentials attached to their account
    //     // $_POST: array(5) {
    //     //   ["grant_type"]=>
    //     //   string(18) "client_credentials"
    //     //   ["client_id"]=>
    //     //   string(11) "japantravel"
    //     //   ["client_secret"]=>
    //     //   string(20) "qwertyuiop1234567890"
    //     //   ["username"]=>
    //     //   string(10) "japantravl"
    //     //   ["password"]=>
    //     //   string(19) "qwertyuiop123456789"
    //     // }
    //
    //     try {
    //
    //         // invalid_client?
    //         $response = $server->issueAccessToken();
    //
    //         $this->get('response')->write( json_encode($response) );
    //         return $this->get('response')->withStatus( 200 );
    //
    //     } catch (\Exception $e) {
    //
    //         return $this->renderJson( array(
    //             'error'     =>  $e->errorType,
    //             'message'   =>  $e->getMessage(),
    //         ), 500 ); // TODO $e->getHttpHeaders()
    //
    //     }
    // }

    /**
     * Will validate auth_code to then grant access token
     * GET /oauth/authorize
     */
    public function accessToken($request, $response, $args)
    {
        $container = $this->getContainer();
        $server = $container->get('authorization_server');

        try {
            return $server->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $body = new Stream('php://temp', 'r+');
            $body->write($exception->getMessage());
            return $response->withStatus(500)->withBody($body);
        }
    }

    /**
     * Will validate auth_code to then grant access token
     * GET /oauth/authorize
     */
    public function user($request, $response, $args)
    {
        // Add the resource server middleware which will intercept and validate requests
        // $app->add(
        //     new \League\OAuth2\Server\Middleware\ResourceServerMiddleware(
        //         $app->getContainer()->get(ResourceServer::class)
        //     )
        // );

        $user = \App\Model\User::first();

        $response->getBody()->write(json_encode($user));
        return $response->withStatus(200);
    }
}
