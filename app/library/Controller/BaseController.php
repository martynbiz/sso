<?php
namespace App\Controller;

use Slim\Container;

class BaseController
{
    /**
     * @var Slim\Container
     */
    protected $container;

    //
    public function __construct(Container $container) {
       $this->container = $container;
    }

    /**
     * Shorthand method to get dependency from container
     * @param $name
     * @return mixed
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * Render the html and attach to the response
     * @param string $file Name of the template/ view to render
     * @param array $args Additional variables to pass to the view
     * @param Response?
     */
    public function render($file, $data=array())
    {
        $container = $this->getContainer();

        // // $data['currentUser'] = $container->get('auth')->getCurrentUser();

        if ($container->has('flash')) {
            $data['messages'] = $container->get('flash')->flushMessages();
        }

        if ($container->has('router')) {
            $data['router'] = $container->get('router');
        }

        if ($container->has('csrf')) {
            $request = $container->get('request');
            $data['csrfName'] = $request->getAttribute('csrf_name');
            $data['csrfValue'] = $request->getAttribute('csrf_value');
        }

        // generate the html
        $html = $container->get('renderer')->render($file, $data);

        // put the html in the response object
        $response = $container->get('response');
        $response->getBody()->write($html);

        return $response;
    }

    /**
     * Redirect.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * This method prepares the response object to return an HTTP Redirect
     * response to the client.
     *
     * @param  string|UriInterface $url    The redirect destination.
     * @param  int                 $status The redirect HTTP status code.
     * @return self
     */
    protected function redirect($url, $status = 302)
    {
        $container = $this->getContainer();
        $response = $container->get('response');
        return $response->withRedirect($url, $status);
    }

    /**
     * Pass on the control to another action. Of the same class (for now)
     *
     * @param  string $actionName The redirect destination.
     * @param array $data
     * @return Controller
     * @internal param string $status The redirect HTTP status code.
     */
    public function forward($actionName, $data=array())
    {
        return call_user_func_array(array($this, $actionName), $data);
    }

    /**
     * Will ensure that returnTo url is valid before doing redirect. Otherwise mean
     * people could use out login then redirect to a phishing site
     * @param string $returnTo The returnTo url that we want to check against our white list
     */
    protected function returnTo($returnTo)
    {
        $container = $this->getContainer();
        $settings = $container->get('settings');

        // check returnTo if it's a full domain  (e.g. http://...)
        $host = parse_url($returnTo, PHP_URL_HOST);
        if(strpos($returnTo, '/') !== 0) {
            $found = false;
            $validReturnToArray = (is_array($settings['valid_return_to'])) ? $settings['valid_return_to'] : array($settings['valid_return_to']);
            foreach($validReturnToArray as $validReturnTo) {
                if ($host and preg_match($validReturnTo, $host)) {
                    $found = true;
                }
            }
            if (! $found) {
                throw new InvalidReturnToUrl( $this->get('i18n')->translate('invalid_return_to') );
            }
        }

        return $this->redirect($returnTo);
    }
}
