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


require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../src/app.php';
require_once __DIR__.'/../../../src/utils.php';


use Symfony\Component\Validator\Constraints as Assert;

$app->match('/raspisanie/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
    $start = 0;
    $vars = $request->request->all();
    $qsStart = (int)$vars["start"];
    $search = $vars["search"];
    $order = $vars["order"];
    $columns = $vars["columns"];
    $qsLength = (int)$vars["length"];    
    
    if($qsStart) {
        $start = $qsStart;
    }    
	
    $index = $start;   
    $rowsPerPage = $qsLength;
       
    $rows = array();
    
    $searchValue = $search['value'];
    $orderValue = $order[0];
    
    $orderClause = "";
    if($orderValue) {
        $orderClause = " ORDER BY ". $columns[(int)$orderValue['column']]['data'] . " " . $orderValue['dir'];
    }
    
    $table_columns = array(
		'id', 
		'tip_raspisaniya_id', 
		'predmety_id', 
		'potoki_id', 
		'data_provedeniya', 
		'auditoriya', 

    );
    
    $table_columns_type = array(
		'int(10) unsigned', 
		'int(10) unsigned', 
		'int(10) unsigned', 
		'int(10) unsigned', 
		'varchar(19)', 
		'varchar(9)', 

    );    
    
    $whereClause = "";
    
    $i = 0;
    foreach($table_columns as $col){
        
        if ($i == 0) {
           $whereClause = " WHERE";
        }
        
        if ($i > 0) {
            $whereClause =  $whereClause . " OR"; 
        }
        
        $whereClause =  $whereClause . " " . $col . " LIKE '%". $searchValue ."%'";
        
        $i = $i + 1;
    }
    
    $recordsTotal = $app['db']->fetchColumn("SELECT COUNT(*) FROM `raspisanie`" . $whereClause . $orderClause, array(), 0);
    
    $find_sql = "SELECT * FROM `raspisanie`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
        for($i = 0; $i < count($table_columns); $i++){

			if($table_columns[$i] == 'tip_raspisaniya_id'){
			    $findexternal_sql = 'SELECT `name` FROM `tip_raspisaniya` WHERE `id` = ?';
			    $findexternal_row = $app['db']->fetchAssoc($findexternal_sql, array($row_sql[$table_columns[$i]]));
			    $rows[$row_key][$table_columns[$i]] = $findexternal_row['name'];
			}
			else if($table_columns[$i] == 'predmety_id'){
			    $findexternal_sql = 'SELECT `name` FROM `predmety` WHERE `id` = ?';
			    $findexternal_row = $app['db']->fetchAssoc($findexternal_sql, array($row_sql[$table_columns[$i]]));
			    $rows[$row_key][$table_columns[$i]] = $findexternal_row['name'];
			}
			else if($table_columns[$i] == 'potoki_id'){
			    $findexternal_sql = 'SELECT `name` FROM `potoki` WHERE `id` = ?';
			    $findexternal_row = $app['db']->fetchAssoc($findexternal_sql, array($row_sql[$table_columns[$i]]));
			    $rows[$row_key][$table_columns[$i]] = $findexternal_row['name'];
			}
			else{
			    $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
			}


        }
    }    
    
    $queryData = new queryData();
    $queryData->start = $start;
    $queryData->recordsTotal = $recordsTotal;
    $queryData->recordsFiltered = $recordsTotal;
    $queryData->data = $rows;
    
    return new Symfony\Component\HttpFoundation\Response(json_encode($queryData), 200);
});




/* Download blob img */
$app->match('/raspisanie/download', function (Symfony\Component\HttpFoundation\Request $request) use ($app) { 
    
    // menu
    $rowid = $request->get('id');
    $idfldname = $request->get('idfld');
    $fieldname = $request->get('fldname');
    
    if( !$rowid || !$fieldname ) die("Invalid data");
    
    $find_sql = "SELECT " . $fieldname . " FROM " . raspisanie . " WHERE ".$idfldname." = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($rowid));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Строка не найдена!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('menu_list'));
    }

    header('Content-Description: File Transfer');
    header('Content-Type: image/jpeg');
    header("Content-length: ".strlen( $row_sql[$fieldname] ));
    header('Expires: 0');
    header('Cache-Control: public');
    header('Pragma: public');
    ob_clean();    
    echo $row_sql[$fieldname];
    exit();
   
    
});



