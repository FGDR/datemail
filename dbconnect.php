<?php
	require_once("lib/adodb5/adodb.inc.php");
	$db = &ADONewConnection(DBTYPE);
	define('ADODB_FORCE_NULLS', 1);

	if (DBTYPE == 'ibase'){
		$db->replaceQuote = "''";
		$db->false = 0;
		$db->true = 1;
		$ok = $db->Connect(DBHOST, DBUSER, DBPWD, DBNAME);
	}else{
		if (DBTYPE == 'ado_mssql'){
			$myDSN = 'PROVIDER=MSDASQL;DRIVER={SQL Server};SERVER='.DBHOST.';DATABASE='.DBNAME.';UID='.DBUSER.';PWD='.DBPWD;
			$ok = $db->Connect($myDSN);
			$db->replaceQuote = "''";
			$db->false = 0;
			$db->true = 1;
		}else{
			$ok = $db->Connect(DBHOST, DBUSER, DBPWD, DBNAME);
		}
	}
	if (!$ok) echo 'DBConnect '.DBNAME.' Error';
?>
