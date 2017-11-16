<?php

if(php_sapi_name() !== 'cli' && !defined('STDIN')) exit('only run on cli mode');

define("M_BASE_PATH",__DIR__);

use Phalcon\Di\FactoryDefault;
$migrate_di = new FactoryDefault();
$migrate_di->setShared('database_config', function () {
    return include_once  M_BASE_PATH."/app/config/database.php";

});
$config = $migrate_di->get("database_config");

$master = array(
	'hostname'=>$config->database->host,
	'username'=>$config->database->username,
	'password'=>$config->database->password,
	'database'=>$config->database->dbname,
	'dbprefix'=>isset($config->database->prefix)?$config->database->prefix:'',
);
$master_db=new mysqli($master['hostname'], $master['username'], $master['password'], $master['database']);

if(!$master_db){
	echo 'can not connect mysql'.PHP_EOL;
	exit;
}

mysqli_set_charset($master_db,'UTF8');

$result=mysqli_query($master_db,"SELECT * FROM ".$master['dbprefix']."migration");

if(!$result){

	$sql="CREATE TABLE `".$master['dbprefix']."migration` (`id` INT AUTO_INCREMENT PRIMARY KEY,filename varchar(64) NOT NULL,`version` varchar(64) NOT NULL,`run_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

	$status=mysqli_query($master_db,$sql);

	$result=mysqli_query($master_db,"SELECT * FROM ".$master['dbprefix']."migration");
}

mysqli_autocommit($master_db,FALSE);


$migrations=array();

while ($row=mysqli_fetch_assoc($result)) {
	
	$migrations[$row['version']]=$row;
}

$versions=array_keys($migrations);

echo 'MIGRATION START'.PHP_EOL;

$php_files = glob(M_BASE_PATH. '/app/migration/*.php');

foreach ($php_files as $file_path) {

	if (preg_match('/([_a-zA-Z0-9\-]*).php/', basename($file_path),$matches)) {
		
		
		$filename=$matches[1];
		$mcrypt=sha1($matches[1]);

		if(!in_array($mcrypt,$versions)){

			$trans=mysqli_query($master_db,"START TRANSACTION");

			if(!$trans){

				echo "dont support transaction".PHP_EOL;
			}

			global $sql;

			$sql=array();

			include $file_path;

			$success=true;

			foreach ($sql as $k => $v) {

				echo "EXEC SQL : ".$v.PHP_EOL;

				if(mysqli_query($master_db,$v,MYSQLI_USE_RESULT)===FALSE){

					echo "SQL ERROR：".$v.PHP_EOL;

					$success=false;

					mysqli_rollback($master_db);

					break;
				}
				else{
					echo "SQL SUCCESS：".$v.PHP_EOL;
				}
			}

			if(!$success) continue;

			if(mysqli_query($master_db,"INSERT INTO ".$master['dbprefix']."migration SET filename='".$filename."',version='".$mcrypt."'",MYSQLI_USE_RESULT)===FALSE){

				echo "update migration error".PHP_EOL;

				mysqli_rollback($master_db);

				continue;
			}

			mysqli_commit($master_db);
		}
	}
}

echo 'MIGRATION END'.PHP_EOL;

