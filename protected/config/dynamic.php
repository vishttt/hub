<?php return array (
  'components' => 
  array (
    'db' => 
    array (
      'class' => 'yii\\db\\Connection',
      'dsn' => 'mysql:host=localhost;dbname=humhub',
      'username' => 'root',
      'password' => '1800545455',
      'charset' => 'utf8',
    ),
    'user' => 
    array (
    ),
    'mailer' => 
    array (
      'transport' => 
      array (
        'class' => 'Swift_MailTransport',
      ),
      'view' => 
      array (
        'theme' => 
        array (
          'name' => 'HumHub',
          'basePath' => '/var/www/html/humhub/themes/HumHub',
          'publishResources' => false,
        ),
      ),
    ),
    'cache' => 
    array (
      'class' => 'yii\\caching\\FileCache',
      'keyPrefix' => 'humhub',
    ),
    'view' => 
    array (
      'theme' => 
      array (
        'name' => 'HumHub',
        'basePath' => '/var/www/html/humhub/themes/HumHub',
        'publishResources' => false,
      ),
    ),
    'formatter' => 
    array (
      'defaultTimeZone' => 'Asia/Bangkok',
    ),
    'formatterApp' => 
    array (
      'defaultTimeZone' => 'Asia/Bangkok',
      'timeZone' => 'Asia/Bangkok',
    ),
  ),
  'params' => 
  array (
    'installer' => 
    array (
      'db' => 
      array (
        'installer_hostname' => 'localhost',
        'installer_database' => 'humhub',
      ),
    ),
    'config_created_at' => 1477467036,
    'horImageScrollOnMobile' => '1',
    'databaseInstalled' => true,
    'installed' => true,
  ),
  'name' => 'HumHub',
  'language' => 'vi',
  'timeZone' => 'Asia/Bangkok',
); ?>