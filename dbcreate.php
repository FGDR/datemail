<?php
require_once('ini.php'); // general functions

$data = get_form_data();
// print_r($data);
if(is_null($data->etape))
{
	$string_html .= '<form action="#" method="POST">';
	$string_html .= 'Information sur le base de données : <br />';
	$string_html .= 'Adresse IP : <br />';
	$string_html .= '<input type="text" name="db_IP" /><br />';
	$string_html .= 'Adresse de la base :';
	$string_html .= '<input type="text" name="db_HOST" /><br />';
	$string_html .= 'Nom de la base :';
	$string_html .= '<input type="text" name="db_NAME" /><br />';
	$string_html .= 'Identifiant :';
	$string_html .= '<input type="text" name="db_USER" /><br />';
	$string_html .= 'Mot de passe :';
	$string_html .= '<input type="password" name="db_PWD" /><br />';
	$string_html .= '<input type="hidden" name="etape" /><br />';
	$string_html .= '<input type="submit"/>';
	$string_html .= '</form>';
}

else
{
		// $string_html .= "test :".$data['db_IP'];
		//modifier le fichier php.ini
	$config = file_get_contents('./ini.php');
		// print_r($config);
	$config=preg_replace('@"IP", "(.*)"@' , '"IP", "'.$data->db_IP.'"',$config);
	$config=preg_replace('@"DBHOST", "(.*)"@','"DBHOST", "'.$data->db_HOST.'"',$config);
	$config=preg_replace('@"DBNAME", "(.*)"@','"DBNAME", "'.$data->db_NAME.'"',$config);
	$config=preg_replace('@"DBUSER", "(.*)"@','"DBUSER", "'.$data->db_USER.'"',$config);
	$config=preg_replace('@"DBPWD", "(.*)"@','"DBPWD", "'.$data->db_PWD.'"',$config);
	$result = file_put_contents('./ini.php',$config);
	if($result)
		$string_html .= "le fichier de configuration a bien été modifié <br/>";
		//creation de differente table de la base de données
	$db = new mysqli ($data->db_HOST,$data->db_USER,$data->db_PWD,$data->db_NAME);
	if ($db->connect_error) {
		$string_html .= 'Erreur de connexion (' . $db->connect_errno . ') '
		. $db->connect_error;
		$string_html .= '<br /> <a href="dbcreate.php">Retour</a>';

	}
	else
	{
		$string_html .= "création des tables manquantes";
		if($db->query('CREATE TABLE IF NOT EXISTS `Evenement` (
			`id_Evenement` int(11) NOT NULL AUTO_INCREMENT,
			`id_Uitilisateur` int(11) NOT NULL,
			`Type` varchar(50) NOT NULL,
			`Date` varchar(50) NOT NULL,
			`Description` text DEFAULT NULL,
			`Envoi_Mail` tinyint(1) NOT NULL,
			PRIMARY KEY (`id_Evenement`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;') === true)
			$string_html .= "table evenement créé";
		else
			$string_html .= $db->error;
		$db->query("CREATE TABLE IF NOT EXISTS `Evenement_EnvoiMail` (
			`id_Evenement` int(11) NOT NULL,
			`Mail` varchar(50) NOT NULL,
			`Message` varchar(200) DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		$db->query("CREATE TABLE IF NOT EXISTS `Evenement_Type` (
			`Type` varchar(50) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		$db->query("CREATE TABLE IF NOT EXISTS `Utilisateur` (
			`id_Uitilisateur` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Nom de l''utilisateur',
			`Nom` varchar(25) NOT NULL,
			`Prenom` varchar(25) NOT NULL,
			`MDP` varchar(50) NOT NULL,
			`Adresse_mail` varchar(50) NOT NULL,
			`Delete` tinyint(4) NOT NULL,
			PRIMARY KEY (`id_Uitilisateur`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}
}

?>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
	echo $string_html;
	?>
</body>
</html>


