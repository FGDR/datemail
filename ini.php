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
// Constantes de param�trage Base de donn�es
//-----------------------------------------------------------------------
define("IP", "");
define("DBTYPE", "mysqli");
define("DBHOST", "");
define("DBUSER", "");
define("DBPWD", "");
define("DBNAME", "");
define("MAXROWS", "9999");
//-----------------------------------------------------------------------
// Constantes de param�trage du site
//-----------------------------------------------------------------------

// define("BASENAME","www.clematite-travers.fr");
// define("SECUREBASEURL", "https://www.clematite-travers.fr");
// define("BASEURL", "http://www.clematite-travers.fr/newsitebeta/");
// define("URLPICTURES", "http://www.clematite-travers.fr/newsitebeta/pictures/");
// define("URLSCRIPT", "http://www.clematite-travers.fr/newsitebeta/scripts/");
// define("ADMIN_BASEURL", "http://www.clematite-travers.fr/newsitebeta/admin/");
// define("REPIMPORT", "../import/");// r�pertoire d'importation
// define("SECURECONNECTION", '0');// connexion en https

// define("USER_LANGUAGE", "FR");
// if(!isset($_SESSION['user_lang'])){$_SESSION['user_lang']=USER_LANGUAGE;}

// define("WEBMASTER","g.finociety@pourquipourquoi.fr");
// define("MAILCONTACT","contact@clematite-travers.fr");
// define('FACEBOOK','http://www.facebook.com/pages/P�pini�res-Travers/540105112673714');
// define('TWITTER','https://twitter.com/PepinieresAT');
// define('YOUTUBE','http://www.youtube.com/user/arnaudtravers');
// define("KEY","jfvslkg287549nfljkjgdhvjycflmjosdf2349067jhbvdfZEFGHN78UIOIUGC5RDezdfv");//cl� de dump et lancement de proc�dure



// //-----------------------------------------------------------------------
// // Inclusion
// //-----------------------------------------------------------------------
require_once('lib/genlib.php'); // general functions
// require_once('lib/datalib.php'); // SQL functions 
// require_once('dbconnect.php'); // Database access functions
// require_once(load_user_language());
// $db->debug = false; // mode debug sql
// //session_destroy();
// $frm = get_form_data(); // collection param�tres get post request
// global $lang; // fichier langue
// is_it_tab();
// is_it_secure();
// //Cr�ation de la session panier
//if(!isset($_SESSION['basket'])){$_SESSION['basket']=array();}
?>