$app->match('/raspisanie', function () use ($app) {
    
	$table_columns = array(
		'id', 
		'tip_raspisaniya_id', 
		'predmety_id', 
		'potoki_id', 
		'data_provedeniya', 
		'auditoriya', 

    );

    $primary_key = "id";

    $image_tag_insertion = array();

    foreach($table_columns as $idx => $table_column) {
        if(isset($app['image_fields']['raspisanie' . '.' . $table_column])) {
            $image_tag_insertion[] = array(
                'column_idx' => $imageIdx = $idx,
                'image_path' => $app['image_fields']['raspisanie' . '.' . $table_column],
                'column_name' => $table_column
            );
        }
    }

    return $app['twig']->render('raspisanie/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key,
        "image_tag_insertion" => $image_tag_insertion
    ));
        
})
->bind('raspisanie_list');



$app->match('/raspisanie/create', function () use ($app) {
    
    $initial_data = array(
		'tip_raspisaniya_id' => '', 
		'predmety_id' => '', 
		'potoki_id' => '', 
		'data_provedeniya' => '', 
		'auditoriya' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `tip_raspisaniya`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('tip_raspisaniya_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('tip_raspisaniya_id', 'text', array('required' => false));
	}

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `predmety`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('predmety_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('predmety_id', 'text', array('required' => false));
	}

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `potoki`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('potoki_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('potoki_id', 'text', array('required' => false));
	}



	$form = $form->add('data_provedeniya', 'text', array('required' => false));
	$form = $form->add('auditoriya', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `raspisanie` (`tip_raspisaniya_id`, `predmety_id`, `potoki_id`, `data_provedeniya`, `auditoriya`) VALUES (?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['tip_raspisaniya_id'], $data['predmety_id'], $data['potoki_id'], $data['data_provedeniya'], $data['auditoriya']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'raspisanie создана!',
                )
            );
            return $app->redirect($app['url_generator']->generate('raspisanie_list'));

        }
    }

    return $app['twig']->render('raspisanie/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('raspisanie_create');



$app->match('/raspisanie/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `raspisanie` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Строка не найдена!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('raspisanie_list'));
    }

    
    $initial_data = array(
		'tip_raspisaniya_id' => $row_sql['tip_raspisaniya_id'], 
		'predmety_id' => $row_sql['predmety_id'], 
		'potoki_id' => $row_sql['potoki_id'], 
		'data_provedeniya' => $row_sql['data_provedeniya'], 
		'auditoriya' => $row_sql['auditoriya'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `tip_raspisaniya`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('tip_raspisaniya_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('tip_raspisaniya_id', 'text', array('required' => false));
	}

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `predmety`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('predmety_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('predmety_id', 'text', array('required' => false));
	}

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `potoki`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('potoki_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('potoki_id', 'text', array('required' => false));
	}


	$form = $form->add('data_provedeniya', 'text', array('required' => false));
	$form = $form->add('auditoriya', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `raspisanie` SET `tip_raspisaniya_id` = ?, `predmety_id` = ?, `potoki_id` = ?, `data_provedeniya` = ?, `auditoriya` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array($data['tip_raspisaniya_id'], $data['predmety_id'], $data['potoki_id'], $data['data_provedeniya'], $data['auditoriya'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'raspisanie изменена!',
                )
            );
            return $app->redirect($app['url_generator']->generate('raspisanie_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('raspisanie/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('raspisanie_edit');


$app->match('/raspisanie/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `raspisanie` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `raspisanie` WHERE `id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'raspisanie удалена!',
            )
        );
    }
    else{
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Строка не найдена!',
            )
        );  
    }

    return $app->redirect($app['url_generator']->generate('raspisanie_list'));

})
->bind('raspisanie_delete');



$app->match('/raspisanie/downloadList', function (Symfony\Component\HttpFoundation\Request $request) use($app){
    
    $table_columns = array(
		'id', 
		'tip_raspisaniya_id', 
		'predmety_id', 
		'potoki_id', 
		'data_provedeniya', 
		'auditoriya', 

    );
    
    $table_columns_type = array(
		'int(10) unsigned', 
		'int(10) unsigned', 
		'int(10) unsigned', 
		'int(10) unsigned', 
		'varchar(19)', 
		'varchar(9)', 

    );   

    $types_to_cut = array('blob');
    $index_of_types_to_cut = array();
    foreach ($table_columns_type as $key => $value) {
        if(in_array($value, $types_to_cut)){
            unset($table_columns[$key]);
        }
    }

    $columns_to_select = implode(',', array_map(function ($row){
        return '`'.$row.'`';
    }, $table_columns));
     
    $find_sql = "SELECT ".$columns_to_select." FROM `raspisanie`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());
  
    $mpdf = new mPDF();

    $stylesheet = file_get_contents('../web/resources/css/bootstrap.min.css'); // external css
    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->WriteHTML('.table {
    border-radius: 5px;
    width: 100%;
    margin: 0px auto;
    float: none;
}',1);

    $mpdf->WriteHTML(build_table($rows_sql));
    $mpdf->Output();
})->bind('raspisanie_downloadList');



