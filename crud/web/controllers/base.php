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


require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../src/app.php';


require_once __DIR__.'/abiturienty/index.php';
require_once __DIR__.'/ekzamenacionnyj_list/index.php';
require_once __DIR__.'/fakultety/index.php';
require_once __DIR__.'/gorod/index.php';
require_once __DIR__.'/gruppy/index.php';
require_once __DIR__.'/kafedry/index.php';
require_once __DIR__.'/konkurs_na_kafedru/index.php';
require_once __DIR__.'/lgoty_postupayuschih/index.php';
require_once __DIR__.'/potok_grupp/index.php';
require_once __DIR__.'/potoki/index.php';
require_once __DIR__.'/predmety/index.php';
require_once __DIR__.'/raspisanie/index.php';
require_once __DIR__.'/role/index.php';
require_once __DIR__.'/strana/index.php';
require_once __DIR__.'/tip_raspisaniya/index.php';
require_once __DIR__.'/uchebnye_zavedeniya/index.php';
require_once __DIR__.'/users/index.php';
require_once __DIR__.'/vedomosti/index.php';



$app->match('/', function () use ($app) {

    return $app['twig']->render('ag_dashboard.html.twig', array());
        
})
->bind('dashboard');


$app->run();