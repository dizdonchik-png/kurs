<?php

/*
 * This file is part of the CRUD Admin Generator project.
 *
 * Author: Jon Segador <jonseg@gmail.com>
 * Web: http://crud-admin-generator.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class queryData {
	public $start;
	public $recordsTotal;
	public $recordsFiltered;
	public $data;
}

use Silex\Application;

$app = new Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/../web/views',
));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
	'translator.messages' => array(),
));
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->before(function ($request) {
    $request->getSession()->start();
    if(!isset($_SESSION["login"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== '1'){ // если не задана переменная сессии login и role и role !== 1, то перейти на главную, в противном случае далее выполнять код
        header("location: ../../report/index.php");
        exit;
    }
});
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(

    'dbs.options' => array(
        'db' => array(
            'driver'   => 'pdo_mysql',
            'dbname'   => 'institution',
            'host'     => 'localhost',
            'user'     => 'root',
            'password' => '',
            'charset'  => 'utf8',
        ),
    )
));

$app['asset_path'] = 'http://kurs/crud/web/resources';
$app['debug'] = true;
	// array of REGEX column name to display for foreigner key insted of ID
	// default used :'name','title','e?mail','username'
$app['usr_search_names_foreigner_key'] = array();

$app['application_name'] = 'Kursach';

// determine image path for image fields in database
// I.E field value would be image.jpg, result would be <img src="http://somepath/dist/images/image.jpg" />
$app['image_fields'] = array(
);

// Allow user to add additional menu links
$app['menu_links'] = array(
);
return $app;
