<?php
namespace Tests\Functional;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

use Symfony\Component\DomCrawler\Crawler;

use App\Model\User;
use App\Model\Meta;
use App\Model\AuthToken;
use App\Model\RecoveryToken;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = true;

    /**
     * Useful to have $app here so we can access during tests
     *
     * @var Slim\App
     */
    protected $app = null;

    /**
     * @var App\Model\User
     */
    protected $user = null;

    // /**
    //  * @var App\Model\Meta
    //  */
    // protected $meta = null;
    //
    // /**
    //  * @var App\Model\Meta
    //  */
    // protected $recoveryToken = null;

    /**
     * We wanna also build $app so that we can gain access to container
     */
    public function setUp()
    {
        // Use the application settings
        $settings = require __DIR__ . '/../../app/settings/settings.php';

        // Instantiate the application
        $app = new App($settings);

        // Set up dependencies
        require __DIR__ . '/../../app/dependencies.php';

        // In some cases, where services have become "frozen", we need to define
        // mocks before they are loaded

        $container = $app->getContainer();

        //  auth service
        $container['auth'] = $this->getMockBuilder('App\\Auth\\Auth')
            ->disableOriginalConstructor()
            ->getMock();

        //  auth service
        $container['mail_manager'] = $this->getMockBuilder('App\\Mail\\Manager')
            ->disableOriginalConstructor()
            ->getMock();

        // Register middleware
        if ($this->withMiddleware) {
            require __DIR__ . '/../../app/middleware.php';
        }

        // Register routes
        require __DIR__ . '/../../app/routes.php';

        $this->app = $app;

        // fill db with test data

        $this->user = User::create([
            'first_name' => 'Martyn',
            'last_name' => 'Bissett',
            'name' => 'Martyn Bissett',
            'username' => 'martyn',
            'email' => 'martyn@example.com',
            'password' => 'password1',
        ]);

        $this->user = Meta::create([
            'user_id' => $this->user->id,
            'name' => 'facebook_id',
            'value' => '1234567890',
        ]);

        $this->user = RecoveryToken::create([
            'user_id' => $this->user->id,
            'selector' => '1234567890',
            'token' => 'qwertyuiop1234567890',
            'expire' => date('Y-m-d H:i:s', strtotime("+3 months", time())),
        ]);
    }

    public function tearDown()
    {
        $container = $this->app->getContainer();
        $settings = $container->get('settings');

        // as we have foreign key constraints on meta, we cannot use
        // truncate (even if the table is empty). so we need to temporarily
        // turn off FOREIGN_KEY_CHECKS

        $connection = (new User())->getConnection();

        // in vagrant, we have an sqlite db. we may still want to run tests there too
        // to ensure the installation is working ok. so we need to disable foreign keys
        // different from mysql
        switch($settings['eloquent']['driver']) {
            case 'sqlite':
                $connection->statement('PRAGMA foreign_keys = OFF;');
                break;
            case 'mysql':
            default:
                $connection->statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // clear tables
        User::truncate();
        Meta::truncate();
        AuthToken::truncate();
        RecoveryToken::truncate();

        // turn foreign key checks back on
        switch($settings['eloquent']['driver']) {
            case 'sqlite':
                $connection->statement('PRAGMA foreign_keys = ON;');
                break;
            case 'mysql':
            default:
                $connection->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Process the application given a request method and URI
     *
     * @param string $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string $requestUri the request URI
     * @param array|object|null $requestData the request data
     * @return \Slim\Http\Response
     */
    public function runApp($requestMethod, $requestUri, $requestData = null)
    {
        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        // Set up a response object
        $response = new Response();

        // Process the application
        $response = $this->app->process($request, $response);

        // Return the response
        return $response;
    }

    // public function login($user)
    // {
    //     // return an identity (eg. email)
    //     $this->container['auth']
    //         ->method('getAttributes')
    //         ->willReturn( $user->toArray() );
    //
    //     // by defaut, we'll make isAuthenticated return a false
    //     $this->container['auth']
    //         ->method('isAuthenticated')
    //         ->willReturn(true);
    // }

    // /**
    //  * Assert response is a redirect
    //  *
    //  * @param $query string
    //  * @param $html string
    //  * @return boolean
    //  */
    // public function assertRedirects($response)
    // {
    //     // although there is a $response->isRedirect() method,
    //     // assertEquals with status codes gives a more meaningful
    //     // error message
    //     return $this->assertEquals(200, $response->getStatusCode());
    // }
    //
    // /**
    //  * Assert response is a redirect to a path
    //  *
    //  * @param $query string
    //  * @param $html string
    //  * @return boolean
    //  */
    // public function assertRedirectsTo($url, $response)
    // {
    //     return $this->assertEquals($url, $response->getHeaderLine('Location'));
    // }

    /**
     * Will crawl html string for a given query (e.g. form#register)
     *
     * @param $query string
     * @param $html string
     * @return boolean
     */
    public function assertQuery($query, $html)
    {
        $crawler = new Crawler($html);
        return $this->assertEquals(1, $crawler->filter($query)->count());
    }

    /**
     * Will crawl html string for a given query (e.g. form#register)
     *
     * @param $query string
     * @param $html string
     * @return boolean
     */
    public function assertQueryCount($query, $count, $html)
    {
        $crawler = new Crawler($html);
        $this->assertEquals($count, $crawler->filter($query)->count());
    }
}
