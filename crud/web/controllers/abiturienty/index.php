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

$app->match('/abiturienty/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'familiya', 
		'name', 
		'otchestvo', 
		'data_rozh', 
		'uchebnye_zavedeniya_id', 
		'pasport', 
		'data_okonchaniya', 
		'lgoty_postupayuschih_id', 
		'gruppy_id', 
		'nomer', 

    );
    
    $table_columns_type = array(
		'int(10) unsigned', 
		'varchar(11)', 
		'varchar(9)', 
		'varchar(13)', 
		'varchar(19)', 
		'int(10) unsigned', 
		'varchar(9)', 
		'varchar(19)', 
		'int(10) unsigned', 
		'int(10) unsigned', 
		'tinyint(4)', 

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
    
    $recordsTotal = $app['db']->fetchColumn("SELECT COUNT(*) FROM `abiturienty`" . $whereClause . $orderClause, array(), 0);
    
    $find_sql = "SELECT * FROM `abiturienty`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
        for($i = 0; $i < count($table_columns); $i++){

			if($table_columns[$i] == 'uchebnye_zavedeniya_id'){
			    $findexternal_sql = 'SELECT `name` FROM `uchebnye_zavedeniya` WHERE `id` = ?';
			    $findexternal_row = $app['db']->fetchAssoc($findexternal_sql, array($row_sql[$table_columns[$i]]));
			    $rows[$row_key][$table_columns[$i]] = $findexternal_row['name'];
			}
			else if($table_columns[$i] == 'lgoty_postupayuschih_id'){
			    $findexternal_sql = 'SELECT `name` FROM `lgoty_postupayuschih` WHERE `id` = ?';
			    $findexternal_row = $app['db']->fetchAssoc($findexternal_sql, array($row_sql[$table_columns[$i]]));
			    $rows[$row_key][$table_columns[$i]] = $findexternal_row['name'];
			}
			else if($table_columns[$i] == 'gruppy_id'){
			    $findexternal_sql = 'SELECT `name` FROM `gruppy` WHERE `id` = ?';
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
$app->match('/abiturienty/download', function (Symfony\Component\HttpFoundation\Request $request) use ($app) { 
    
    // menu
    $rowid = $request->get('id');
    $idfldname = $request->get('idfld');
    $fieldname = $request->get('fldname');
    
    if( !$rowid || !$fieldname ) die("Invalid data");
    
    $find_sql = "SELECT " . $fieldname . " FROM " . abiturienty . " WHERE ".$idfldname." = ?";
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



$app->match('/abiturienty', function () use ($app) {
    
	$table_columns = array(
		'id', 
		'familiya', 
		'name', 
		'otchestvo', 
		'data_rozh', 
		'uchebnye_zavedeniya_id', 
		'pasport', 
		'data_okonchaniya', 
		'lgoty_postupayuschih_id', 
		'gruppy_id', 
		'nomer', 

    );

    $primary_key = "id";

    $image_tag_insertion = array();

    foreach($table_columns as $idx => $table_column) {
        if(isset($app['image_fields']['abiturienty' . '.' . $table_column])) {
            $image_tag_insertion[] = array(
                'column_idx' => $imageIdx = $idx,
                'image_path' => $app['image_fields']['abiturienty' . '.' . $table_column],
                'column_name' => $table_column
            );
        }
    }

    return $app['twig']->render('abiturienty/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key,
        "image_tag_insertion" => $image_tag_insertion
    ));
        
})
->bind('abiturienty_list');



$app->match('/abiturienty/create', function () use ($app) {
    
    $initial_data = array(
		'familiya' => '', 
		'name' => '', 
		'otchestvo' => '', 
		'data_rozh' => '', 
		'uchebnye_zavedeniya_id' => '', 
		'pasport' => '', 
		'data_okonchaniya' => '', 
		'lgoty_postupayuschih_id' => '', 
		'gruppy_id' => '', 
		'nomer' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `uchebnye_zavedeniya`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('uchebnye_zavedeniya_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('uchebnye_zavedeniya_id', 'text', array('required' => false));
	}

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `lgoty_postupayuschih`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('lgoty_postupayuschih_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('lgoty_postupayuschih_id', 'text', array('required' => false));
	}

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `gruppy`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('gruppy_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('gruppy_id', 'text', array('required' => false));
	}



	$form = $form->add('familiya', 'text', array('required' => false));
	$form = $form->add('name', 'text', array('required' => false));
	$form = $form->add('otchestvo', 'text', array('required' => false));
	$form = $form->add('data_rozh', 'text', array('required' => false));
	$form = $form->add('pasport', 'text', array('required' => false));
	$form = $form->add('data_okonchaniya', 'text', array('required' => false));
	$form = $form->add('nomer', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `abiturienty` (`familiya`, `name`, `otchestvo`, `data_rozh`, `uchebnye_zavedeniya_id`, `pasport`, `data_okonchaniya`, `lgoty_postupayuschih_id`, `gruppy_id`, `nomer`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['familiya'], $data['name'], $data['otchestvo'], $data['data_rozh'], $data['uchebnye_zavedeniya_id'], $data['pasport'], $data['data_okonchaniya'], $data['lgoty_postupayuschih_id'], $data['gruppy_id'], $data['nomer']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'abiturienty создана!',
                )
            );
            return $app->redirect($app['url_generator']->generate('abiturienty_list'));

        }
    }

    return $app['twig']->render('abiturienty/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('abiturienty_create');



$app->match('/abiturienty/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `abiturienty` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Строка не найдена!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('abiturienty_list'));
    }

    
    $initial_data = array(
		'familiya' => $row_sql['familiya'], 
		'name' => $row_sql['name'], 
		'otchestvo' => $row_sql['otchestvo'], 
		'data_rozh' => $row_sql['data_rozh'], 
		'uchebnye_zavedeniya_id' => $row_sql['uchebnye_zavedeniya_id'], 
		'pasport' => $row_sql['pasport'], 
		'data_okonchaniya' => $row_sql['data_okonchaniya'], 
		'lgoty_postupayuschih_id' => $row_sql['lgoty_postupayuschih_id'], 
		'gruppy_id' => $row_sql['gruppy_id'], 
		'nomer' => $row_sql['nomer'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `uchebnye_zavedeniya`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('uchebnye_zavedeniya_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('uchebnye_zavedeniya_id', 'text', array('required' => false));
	}

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `lgoty_postupayuschih`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('lgoty_postupayuschih_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('lgoty_postupayuschih_id', 'text', array('required' => false));
	}

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `gruppy`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('gruppy_id', 'choice', array(
	        'required' => false,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('gruppy_id', 'text', array('required' => false));
	}


	$form = $form->add('familiya', 'text', array('required' => false));
	$form = $form->add('name', 'text', array('required' => false));
	$form = $form->add('otchestvo', 'text', array('required' => false));
	$form = $form->add('data_rozh', 'text', array('required' => false));
	$form = $form->add('pasport', 'text', array('required' => false));
	$form = $form->add('data_okonchaniya', 'text', array('required' => false));
	$form = $form->add('nomer', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `abiturienty` SET `familiya` = ?, `name` = ?, `otchestvo` = ?, `data_rozh` = ?, `uchebnye_zavedeniya_id` = ?, `pasport` = ?, `data_okonchaniya` = ?, `lgoty_postupayuschih_id` = ?, `gruppy_id` = ?, `nomer` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array($data['familiya'], $data['name'], $data['otchestvo'], $data['data_rozh'], $data['uchebnye_zavedeniya_id'], $data['pasport'], $data['data_okonchaniya'], $data['lgoty_postupayuschih_id'], $data['gruppy_id'], $data['nomer'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'abiturienty изменена!',
                )
            );
            return $app->redirect($app['url_generator']->generate('abiturienty_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('abiturienty/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('abiturienty_edit');


$app->match('/abiturienty/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `abiturienty` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `abiturienty` WHERE `id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'abiturienty удалена!',
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

    return $app->redirect($app['url_generator']->generate('abiturienty_list'));

})
->bind('abiturienty_delete');



$app->match('/abiturienty/downloadList', function (Symfony\Component\HttpFoundation\Request $request) use($app){
    
    $table_columns = array(
		'id', 
		'familiya', 
		'name', 
		'otchestvo', 
		'data_rozh', 
		'uchebnye_zavedeniya_id', 
		'pasport', 
		'data_okonchaniya', 
		'lgoty_postupayuschih_id', 
		'gruppy_id', 
		'nomer', 

    );
    
    $table_columns_type = array(
		'int(10) unsigned', 
		'varchar(11)', 
		'varchar(9)', 
		'varchar(13)', 
		'varchar(19)', 
		'int(10) unsigned', 
		'varchar(9)', 
		'varchar(19)', 
		'int(10) unsigned', 
		'int(10) unsigned', 
		'tinyint(4)', 

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
     
    $find_sql = "SELECT ".$columns_to_select." FROM `abiturienty`";
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
})->bind('abiturienty_downloadList');



