<?php
setlocale(LC_MONETARY, 'fr_FR');
//-----------------------------------------------------------------------
// Initialisation serveur
//-----------------------------------------------------------------------
ini_set('session.cookie_lifetime', '36000');
ini_set("session.gc_maxlifetime", '36000');
ini_set('session.cache_expire', '600');
session_start();
//-----------------------------------------------------------------------
// Constantes de paramètrage Base de données
//-----------------------------------------------------------------------
define("IP", "");
define("DBTYPE", "mysqli");
define("DBHOST", "");
define("DBUSER", "");
define("DBPWD", "");
define("DBNAME", "");
define("MAXROWS", "9999");
//-----------------------------------------------------------------------
// Constantes de paramètrage du site
//-----------------------------------------------------------------------


// //-----------------------------------------------------------------------
// // Inclusion
// //-----------------------------------------------------------------------
require_once('lib/genlib.php'); // general functions
// require_once('lib/datalib.php'); // SQL functions
// require_once('dbconnect.php'); // Database access functions
// require_once(load_user_language());
// $db->debug = false; // mode debug sql
// //session_destroy();
// $frm = get_form_data(); // collection paramètres get post request
// global $lang; // fichier langue
// is_it_tab();
// is_it_secure();
// //Création de la session panier
//if(!isset($_SESSION['basket'])){$_SESSION['basket']=array();}
?>
