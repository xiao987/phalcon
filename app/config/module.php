<?php

$baseName = '';

$application->registerModules([
 'frontend' => array(
            'className' => 'Marser\App\Frontend\FrontendModule',
            'path' => BASE_PATH . '/app/frontend/FrontendModule.php',
        ),
        'backend' => array(
            'className' => 'Marser\App\Backend\BackendModule',
            'path' => BASE_PATH . '/app/backend/BackendModule.php',
    ),

]);
