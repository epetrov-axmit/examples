#### Run

After the environment is setup and configured a few things are left to make the application alive:

 * Run `./bin/setup` within a project root directory to install the app dependencies
 * CP the `config/autoload/local.php.dist` into the `config/autoload/local.php` file to prevent automatic
 configuration caching and turn on a debug mode
 * Run `./bin/start` to start the builtin PHP server configured to `public/index.php` as entrypoint. By default it will run
 on `80` port, but you can override it by specifying the `PORT` environment variable
 * Run `./bin/tests` to run the unit tests

#### libraries and Frameworks used

Application is built on top of PSR-7 Middleware stack based on Zend Expressive micro framework.

 * Zend Expressive is the ligthweight and extremely fast framework to develop a tiny standalone apps. It utilize
 the best tools and approaches such as DI via ContainerInterop compatible components within a core of the
 application, PSR-7 Middleware stack via Zend Stratigility component and flexible routing via different routing providers
 as a thirdparty dependencies.
 * Zend Service Locator as its flexibility in controlling over DI and compatibility with ConteinerInterop Interface which
 means framework agnostic solution.
 * Aura Router as really fast and simple Router implementation which allows to setup parametrized routes
 * Guzzle Http Client as a clean and simple abstraction over PSR-7 and HTTP layer. Very helpful for testing and flexible
 in taking a control over the HTTP requests and responses.
 * PHPUnit and Mockery for testing environment as a full featured testing tools.

About the app
--------

Web application that provides an API endpoint for transforming email addresses into gravatar uris

```
# GET /gravatr/{email}
+ Response 200 (text/plain)

    https://www.gravatar.com/avatar/sdgdsf3535sdfg.jpeg
```
