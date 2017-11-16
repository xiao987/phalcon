<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit71f27deb19b17fe21a6b22802c7339ba
{
    public static $files = array (
        '841780ea2e1d6545ea3a253239d59c05' => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu/functions.php',
        '8a815d32598840ae7a1914edf1138d44' => __DIR__ . '/../..' . '/app/helpers/common.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Q' => 
        array (
            'Qiniu\\' => 6,
        ),
        'P' => 
        array (
            'Payment\\' => 8,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Qiniu\\' => 
        array (
            0 => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu',
        ),
        'Payment\\' => 
        array (
            0 => __DIR__ . '/..' . '/riverslei/payment/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'App\\Library\\AppSign' => __DIR__ . '/../..' . '/app/library/AppSign.php',
        'Marser\\App\\Backend\\BackendModule' => __DIR__ . '/../..' . '/app/backend/BackendModule.php',
        'Marser\\App\\Backend\\Controllers\\AccountController' => __DIR__ . '/../..' . '/app/backend/controllers/AccountController.php',
        'Marser\\App\\Backend\\Controllers\\AdminController' => __DIR__ . '/../..' . '/app/backend/controllers/AdminController.php',
        'Marser\\App\\Backend\\Controllers\\Ajax\\AdminController' => __DIR__ . '/../..' . '/app/backend/controllers/ajax/AdminController.php',
        'Marser\\App\\Backend\\Controllers\\Ajax\\MenuController' => __DIR__ . '/../..' . '/app/backend/controllers/ajax/MenuController.php',
        'Marser\\App\\Backend\\Controllers\\Ajax\\PassportController' => __DIR__ . '/../..' . '/app/backend/controllers/ajax/PassportController.php',
        'Marser\\App\\Backend\\Controllers\\Ajax\\RoleController' => __DIR__ . '/../..' . '/app/backend/controllers/ajax/RoleController.php',
        'Marser\\App\\Backend\\Controllers\\BaseController' => __DIR__ . '/../..' . '/app/backend/controllers/BaseController.php',
        'Marser\\App\\Backend\\Controllers\\DashboardController' => __DIR__ . '/../..' . '/app/backend/controllers/DashboardController.php',
        'Marser\\App\\Backend\\Controllers\\IndexController' => __DIR__ . '/../..' . '/app/backend/controllers/IndexController.php',
        'Marser\\App\\Backend\\Controllers\\MenuController' => __DIR__ . '/../..' . '/app/backend/controllers/MenuController.php',
        'Marser\\App\\Backend\\Controllers\\OptionsController' => __DIR__ . '/../..' . '/app/backend/controllers/OptionsController.php',
        'Marser\\App\\Backend\\Controllers\\PassportController' => __DIR__ . '/../..' . '/app/backend/controllers/PassportController.php',
        'Marser\\App\\Backend\\Controllers\\RoleController' => __DIR__ . '/../..' . '/app/backend/controllers/RoleController.php',
        'Marser\\App\\Backend\\Repositories\\Admin' => __DIR__ . '/../..' . '/app/backend/repositories/Admin.php',
        'Marser\\App\\Backend\\Repositories\\BaseRepository' => __DIR__ . '/../..' . '/app/backend/repositories/BaseRepository.php',
        'Marser\\App\\Backend\\Repositories\\Menu' => __DIR__ . '/../..' . '/app/backend/repositories/Menu.php',
        'Marser\\App\\Backend\\Repositories\\Options' => __DIR__ . '/../..' . '/app/backend/repositories/Options.php',
        'Marser\\App\\Backend\\Repositories\\RepositoryFactory' => __DIR__ . '/../..' . '/app/backend/repositories/RepositoryFactory.php',
        'Marser\\App\\Core\\Config' => __DIR__ . '/../..' . '/app/core/Config.php',
        'Marser\\App\\Core\\PhalBaseController' => __DIR__ . '/../..' . '/app/core/PhalBaseController.php',
        'Marser\\App\\Core\\PhalBaseFilter' => __DIR__ . '/../..' . '/app/core/PhalBaseFilter.php',
        'Marser\\App\\Core\\PhalBaseLogger' => __DIR__ . '/../..' . '/app/core/PhalBaseLogger.php',
        'Marser\\App\\Core\\PhalBaseModel' => __DIR__ . '/../..' . '/app/core/PhalBaseModel.php',
        'Marser\\App\\Core\\PhalBaseTask' => __DIR__ . '/../..' . '/app/core/PhalBaseTask.php',
        'Marser\\App\\Core\\PhalBaseUrl' => __DIR__ . '/../..' . '/app/core/PhalBaseUrl.php',
        'Marser\\App\\Core\\PhalBaseVolt' => __DIR__ . '/../..' . '/app/core/PhalBaseVolt.php',
        'Marser\\App\\Frontend\\Controllers\\BaseController' => __DIR__ . '/../..' . '/app/frontend/controllers/BaseController.php',
        'Marser\\App\\Frontend\\Controllers\\IndexController' => __DIR__ . '/../..' . '/app/frontend/controllers/IndexController.php',
        'Marser\\App\\Frontend\\Controllers\\Sp_api\\LoginController' => __DIR__ . '/../..' . '/app/frontend/controllers/sp_api/LoginController.php',
        'Marser\\App\\Frontend\\Controllers\\Sp_api\\SpBaseController' => __DIR__ . '/../..' . '/app/frontend/controllers/sp_api/SpBaseController.php',
        'Marser\\App\\Frontend\\Controllers\\Sp_api\\UserController' => __DIR__ . '/../..' . '/app/frontend/controllers/sp_api/UserController.php',
        'Marser\\App\\Frontend\\FrontendModule' => __DIR__ . '/../..' . '/app/frontend/FrontendModule.php',
        'Marser\\App\\Frontend\\Repositories\\BaseRepository' => __DIR__ . '/../..' . '/app/frontend/repositories/BaseRepository.php',
        'Marser\\App\\Frontend\\Repositories\\Menu' => __DIR__ . '/../..' . '/app/frontend/repositories/Menu.php',
        'Marser\\App\\Frontend\\Repositories\\Options' => __DIR__ . '/../..' . '/app/frontend/repositories/Options.php',
        'Marser\\App\\Frontend\\Repositories\\RepositoryFactory' => __DIR__ . '/../..' . '/app/frontend/repositories/RepositoryFactory.php',
        'Marser\\App\\Helpers\\PaginatorHelper' => __DIR__ . '/../..' . '/app/helpers/PaginatorHelper.php',
        'Marser\\App\\Library\\Base62' => __DIR__ . '/../..' . '/app/library/Base62.php',
        'Marser\\App\\Library\\DemoTest' => __DIR__ . '/../..' . '/app/library/DemoTest.php',
        'Marser\\App\\Library\\ErrorCode' => __DIR__ . '/../..' . '/app/library/DemoTest.php',
        'Marser\\App\\Library\\Filter' => __DIR__ . '/../..' . '/app/library/Filter.php',
        'Marser\\App\\Library\\JwtAuth' => __DIR__ . '/../..' . '/app/library/JwtAuth.php',
        'Marser\\App\\Library\\Parser' => __DIR__ . '/../..' . '/app/library/Parser.php',
        'Marser\\App\\Library\\ServerNeedle' => __DIR__ . '/../..' . '/app/library/ServerNeedle.php',
        'Marser\\App\\Library\\Validator' => __DIR__ . '/../..' . '/app/library/Validator.php',
        'Marser\\App\\Library\\WechatAuth' => __DIR__ . '/../..' . '/app/library/WechatAuth.php',
        'Marser\\App\\Models\\Admin' => __DIR__ . '/../..' . '/app/models/Admin.php',
        'Marser\\App\\Models\\AdminModel' => __DIR__ . '/../..' . '/app/models/AdminModel.php',
        'Marser\\App\\Models\\BaseModel' => __DIR__ . '/../..' . '/app/models/BaseModel.php',
        'Marser\\App\\Models\\Menu' => __DIR__ . '/../..' . '/app/models/Menu.php',
        'Marser\\App\\Models\\MenuModel' => __DIR__ . '/../..' . '/app/models/MenuModel.php',
        'Marser\\App\\Models\\ModelFactory' => __DIR__ . '/../..' . '/app/models/ModelFactory.php',
        'Marser\\App\\Models\\OptionsModel' => __DIR__ . '/../..' . '/app/models/OptionsModel.php',
        'Marser\\App\\Models\\Role' => __DIR__ . '/../..' . '/app/models/Role.php',
        'Marser\\App\\Models\\RoleMenu' => __DIR__ . '/../..' . '/app/models/RoleMenu.php',
        'Marser\\App\\Tasks\\BaseTask' => __DIR__ . '/../..' . '/app/tasks/BaseTask.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit71f27deb19b17fe21a6b22802c7339ba::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit71f27deb19b17fe21a6b22802c7339ba::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit71f27deb19b17fe21a6b22802c7339ba::$classMap;

        }, null, ClassLoader::class);
    }
}
