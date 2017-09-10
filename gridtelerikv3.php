<?php
	//PDO REMOTO EXITOSO
	
	$servername = "190.3.171.23";
	$username = "barlosp_btest";
	$password = "btest2016.,";

	try {
		//$conn = new PDO("mysql:host=$servername;dbname=mysqltest", $username, $password);
		$conn = new PDO("mysql:host=$servername;dbname=barlosp_barlotest", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "PDO Connected successfully"; 
		}
	catch(PDOException $e)
		{
		echo "PDO Connection failed: " . $e->getMessage();
		}
	
		
	$sql="SELECT CustomerID, ContactName, ContactTitle, CompanyName, Country FROM Customers";
	//$sql="SELECT id_usuario FROM usuarios_adm";
	echo $sql;
	$resultado = $conn->query($sql); 
	$num_rows = $resultado->fetchColumn();
	if ($num_rows==0){
		echo 'Sin datos';
	}
	else{
		echo 'se encontraron ' .$num_rows ."Filas";
	}
	

			

	foreach ($resultado as $row) {
	   echo $rows['CustomerID'] . "\n";
	}		
	exit;
	
	
	//Local exitoso
	//$link = mysql_pconnect("localhost","root","root")or die("No puede conectarse a la Base de Datos");
	//mysql_select_db("mysqltest")or die("No puede conectarse a pruebasphp");
	
	//Remoto exitoso
	/*
	$link = mysql_pconnect("190.3.171.23","barlosp_btest","btest2016.,")or die("No puede conectarse a la Base de Datos");
	mysql_select_db("barlosp_barlotest")or die("No puede conectarse a pruebasphp");
	
	if(!$link)
	{
		echo 'error';
	}else{
		echo 'exitoso';
	}	
	$rs = mysql_query("SELECT CustomerID, ContactName, ContactTitle, CompanyName, Country FROM Customers");
	while($obj = mysql_fetch_object($rs)){
	$arr[] = $obj;
	}
	header("Content-type: application/json"); 

	echo "{\"data\":" .json_encode($arr). "}";	
	exit;
	*/
	
require_once '../webpanel/lib/datasourceresult.php';
require_once '../webpanel/lib/kendo/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');

    $request = json_decode(file_get_contents('php://input'));
	//sqlitr correcto
    //$result = new DataSourceResult('sqlite:..//sample.db');	
	//Remoto Error
	//$result = new DataSourceResult('mysql:host=190.3.171.23','barlosp_btest','btest2016.,');
	//Local exitoso
	//$result = new DataSourceResult('mysql:host=localhost;dbname=mysqltest','root','root');
	//Remoto 
	$result = new DataSourceResult('mysql:host=190.3.171.23;dbname=barlosp_barlotest','barlosp_btest','btest2016.,');
								  								  
    echo json_encode($result->read('Customers', array('CustomerID', 'ContactName', 'ContactTitle', 'CompanyName', 'Country'), $request));
	  
}

require_once '../webpanel/include/header.php';

$transport = new \Kendo\Data\DataSourceTransport();

$read = new \Kendo\Data\DataSourceTransportRead();

$read->url('index.php')
     ->contentType('application/json')
     ->type('POST');

$transport ->read($read)
          ->parameterMap('function(data) {
              return kendo.stringify(data);
          }');

$model = new \Kendo\Data\DataSourceSchemaModel();

$contactNameField = new \Kendo\Data\DataSourceSchemaModelField('ContactName');
$contactNameField->type('string');

$contactTitleField = new \Kendo\Data\DataSourceSchemaModelField('ContactTitle');
$contactTitleField->type('string');

$companyNameField = new \Kendo\Data\DataSourceSchemaModelField('CompanyName');
$companyNameField->type('string');

$countryField = new \Kendo\Data\DataSourceSchemaModelField('Country');
$countryField->type('string');

$model->addField($contactNameField)
      ->addField($contactTitleField)
      ->addField($companyNameField)
      ->addField($countryField);

$schema = new \Kendo\Data\DataSourceSchema();
$schema->data('data')
       ->errors('errors')
       ->groups('groups')
       ->model($model)
       ->total('total');

$dataSource = new \Kendo\Data\DataSource();

$dataSource->transport($transport)
           ->pageSize(10)
           ->serverPaging(true)
           ->serverSorting(true)
           ->serverGrouping(true)
           ->schema($schema);

$grid = new \Kendo\UI\Grid('grid');

$contactName = new \Kendo\UI\GridColumn();
$contactName->field('ContactName')
            ->template("<div class='customer-photo'style='background-image: url(../content/web/Customers/#:data.CustomerID#.jpg);'></div><div class='customer-name'>#: ContactName #</div>")
            ->title('Contact Name')
            ->width(240);

$contactTitle = new \Kendo\UI\GridColumn();
$contactTitle->field('ContactTitle')
            ->title('Contact Title');

$companyName = new \Kendo\UI\GridColumn();
$companyName->field('CompanyName')
            ->title('Company Name');

$Country = new \Kendo\UI\GridColumn();
$Country->field('Country')
        ->width(150);

$pageable = new Kendo\UI\GridPageable();
$pageable->refresh(true)
      ->pageSizes(true)
      ->buttonCount(5);

$grid->addColumn($contactName, $contactTitle, $companyName, $Country)
     ->dataSource($dataSource)
     ->sortable(true)
     ->groupable(true)
     ->pageable($pageable)
     ->attr('style', 'height:550px');

echo $grid->render();
?>

<style type="text/css">
    .customer-photo {
        display: inline-block;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-size: 32px 35px;
        background-position: center center;
        vertical-align: middle;
        line-height: 32px;
        box-shadow: inset 0 0 1px #999, inset 0 0 10px rgba(0,0,0,.2);
        margin-left: 5px;
    }

    .customer-name {
        display: inline-block;
        vertical-align: middle;
        line-height: 32px;
        padding-left: 3px;
    }
</style>
