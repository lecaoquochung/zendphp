<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    // ...
    'db' => array(
        'driver'            => 'Pdo_Mysql',
        'dsn'               => 'mysql:dbname=' .getenv('MYSQL_ENV_MYSQL_DATABASE') .';host=' .getenv('MYSQL_PORT_3306_TCP_ADDR'),
        'username'          => getenv('MYSQL_ENV_MYSQL_USER'),
        'password'          => getenv('MYSQL_ENV_MYSQL_PASSWORD'),
        'driver_options'    => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
     ),

    // 'db' => [
    //     'driver' => 'Pdo',
    //     'dsn'    => sprintf('sqlite:%s/data/zendphp.db', realpath(getcwd())),
    // ],

    'service_manager' => array(
         'factories' => array(
             'Zend\Db\Adapter\Adapter'
                     => 'Zend\Db\Adapter\AdapterServiceFactory',
         ),
     ),
];
