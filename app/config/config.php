<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'version' => '1.0',
    //'database2' => \Phalcon\Di::getDefault()->get('database_config')['mysql'],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'modelsDir'      => APP_PATH . '/models/',
        'controllersDir'      => APP_PATH . '/controllers/',
        'servicesDir'      => APP_PATH . '/services/',
        'libraryDir'      => APP_PATH . '/library/',
        'viewsDir'      => APP_PATH . '/views/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'cacheDir'       => APP_PATH . '/cache/data/',
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ],

    /**
     * if true, then we print a new line at the end of each CLI execution
     *
     * If we dont print a new line,
     * then the next command prompt will be placed directly on the left of the output
     * and it is less readable.
     *
     * You can disable this behaviour if the output of your application needs to don't have a new line at end
     */
    'printNewLine' => true
]);
