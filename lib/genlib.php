<?php 

//*********************************************************************************************
//transfert de base ftp
//*********************************************************************************************
function transfertfile($rep,$file,$ftp_server,$ftp_user_name,$ftp_user_pass)
{
$conn_id = ftp_connect("$ftp_server");
// authentification avec nom de compte et mot de passe
$login_result = ftp_login($conn_id, "$ftp_user_name", "$ftp_user_pass");
// vérification de la connexion
$connect=0;

if ((!$conn_id) || (!$login_result)) {
	$log="LA CONNEXION FTP A ECHOUEE";
	//echo "Tentative de connexion";
	sleep(3);
	} else {
	$log="Connection<br />";
	$connect==1;
	}

$log.="téléchargement du fichier<br />";
$upload = ftp_put($conn_id,$file, $rep.$file, FTP_BINARY);
$log.="Vérification du téléchargement OK";
if (!$upload) {
$log.="Le téléchargement Ftp a échoué!";
} else {
$log.="Téléchargement de $file sur $ftp_server en $destination_file OK<br />";
}
	$log.="fermeture de la connexion FTP.";
	ftp_quit($conn_id); 
	return $log;
}
//*********************************************************************************************
//lecture, copie, suppression de base d'un répertoire
//*********************************************************************************************
function copy_dir($dir2copy,$dir_paste) 
{    
	if (is_dir($dir2copy)) 
	{   
           if ($dh = opendir($dir2copy)) 
	   {                     
             while(($file = readdir($dh)) !== false) 
	     {                          
                if (!is_dir($dir_paste)) mkdir($dir_paste,777);                           
		if(is_dir($dir2copy.$file) && $file != '..'  && $file != '.') 
                copy_dir ( $dir2copy.$file.'/' , $dir_paste.$file.'/');                                     
		                elseif($file != '..'  && $file != '.') copy ( $dir2copy.$file , $dir_paste.$file );
		            }
					closedir($dh);
		        }               
		    }    
		}


function clearDir($dir2copy) {
	$ouverture=@opendir($dir2copy);
	if (!$ouverture) return;
	while($fichier=readdir($ouverture)) {
		if ($fichier == '.' || $fichier == '..') continue;
			if (is_dir($dir2copy."/".$fichier)) {
				$r=clearDir($dir2copy."/".$fichier);
				if (!$r) return false;
			}
			else {
				$r=@unlink($dir2copy."/".$fichier);
				if (!$r) return false;
			}
	}
closedir($ouverture);
$r=@rmdir($dir2copy);
@rename($dir2copy,"trash");
return true;
}
function gl_read($path,$order,$dir)
{
	
	//echo $path." - ".$Aquila."<br />";
	$MyDirectory = opendir($path) or die('Erreur');
	while($Entry = @readdir($MyDirectory)) {
		
		
		//echo $Entry.'__';
		if($Entry != '.' && $Entry != '..') {
             $i=count($pdflist);
			// echo $path.'/'.$Entry."<br />";
			 $pdflist[$i][0]=$Entry;
			 $pdflist[$i][1]=strftime("%d/%m/%Y %H:%M:%S",filemtime($path.'/'.$Entry)); 
			 //echo $Entry.'__Cest OK!!!!!!!!!!!!!!!!!<br />';
		}else{
			//echo $Entry.'__<br />';
		}
	}
	
  @closedir($path);
  if(count($pdflist)>0)sort($pdflist);
  //Print_r($picturelist);
  return $pdflist;
}    
//*********************************************************************************************
//age en fonction de la date de naissance
//*********************************************************************************************
function age($naiss)  {
  list($annee, $mois, $jour) = split('[-.]', $naiss);
  $today['mois'] = date('n');
  $today['jour'] = date('j');
  $today['annee'] = date('Y');
  $annees = $today['annee'] - $annee;
  if ($today['mois'] <= $mois) {
    if ($mois == $today['mois']) {
      if ($jour > $today['jour'])
        $annees--;
      }
    else
      $annees--;
    }
 return $annees;
  }
//*********************************************************************************************
//duree entre 2 dates
//*********************************************************************************************
function NbJour($debut,$fin) {
	/*on "découpe" les dates de façon à obtenir un tableau de 3 lignes : 0=>jours, 1=>mois, 2=>années*/
	$debut=explode("/",$debut);
	$fin=explode("/",$fin);
	/*A partir de ce tableau, on reconstitue le timestamp grâce à la fonction mktime*/
	$debut=mktime(0,0,0,$debut[1],$debut[0],$debut[2]);
	$fin=mktime(0,0,0,$fin[1],$fin[0],$fin[2]);
	/*On soustrait les deux dates et on obtient le nombre de secondes écoulé*/
	$d=$fin-$debut;
	/*Il ne reste plus qu'à calculer le nombre d'années, mois et jours écoulé*/
	return array("ans"=>date('Y',$d)-1970,"mois"=>date('m',$d)-1,"jours"=>date('d',$d)-1);
}
//*********************************************************************************************
//envoi de mail
//*********************************************************************************************
function sendmailcontact($entreprise,$name,$surname,$mail,$tel,$message){
	$to = MAILCONTACT;
	$sujet = "Clematite-travers.fr: nouveau message"; 
	//--- la structure du mail ----//  
	
	$headers ='From:contact@clematite-travers.fr/'."\n";
	$headers .='Reply-To:contact@clematite-travers.fr'."\n";
	$headers .='Content-Type: text/html; charset="UTF-8"'."\n";
	$headers .='Content-Transfer-Encoding: 8bit'; 
	$from  = $mail; 
	$from .= "MIME-version: 1.0\n"; 
	$from .= "Content-type: text/html; charset= iso-8859-1\n"; 
	
	$partheader="<body style='margin: 0px auto 0px auto;'><img src='".URLPICTURES."travers-bandeau-mail.png' />
	
	<p style='width:640px;text-align:justify;margin-left:20px;margin-right:20px;font-size:12px;width:640px;'>Nom : ".stripslashes($surname)." ".stripslashes($name)."<br />Courriel : ".$mail."<br />Téléphone : ".$tel."<br /><br />
	<br />
	
	";
	$partmessage1=nl2br(stripslashes($message));
	$partmessage2='';
	$partmessage3='';
	$partfooter='</p><p style="margin-bottom:20px;font-size:10px;text-align:center;width:640px;">mentions légales | © 2013 Domaine de Bellevue - Chemin rural des Montées - RD 126 45590 Saint Cyr en Val</p></body>';
	$message=$partheader.$partmessage1.$partmessage2.$partmessage3.$partfooter;
	mail($to,$sujet,$message,$headers); 
	$sujet = "Copie de votre message envoyé depuis clematite-travers.fr"; 
	mail($mail,$sujet,$message,$headers); 
	
	}

function gl_mail_passforgot($mail,$mdp)
{
$to = $mail;
	
$sujet = "LEPAGE-VIVACE : Mot de passe oublié."; 

//--- la structure du mail ----//  

$headers ='From:serviceclient@lepage-vivaces.com'."\n";
//$headers .='Reply-To: adresse_de_reponse@fai.fr'."\n";
$headers .='Content-Type: text/html; charset="UTF-8"'."\n";
$headers .='Content-Transfer-Encoding: 8bit'; 
 
$from  = "serviceclient@lepage-vivaces.com"; 
$from .= "MIME-version: 1.0\n"; 
$from .= "Content-type: text/html; charset= iso-8859-1\n"; 

$partheader="<img src='http://www.lepage-vivaces.com/newsitebeta/pictures/FondImpressionSite140.jpg' />
<p style='margin-bottom:20px;font-size:10px;text-align:center;width:640px;'>Pépinière Lepage - Rue des Perrins, 49130 Les Ponts-de-Cé - Tél. : 02 41 44 93 55</p>
<p style='width:640px;text-align:justify'>Voici votre mot de passe Lepage :".$mdp." (Respectez la casse caractère)</p>";

 
$partmessage1='';
$partmessage2='';
$partmessage3='';
$message=$partheader.$partmessage1.$partmessage2.$partmessage3;
mail($to,$sujet,$message,$headers); 

}

//*********************************************************************************************
//test connection https
//*********************************************************************************************
/*function is_it_secure(){
	if(SECURECONNECTION==1){
		if($_SERVER["SERVER_PORT"] == 80){
			header('location:'.BASEURL);	
		}
	}
}*/
//*********************************************************************************************
//test tablette
//*********************************************************************************************
/*function is_it_tab(){
if (
 strripos($_SERVER['HTTP_USER_AGENT'], 'Tablet') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'Sony') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'Nokia') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'Blackberry') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'Pocket') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'Windows CE') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false
 ){$_SESSION['istablet']=true;}else{$_SESSION['istablet']=false;}
}*/

//*********************************************************************************************
//header commun
//*********************************************************************************************
function header_meta($title,$description, $listkeywords)
{
	$concat=concat($listkeywords,'NUM');

echo '<!DOCTYPE HTML>
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>'.$title.'</title>
<meta name="description" content="'.$description.'">
<meta name="keywords" content="'.$concat.'">
<meta name="copyright" content="INTRANET / www.Pourquipourquoi?">
<meta name="author" content="www.Pourquipourquoi.fr">
<meta name="robots" content="index, follow">
<meta name="google-site-verification" content="" />
<link rel="stylesheet" href="'.BASEURL.'TRAV_stylebase.css" type="text/css" media="all" />
<link rel="stylesheet" href="'.URLSCRIPT.'jquery-ui-1.8.21.custom/css/south-street/jquery-ui-1.8.21.custom.css" type="text/css" media="all" />
<link rel="stylesheet" href="'.URLSCRIPT.'farbtastic/farbtastic.css" type="text/css" />
<link rel="stylesheet" href="'.BASEURL.'prettyPhoto.css" type="text/css" media="all" />
<script type="text/javascript" src="'.URLSCRIPT.'jquery-ui-1.8.21.custom/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="'.URLSCRIPT.'jquery-ui-1.8.21.custom/js/jquery-ui-1.8.21.custom.min.js"></script>
<!--<script type="text/javascript" src="'.URLSCRIPT.'jquery.ui.timepicker.js"></script>-->
<!--<script type="text/javascript" src="'.URLSCRIPT.'coin-slider.js"></script>-->
<!--<script type="text/javascript" src="'.URLSCRIPT.'jquery.ui.widget.min.js"></script>-->
<!--<script type="text/javascript" src="'.URLSCRIPT.'jquery.cycle.all.js"></script>-->
<!--<script type="text/javascript" src="'.URLSCRIPT.'farbtastic/farbtastic.js"></script>-->
<script type="text/javascript" src="'.URLSCRIPT.'jquery.jcontent.0.8.min.js"></script>
<script type="text/javascript" src="scripts/slide-fade-content.js"></script>
<script type="text/javascript" src="'.URLSCRIPT.'jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="'.URLSCRIPT.'init.js"></script>
<script>
   
   
    var i=false; // La variable i nous dit si la bulle est visible ou non
    var j=true; // La variable i nous dit si la bulle est visible ou non 
    function move(e) {
      if(i) {  // Si la bulle est visible, on calcul en temps reel sa position ideale
        if (navigator.appName!="Microsoft Internet Explorer") { // Si on est pas sous IE
        GetId("curseur").style.left=e.pageX + 5+"px";
        GetId("curseur").style.top=e.pageY + 10+"px";
        }
        else { // Modif proposé par TeDeum, merci à  lui
        if(document.documentElement.clientWidth>0) {
    GetId("curseur").style.left=20+event.x+document.documentElement.scrollLeft+"px";
    GetId("curseur").style.top=40+event.y+document.documentElement.scrollTop+"px";
        } else {
    GetId("curseur").style.left=20+event.x+document.body.scrollLeft+"px";
    GetId("curseur").style.top=40+event.y+document.body.scrollTop+"px";
	     }
        }
      }
	    
		if(j) {  // Si la bulle est visible, on calcul en temps reel sa position ideale
		if (navigator.appName!="Microsoft Internet Explorer") { // Si on est pas sous IE
        GetId("curseur").style.left=-10+e.pageX + 5+"px";
        GetId("curseur").style.top=10+e.pageY + 10+"px";
        }
        else { // Modif proposé par TeDeum, merci à  lui
        if(document.documentElement.clientWidth>0) {
    GetId("curseur").style.left=-40+event.x+document.documentElement.scrollLeft+"px";
    GetId("curseur").style.top=40+event.y+document.documentElement.scrollTop+"px";
        } else {
    GetId("curseur").style.left=-40+event.x+document.body.scrollLeft+"px";
    GetId("curseur").style.top=40+event.y+document.body.scrollTop+"px";
	     }
        }
		}
	  
	  
    }
    function GetId(id)
    {
    return document.getElementById(id);
    }
    function montre(text) {
      if(i==false) {
      GetId("curseur").style.visibility="visible"; 
      GetId("curseur").innerHTML = text; 
      i=true;
      }
    }
    function cache() {
    if(i==true) {
    GetId("curseur").style.visibility="hidden"; // Si la bulle est visible on la cache
    i=false;
    }
    }
    document.onmousemove=move; // dès que la souris bouge, on appelle la fonction move pour mettre à jour la position de la bulle.
 
</script>
</head>	';
	
	
}
//*********************************************************************************************
//header
//*********************************************************************************************
function header_body($title)
{
echo'<body>
<div id="blockpage" style="display:none;" onclick="j=true;$(\'#blockpage\').hide(\'blind\',{},400);GetId(\'modif\').style.visibility=\'hidden\';"></div>
<div id="curseur" class="infobulle" ></div>';
giveyourmail();
mention();
echo '<div id="container">

<div id="image-container"><h3 id="title">'.$title.'</h3></div>';

}
//*********************************************************************************************
//footer
//*********************************************************************************************
function footer()
{
	echo '</div>


</body>
</html>';
	
	
}


//*********************************************************************************************
//code de base de la vignette contactez nous
//*********************************************************************************************
function giveyourmail(){
	
echo '<div id="giveyourmail" style="display: none;">
<div id="gymhead">Contactez-nous<br><span style="font-size:16px;line-height:80%">02.38.66.13.70 (parti.)<br />02.38.66.14.90 (profe.)</span><br><span style="font-size:14px;text-align:center;line-height:80%">ou laissez-nous votre message</span></div>

<table id="formpdf" style="">
<tbody><tr><td style="cursor:pointer;" onmouseover="montre(\'le nom est obligatoire\');" onmouseout="cache();">Nom*</td><td>&nbsp;</td><td><input type="text" class="it1" id="name" name="name" value="" onkeyup="format();" onblur="control();"></td><td><img style="display:none;" id="alert1" src="http://www.mar.pourquipourquoi.fr/pictures/Alert.png" onmouseover="montre(\'Le nom ne doit pas contenir de chiffre\');" onmouseout="cache();"></td></tr>
<tr><td style="cursor:pointer;" onmouseover="montre(\'le Prénom est obligatoire\');" onmouseout="cache();">Prénom*</td><td>&nbsp;</td><td><input type="text" class="it1" id="surname" name="surname" onkeyup="format();" onblur="control();" value=""></td><td><img style="display:none;" id="alert2" src="http://www.mar.pourquipourquoi.fr/pictures/Alert.png" onmouseover="montre(\'Le prénom ne doit pas contenir de chiffre\');" onmouseout="cache();"></td></tr>
<tr><td style="cursor:pointer;" onmouseover="montre(\'le Courriel est obligatoire\');" onmouseout="cache();">Courriel*</td><td>&nbsp;</td><td><input type="text" class="it1" id="mail" name="mail" onkeyup="format();" onmousemove="control();" onblur="control();" value=""></td><td><img style="display:none;" id="alert3" src="http://www.mar.pourquipourquoi.fr/pictures/Alert.png" onmouseover="montre(\'mail non conforme\');" onmouseout="cache();"></td></tr>
<tr><td>Téléphone</td><td>&nbsp;</td><td><input type="text" class="it1" id="tel" name="tel" value="" onkeyup="format();" onmousemove="control();" onblur="format();control();"></td><td><img style="display:none;" id="alert4" src="http://www.mar.pourquipourquoi.fr/pictures/Alert.png" onmouseover="montre(\'Téléphone non conforme\');" onmouseout="cache();"></td></tr>
<tr><td colspan="3"><textarea id="message" style="width:412px;height:100px;" name="message" onkeyup="format();" onmousemove="control();" onblur="format();control();"></textarea></td><td><img style="display:none;" id="alert4" src="http://www.mar.pourquipourquoi.fr/pictures/Alert.png" onmouseover="montre(\'Téléphone non conforme\');" onmouseout="cache();"></td></tr>
<tr><td colspan="3"><input type="checkbox"  id="chnews" name="chnews" checked="checked" value="1" />Je m\'inscris à la newsletter</td></tr>
</tbody></table>

<div id="btndownloaddisabled" style="display: block;"><div id="btndd">J\'envoie mon message</div></div>
<div id="btndownload" style="display:none;"><a href="#" onclick="savecontact();"><div id="btnd">J\'envoie mon message</div></a></div>
</div>';
	
}

//*********************************************************************************************
//code de base de la div mention
//*********************************************************************************************
function mention(){
	echo'<div id="divmention">
      <div id="dmblocktext">
      <div class="dmbtitle">Mentions légales</div>
      <div class="dmbtleft">
          <div class="dmbttitle"><div class="dmbtpt"></div><div class="dmbttit">Propriété et responsabilité éditoriale</div></div>
          <div class="dmbttext">Le présent site est la propriété des pépinières Travers située Domaine de Bellevue - Chemin rural des Montées - RD 126 45590 Saint Cyr en Val. Le Responsable de la publication du présent site est M. Arnaud Travers en sa qualité de directeur.</div>
          <div class="dmbttitle"><div class="dmbtpt"></div><div class="dmbttit">Site réalisé par </div></div>
          <div class="dmbttext">Pour Qui Pourquoi ?<br />42 rue des Lices - 49100 ANGERS<br />
                                                        Tél. : 02 41 20 16 10<br />
                                                        <a href="http://www.pourquipourquoi.fr">www.pourquipourquoi.fr</a></div>
          <div class="dmbttitle"><div class="dmbtpt"></div><div class="dmbttit">Crédit Photos</div></div>
          <div class="dmbttext">pépinières Travers située Domaine de Bellevue.</div>
          <div class="dmbttitle"><div class="dmbtpt"></div><div class="dmbttit">Hébergement</div></div>
          <div class="dmbttext">Le prestataire assurant l’hébergement du site est la société ovh - 140, quai du sartel - 59100 Roubaix - France - SAS au capital de 500 000 € - RCS Roubaix-Tourcoing 424 761 419 00011 - Code APE 712Z.</div>
          <div class="dmbttitle"><div class="dmbtpt"></div><div class="dmbttit">Déclaration du site à la CNIL</div></div>
          <div class="dmbttext">Conformément aux dispositions de la loi n°78-17 du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, le traitement automatisé des données nominatives réalisé à partir de ce site Internet a fait l’objet d’une déclaration auprès de la Commission nationale de l’informatique et des libertés (CNIL) sous le numéro en cours. </div>
           
          
      </div>
      <div class="dmbtleft" margin-left:00px;>
      
      <div class="dmbttitle"><div class="dmbtpt"></div><div class="dmbttit">Données nominatives</div></div>
          <div class="dmbttext">En application de la Loi n° 78-17 du 6 janvier 1978 relative à l’Informatique, aux Fichiers et aux Libertés, vous disposez des droits d’opposition (art. 26 de la loi), d’accès (art.34 à 38 de la loi) et de rectification (art. 36 de la loi) des données vous concernant. Ainsi, vous pouvez nous contacter pour que soient rectifiées, complétées, mises à jour ou effacées les informations vous concernant qui sont inexactes, incomplètes, équivoques, périmées ou dont la collecte ou l’utilisation, la communication ou la conservation est interdite.
Les informations qui vous concernent sont uniquement destinées à la société Pépinières Travers. Nous ne transmettons ces informations à aucuns tiers (partenaires commerciaux,etc.c).</div>
           <div class="dmbttitle"><div class="dmbtpt"></div><div class="dmbttit">Propriété intellectuelle</div></div>
          <div class="dmbttext">Le site Internet, sa structure générale, ainsi que les textes, images animées ou non, savoir-faire, dessins, graphismes (…) et tout autre élément composant le site, sont la propriété dee Pépinières Travers. Toute représentation totale ou partielle de ce site par quelque procédé que ce soit, sans l’autorisation expresse de l’exploitant du site Internet est interdite et constituerait une contrefaçon sanctionnée par les articles L 335-2 et suivants du Code de la propriété intellectuelle. Les marques de l’exploitant du site Internet et de ses partenaires, ainsi que les logos figurant sur le site sont des marques (semi-figuratives ou non) déposées. Toute reproduction totale ou partielle de ces marques ou de ces logos, effectuée à partir des éléments du site sans l’autorisation expresse de l’exploitant du site Internet ou de son ayant-droit est donc prohibée, au sens de l’article L713-2 du CPI.</div>
          
      
      </div>
      
      </div>
  </div>  ';
	
	
	
}
//*********************************************************************************************
//enlève les caractères ASCII à un texte
//*********************************************************************************************
/*function unhtmlentities($string)
{
	// Remplace les entités numériques
	$string = preg_replace('~([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
	$string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
	// Remplace les entités litérales
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}
*/
//*********************************************************************************************
//lecture d'un répertoire d'image renvoi la liste des images du répertoire
//*********************************************************************************************
/*function readphotos($path,$number)
{
	$num=0;
	//$path='./Photo_plantes';
	echo $path."<br />";
	$MyDirectory = opendir($path) or die('Erreur');
	while($Entry = readdir($MyDirectory)) {
		//echo $Entry.'<br />';
		if($Entry != '.' && $Entry != '..' ) {
             $picturelist[]=$Entry;
			 //echo $Entry.'__Cest OK!!!!!!!!!!!!!!!!!<br />';
		}
		$num++;
	}
	
  @closedir($path);
  $ct=count($picturelist);
 
  for($i=0;$i<$number;$i++)
  {
	 $hasard = rand(0, $ct-1) ; 
	 $list[] =$picturelist[$hasard];
	  
   }
 // $picturelist=shuffle($picturelist);
  return $list;
}   */
//*********************************************************************************************
//lecture info iptc image jpg
//*********************************************************************************************
function info_JPEG_picture($img)
{
//GetImageSize qui prend comme paramètre optionnel $info.
// dans $info seront stockés le marker APP13 défini par Adobe Photoshop et l'IPTC pour un fichier JPEG, mais aussi d'autres markers. 
GetImageSize ($img,$tabinf);
//Récupérons donc le marker qui contient IPTC: APP13.

$iptc = iptcparse ($tabinf["APP13"]);
//iptcparse retourne un tableau associatif avec les "codes" associés aux champs IPTC comme index
//Array ( [2#000] => Array ( [0] => * ) [2#120] => Array ( [0] => Commentaire ) [2#025] => Array ( [0] => Mots-clé ) [2#005] => Array ( [0] => Titre ) [2#080] => Array ( [0] => Auteur ) [2#116] => Array ( [0] => Libre) ) 
$info['CHAR_P_IPTC_IMAGETYPE']=$iptc['2#130'][0];//130 	Image Type 	non répétable, 2 caractères 	Type de l'image (cf. le document IPTC-NAA IIMV4)
$info['CHAR_P_IPTC_WRITER']=$iptc['2#122'][0];//122 	Writer/Editor 	répétable, 32 caractères maximum 	Auteur de la Description (du champ 120)
$info['CHAR_P_IPTC_CAPTION']=$iptc['2#120'][0];//120 	Caption/Abstract 	non répétable, 2000 caractères maximum 	Description, Résumé, Commentaire
$info['CHAR_P_IPTC_CONTACT']=$iptc['2#118'][0];//118 	Contact 	répétable, 128 caractères maximum 	Contact
$info['CHAR_P_IPTC_COPYRIGHT']=$iptc['2#116'][0];//116 	Copyright Notice 	non répétable, 128 caractères maximum 	Copyright
$info['CHAR_P_IPTC_SOURCE']=$iptc['2#115'][0];//115 	Source 	non répétable, 32 caractères maximum 	Source (propriétaire intellectuel de l'objet)
$info['CHAR_P_IPTC_CREDIT']=$iptc['2#110'][0];//110 	Credit 	non répétable, 32 caractères maximum 	Crédit (fournisseur de l'objet)
$info['CHAR_P_IPTC_HEADLINE']=$iptc['2#105'][0];//105 	Headline 	non répétable, 256 caractères maximum 	Titre
$info['CHAR_P_IPTC_ORIGTRANSREF']=$iptc['2#103'][0];//103 	Original Transmission Reference 	non répétable, 32 caractères maximum 	Référence de la transmission (code)
$info['CHAR_P_IPTC_COUNTRYNAME']=$iptc['2#101'][0];//101 	Country/Primary Location Name 	non répétable, 64 caractères maximum 	Libellé du pays
$info['CHAR_P_IPTC_COUNTRYCODE']=$iptc['2#100'][0];//100 	Country/Primary Location Code 	non répétable, 3 caractères 	Code du pays, suit la norme ISO3166 (codes pays sur 3 caractères)
$info['CHAR_P_IPTC_STATE']=$iptc['2#095'][0];//95 	Province/State 	non répétable, 32 caractères maximum 	Province/État
$info['CHAR_P_IPTC_PLACE']=$iptc['2#092'][0];// non répétable, 32 caractères maximum 	Région
$info['CHAR_P_IPTC_CITY']=$iptc['2#090'][0];//90 	City 	non répétable, 32 caractères maximum 	Ville
$info['CHAR_P_IPTC_BYLINETITLE']=$iptc['2#085'][0];//85 	By-line Title 	répétable, 32 caractères maximum 	Titre du créateur ou des créateurs. Ex. "Staff Photographer", "Envoyé spécial"
$info['CHAR_P_IPTC_BYLINE']=$iptc['2#080'][0];//80 	By-line 	répétable, 32 caractères maximum 	Créateur de l'objet (auteur): nom du rédacteur, du photographe, etc.
$info['CHAR_P_IPTC_OBJECTCYCLE']=$iptc['2#075'][0];//75 	Object cycle 	non répétable, un seul caractère 	Cycle de l'objet 'a' = le matin, 'b' = l'après-midi, 'c' = matin et après-midi
$info['CHAR_P_IPTC_PROGRAM']=$iptc['2#065'][0];//65 	Originating Program 	non répétable, 32 caractères maximum 	Programme ayant créé l'objet
$info['CHAR_P_IPTC_VERSION']=$iptc['2#070'][0];//70 	Program version 	non répétable, 10 caractères maximum 	Version du programme ayant créé l'objet
$info['CHAR_P_IPTC_TIMECREATED']=$iptc['2#060'][0];//60 	Time Created 	non répétable, 11 caractères, forme HHMMSS±HHMM 	Heure de création de l'objet, suit la norme ISO8601.
$info['CHAR_P_IPTC_DATECREATED']=$iptc['2#055'][0];//55 	Date Created 	non répétable, 8 caractères, forme AAAAMMJJ 	Date de création de l'objet
$info['CHAR_P_IPTC_SPECIAL']=$iptc['2#040'][0];//40 	Special Instructions 	non répétable, 256 caractères maximum 	Instructions spéciales
$info['CHAR_P_IPTC_RELEASETIME']=$iptc['2#035'][0];//35 	Release Time 	non répétable, 11 caractères, forme HHMMSS±HHMM 	Heure de disponibilité, suit la norme ISO8601.Ex. 090000-0500 = disponible à 9h00, temps de New York (5 heures avant TU)
$info['CHAR_P_IPTC_RELEASEDATE']=$iptc['2#030'][0];//30 	Release Date 	non répétable, 8 caractères, forme AAAAMMJJ 	Date de disponibilité
$info['CHAR_P_IPTC_KEYWORDS']=$iptc['2#025'][0];//25 	Keywords 	répétable, 64 caractères maximum 	Mots-clés
$info['CHAR_P_IPTC_FIXTUREIDENT']=$iptc['2#022'][0];//22 	Fixture Identifier 	non répétable, 32 caractères maximum 	Identificateur
$info['CHAR_P_IPTC_URGENCY']=$iptc['2#010'][0];//10 	Urgency 	non répétable, un seul caractère 	Priorité valeurs de 0 à 8 :0 aucun, 1 ']= haut, 8 ']= faible 	1
$info['CHAR_P_IPTC_EDITSTATUS']=$iptc['2#007'][0];//7 	Edit Status 	non répétable, 64 caractères maximum 	Statut éditorial
$info['CHAR_P_IPTC_OBJECTNAME']=$iptc['2#005'][0];//5 	Object Name 	non répétable, 64 caractères maximum 	Nom de l'objet
$info['CHAR_P_IPTC_REFSERVICE']=$iptc['2#045'][0];//45 	Reference service 	répétable, 10 caractères maximum. Optionnel 	Service de référence (doit être suivi des champs 47 et 50)
$info['CHAR_P_IPTC_REFDATE']=$iptc['2#047'][0];//47 	Reference Date 	obligatoire si le champ 45 est présent, 8 caractères, forme AAAAMMJJ 	Date de référence
$info['CHAR_P_IPTC_REFNUMBER']=$iptc['2#050'][0];//50 	Reference Number 	obligatoire si le champ 45 est présent, 10 caractères maximum 	Numéro de référence

$fileinf = exif_read_data($img);
//Array ( [FileName] => essai3.jpeg [FileDateTime] => 1353493319 [FileSize] => 149978 [FileType] => 2 [MimeType] => image/jpeg [SectionsFound] => [COMPUTED] => Array ( [html] => width="617" height="480" [Height] => 480 [Width] => 617 [IsColor] => 1 ) ) 


$info['CHAR_P_FILE_FILENAME']=$fileinf['FileName'];
$info['CHAR_P_FILE_FILESIZE']=$fileinf['FileSize'];
$info['CHAR_P_FILE_FILEDTIME']=$fileinf['FileDateTime'];
$info['CHAR_P_COMPUTED_HTML']=$fileinf['SectionsFound']['COMPUTED']['html'];
$info['CHAR_P_COMPUTED_HEIGHT']=$fileinf['SectionsFound']['COMPUTED']['Height'];
$info['CHAR_P_COMPUTED_WIDTH']=$fileinf['SectionsFound']['COMPUTED']['Width'];
$info['CHAR_P_COMPUTED_ISCOLOR']=$fileinf['SectionsFound']['COMPUTED']['IsColor'];
$info['CHAR_P_COMPUTED_BORDERMOTOROLA']=$fileinf['SectionsFound']['COMPUTED']['ByteOrderMotorola'];
$info['CHAR_P_COMPUTED_USERCOMMENT']=$fileinf['SectionsFound']['COMPUTED']['UserComment'];
$info['CHAR_P_COMPUTED_USERCOMMENTENC']=$fileinf['SectionsFound']['COMPUTED']['UserCommentEncoding'];
$info['CHAR_P_COMPUTED_COPYRIGHT']=$fileinf['SectionsFound']['COMPUTED']['Copyright'];
$info['CHAR_P_COMPUTED_CRPHOTOGRAPHER']=$fileinf['SectionsFound']['COMPUTED']['Copyright']['Photographer'];
$info['CHAR_P_COMPUTED_CREDITOR']=$fileinf['SectionsFound']['COMPUTED']['Copyright']['Editor'];
$info['CHAR_P_IFD0_COPYRIGHT']=$fileinf['SectionsFound']['IFD0']['Copyright'];
$info['CHAR_P_IFD0_USERCOMMENT']=$fileinf['SectionsFound']['IFD0']['UserComment'];
$info['CHAR_P_THUMBNAIL_JPEGINTERCHANGEFORMAT']=$fileinf['SectionsFound']['THUMBNAIL']['JPEGInterchangeFormat'];
$info['CHAR_P_THUMBNAIL_JPEGINTERCHANGEFORMATLENGHT']=$fileinf['SectionsFound']['THUMBNAIL']['JPEGInterchangeFormatLength'];
$info['CHAR_P_THUMBNAIL_HEIGHT']=$fileinf['SectionsFound']['THUMBNAIL']['Height'];
$info['CHAR_P_THUMBNAIL_WIDTH']=$fileinf['SectionsFound']['THUMBNAIL']['Width'];
$info['CHAR_P_COMMENT_A']=$fileinf['SectionsFound']['COMMENT'][0];
$info['CHAR_P_COMMENT_B']=$fileinf['SectionsFound']['COMMENT'][1];
$info['CHAR_P_COMMENT_C']=$fileinf['SectionsFound']['COMMENT'][2];


if($exif = exif_read_data($img, EXIF, true)) // Si le fichier $img contient des infos Exif
{
  foreach ($exif as $key => $section) // On parcourt la première partie du tableau multidimensionnel
	{       
		foreach ($section as $name => $value) // On parcourt la seconde partie
		{
			$exif_tab[$name] .= $value; // Récupération des valeurs dans le tableau $exif_tab
		}
	}
}	
	//remise au propre des variables récupérée en exif
	$info['CHAR_P_EXIF_FOCALLENGHT']=round($exif_tab['FocalLength'], 0);//en mm
	$info['CHAR_P_EXIF_MAKE']=$exif_tab['Make'];//marque de l'appareil
	$info['CHAR_P_EXIF_MODEL']=$exif_tab['Model'];//model de l'appareil
	$info['CHAR_P_EXIF_EXPOSURE']=$exif_tab['ExposureTime'];//vitesse d'opturation
	$info['CHAR_P_EXIF_ISOSPEEDRATING']=$exif_tab['ISOSpeedRatings'];//valeur iso
	$info['CHAR_P_EXIF_DTORIG']=$exif_tab['DateTimeOriginal'];; // Date de la prise de vue (heure de l'appareil) // !! attention l'appareil n'est peut être pas à l'heure
	$info['CHAR_P_EXIF_SOFTWARE'] = $exif_tab['Software'];

return $info;
}
//*********************************************************************************************
//fonction permettant de redimensionner une image entrée (png, jpeg, gif)
//*********************************************************************************************
// function redimensionner_img($file, $x, $y) {

// 	$size = getimagesize($file);

// 	if ($size) {
// 	//echo 'Image en cours de redimensionnement...';

// 	if ($size['mime']=='image/jpeg' ) {
// 	$img_big = imagecreatefromjpeg($file); # On ouvre l'image d'origine
// 	$img_new = imagecreate($x, $y);
// 	# création de la miniature
// 	$img_mini = imagecreatetruecolor($x, $y)
// 	or $img_mini = imagecreate($x, $y);

// 	// copie de l'image, avec le redimensionnement.
// 	imagecopyresized($img_mini,$img_big,0,0,0,0,$x,$y,$size[0],$size[1]);

// 	$imageredim = imagejpeg($img_mini,$file );

// 	}
// 	elseif ($size['mime']=='image/png' ) {

// 	$img_big = imagecreatefrompng($file); # On ouvre l'image d'origine
// 	$img_new = imagecreate($x, $y);
// 	# création de la miniature
// 	$img_mini = imagecreatetruecolor($x, $y)
// 	or $img_mini = imagecreate($x, $y);

// 	// copie de l'image, avec le redimensionnement.
// 	imagecopyresized($img_mini,$img_big,0,0,0,0,$x,$y,$size[0],$size[1]);

// 	$imageredim = imagepng($img_mini,$file );

// 	}
// 	elseif ($size['mime']=='image/gif' ) {
// 	$img_big = imagecreatefromgif($file); # On ouvre l'image d'origine
// 	$img_new = imagecreate($x, $y);
// 	# création de la miniature
// 	$img_mini = imagecreatetruecolor($x, $y)
// 	or $img_mini = imagecreate($x, $y);

// 	// copie de l'image, avec le redimensionnement.
// 	imagecopyresized($img_mini,$img_big,0,0,0,0,$x,$y,$size[0],$size[1]);

// 	$imageredim = imagegif($img_mini,$file );

// 	}
// 	//echo 'Image redimensionnée !';
// 	}
	
// 	return $imageredim;

// }
//*********************************************************************************************
//test erreur code de base
//*********************************************************************************************
function gl_testerrorlistmail($errorlist)
{
	$result=0;
	//Print_r($errorlist);
	//[name] => 0 [name2] => 3 [mail] => 0 [mdp] => 0 [pass2] => 0 [adress] => 3 [cp] => 0 [town] => 0 [phone] => 0
		if($errorlist['mail']!=0){$result=$result+1;}
	return $result;		
}
function gl_mistake_login($mail,$pass)
{
if($mail==''){
	$errorlist['mail']=1;	
}else{
	
	if(is_numeric($mail)){	
		$errorlist['mail']=2;
	}
	else{
		$pattern="#^([a-zA-Z0-9]+(([\.\-\_]?[a-zA-Z0-9]+)+)?)\@(([a-zA-Z0-9]+[\.\-\_])+[a-zA-Z]{2,4})$#";
		if (preg_match($pattern, $mail)){
			$errorlist['mail']=0;		
		}else{
			$errorlist['mail']=3;
		}
	}
}

return $errorlist;	
}
//*********************************************************************************************
//Formatage correct du nom botanique d'une plante
//*********************************************************************************************
function gl_formatnombotanique($PLANTE)
{
	if(trim($PLANTE['CHAR_P_VARIETE'])!=''){$variete=' var. '.$PLANTE['CHAR_P_VARIETE'];}else{$variete='';}
	if($PLANTE['CHAR_P_CULTIVAR']!=''){$cultivar=' \''.$PLANTE['CHAR_P_CULTIVAR'].'\'';}else{$cultivar='';}
	if($PLANTE['CHAR_P_MARQUE']!=''){$marque=' '.$PLANTE['CHAR_P_MARQUE'];}else{$marque='';}
	$nom=ucwords($PLANTE['CHAR_P_GENRE']).' '.strtolower($PLANTE['CHAR_P_ESPECE']).$variete.$marque.$cultivar;
	return $nom;
}
//*********************************************************************************************
//connaitre le jour de la semaine
//*********************************************************************************************
function knowday($timestampbase)
{
			$jour=date("l", $timestampbase);
			$numday=date("d", $timestampbase);
			if($numday<=7){$orderday='first';}
			else if($numday<=14){$orderday='second';}
			else if($numday<=21){$orderday='third';}
			else if($numday<=28){$orderday='fourth';}
			$datedetail=$orderday.' '.$jour;
			return $datedetail;
}
//*********************************************************************************************
//conversion de date et temps
//*********************************************************************************************
function dateFR( $time) {
	setlocale(LC_ALL, 'fr_FR');
	return @strftime('%A %d %B %Hh %Mmin', strtotime($time));
}
function dateFRshort( $time) {
	setlocale(LC_ALL, 'fr_FR');
	return @strftime('%A %d %B %Y', strtotime($time));
}
// format racourcis (pas d'heure)
function dateFR_S( $time) {
	setlocale(LC_ALL, 'fr_FR');
	return @strftime('%d %B %Y', strtotime($time));
}
function dateFR_slash( $time) {
	setlocale(LC_ALL, 'fr_FR');
	return @strftime('%d/%m/%Y', strtotime($time));
}
function invert_dateFR_slash( $time) {
	setlocale(LC_ALL, 'fr_FR');
	$listtime=explode('/',$time);
	return $listtime[2].'-'.$listtime[1].'-'.$listtime[0];
}
function unix_timestamp($date)
{
	$date = str_replace(array(' ', ':'), '/', $date);
	$c    = explode('/', $date);
	$c    = array_pad($c, 6, 0);
	array_walk($c, 'intval');
 
	return mktime($c[3], $c[4], $c[5], $c[1], $c[0], $c[2]);
}

//*********************************************************************************************
//supprimer les accents 
//*********************************************************************************************

function stripaccents($txt)
{
    $txt = str_replace('œ', 'oe', $txt);
    $txt = str_replace('Œ', 'Oe', $txt);
    $txt = str_replace('æ', 'ae', $txt);
    $txt = str_replace('Æ', 'Ae', $txt);
    mb_regex_encoding('UTF-8');
    $txt = mb_ereg_replace('[ÀÁÂÃÄÅĀĂǍẠẢẤẦẨẪẬẮẰẲẴẶǺĄ]', 'A', $txt);
    $txt = mb_ereg_replace('[àáâãäåāăǎạảấầẩẫậắằẳẵặǻą]', 'a', $txt);
    $txt = mb_ereg_replace('[ÇĆĈĊČ]', 'C', $txt);
    $txt = mb_ereg_replace('[çćĉċč]', 'c', $txt);
    $txt = mb_ereg_replace('[ÐĎĐ]', 'D', $txt);
    $txt = mb_ereg_replace('[ďđ]', 'd', $txt);
    $txt = mb_ereg_replace('[ÈÉÊËĒĔĖĘĚẸẺẼẾỀỂỄỆ]', 'E', $txt);
    $txt = mb_ereg_replace('[èéêëēĕėęěẹẻẽếềểễệ]', 'e', $txt);
    $txt = mb_ereg_replace('[ĜĞĠĢ]', 'G', $txt);
    $txt = mb_ereg_replace('[ĝğġģ]', 'g', $txt);
    $txt = mb_ereg_replace('[ĤĦ]', 'H', $txt);
    $txt = mb_ereg_replace('[ĥħ]', 'h', $txt);
    $txt = mb_ereg_replace('[ÌÍÎÏĨĪĬĮİǏỈỊ]', 'I', $txt);
    $txt = mb_ereg_replace('[ìíîïĩīĭįıǐỉị]', 'i', $txt);
    $txt = str_replace('Ĵ', 'J', $txt);
    $txt = str_replace('ĵ', 'j', $txt);
    $txt = str_replace('Ķ', 'K', $txt);
    $txt = str_replace('ķ', 'k', $txt);
    $txt = mb_ereg_replace('[ĹĻĽĿŁ]', 'L', $txt);
    $txt = mb_ereg_replace('[ĺļľŀł]', 'l', $txt);
    $txt = mb_ereg_replace('[ÑŃŅŇ]', 'N', $txt);
    $txt = mb_ereg_replace('[ñńņňŉ]', 'n', $txt);
    $txt = mb_ereg_replace('[ÒÓÔÕÖØŌŎŐƠǑǾỌỎỐỒỔỖỘỚỜỞỠỢ]', 'O', $txt);
    $txt = mb_ereg_replace('[òóôõöøōŏőơǒǿọỏốồổỗộớờởỡợð]', 'o', $txt);
    $txt = mb_ereg_replace('[ŔŖŘ]', 'R', $txt);
    $txt = mb_ereg_replace('[ŕŗř]', 'r', $txt);
    $txt = mb_ereg_replace('[ŚŜŞŠ]', 'S', $txt);
    $txt = mb_ereg_replace('[śŝşš]', 's', $txt);
    $txt = mb_ereg_replace('[ŢŤŦ]', 'T', $txt);
    $txt = mb_ereg_replace('[ţťŧ]', 't', $txt);
    $txt = mb_ereg_replace('[ÙÚÛÜŨŪŬŮŰŲƯǓǕǗǙǛỤỦỨỪỬỮỰ]', 'U', $txt);
    $txt = mb_ereg_replace('[ùúûüũūŭůűųưǔǖǘǚǜụủứừửữự]', 'u', $txt);
    $txt = mb_ereg_replace('[ŴẀẂẄ]', 'W', $txt);
    $txt = mb_ereg_replace('[ŵẁẃẅ]', 'w', $txt);
    $txt = mb_ereg_replace('[ÝŶŸỲỸỶỴ]', 'Y', $txt);
    $txt = mb_ereg_replace('[ýÿŷỹỵỷỳ]', 'y', $txt);
    $txt = mb_ereg_replace('[ŹŻŽ]', 'Z', $txt);
    $txt = mb_ereg_replace('[źżž]', 'z', $txt);
    return $txt;

}
//*********************************************************************************************
//convertir les caractères spéciaux en leur code utf8
//*********************************************************************************************
function preg_utf8($text)
{
str_replace("é",	"&eacute;",$text);
str_replace("è",	"&egrave;",$text);
str_replace("È",	"&Egrave;",$text);  
str_replace("ë",	"&euml;",$text);
str_replace("Ë",	"&Euml;",$text);  
str_replace("à",	"&agrave;",$text);  
str_replace("À",	"&Agrave;",$text);  
str_replace("â",	"&acirc;",$text);  
str_replace("Â",	"&Acirc;",$text);  
str_replace("û",	"&ucirc;",$text);  
str_replace("Û",	"&Ucirc;",$text);  
str_replace("ù",	"&ugrave;",$text);  
str_replace("Ù",	"&Ugrave;",$text);  
str_replace("ê",	"&ecirc;",$text);  
str_replace("Ê",	"Ecirc;",$text);  
str_replace("î",	"&icirc;",$text);  
str_replace("Î",	"&Icirc;",$text);  
str_replace("ô",	"&ocirc;",$text);  
str_replace("Ô",	"&Ocirc;",$text);  
str_replace("ç",	"&ccedil;",$text);  
str_replace("Ç",	"&Ccedil;",$text);  
str_replace("ï",	"&iuml;",$text);  
str_replace("Ï",	"&Iuml;",$text);  
str_replace("ï",	"&iuml;",$text);  
str_replace("Ï",	"&Iuml;",$text);  
str_replace("™",	"&trade;",$text);  
str_replace("®",	"&reg;",$text);  
str_replace("©",	"&copy;",$text);  
str_replace("€",	"&euro;",$text); 
str_replace("\"",	"&#34;",$text);	//&quot;
str_replace("&",	"&#38;",$text);	//&amp;
str_replace("€",	"&#128;",$text);//&euro;
str_replace("ƒ",	"&#131;",$text);	
str_replace("…",	"&#133;",$text);	
str_replace("†",	"&#134;",$text);	
str_replace("‡",	"&#135;",$text);
str_replace("ˆ",	"&#136;",$text);	
str_replace("‰",	"&#137;",$text);	
str_replace("Š",	"&#138;",$text);	
str_replace("<",	"&#139;",$text);	//&lt;
str_replace("Œ",	"&#140;",$text);	
str_replace("Ž",	"&#142;",$text);	
str_replace("‘",	"&#145;",$text);	
str_replace("’",	"&#146;",$text);	
str_replace("“",	"&#147;",$text);	
str_replace("”",	"&#148;",$text);	
str_replace("•",	"&#149;",$text);	
str_replace("–",	"&#150;",$text);	
str_replace("—",	"&#151;",$text);	
str_replace("˜",	"&#152;",$text);	
str_replace("™",	"&#153;",$text);	
str_replace("š",	"&#154;",$text);	
str_replace("›",	"&#155;",$text);	//&gt;
str_replace("œ",	"&#156;",$text);	//&oelig;
str_replace("ž",	"&#158;",$text);	
str_replace("Ÿ",	"&#159;",$text);	//&Yuml;
str_replace("¡",	"&#161;",$text);	//&iexcl;
str_replace("¢",	"&#162;",$text);	//&cent;
str_replace("£",	"&#163;",$text);	//&pound;
str_replace("¤",	"&#164;",$text);	//&curren;
str_replace("¥",	"&#165;",$text);	//¥
str_replace("¦",	"&#166;",$text);	//&brvbar;
str_replace("§",	"&#167;",$text);	//&sect;
str_replace("¨",	"&#168;",$text);	//&uml;
str_replace("©",	"&#169;",$text);	//&copy;
str_replace("ª",	"&#170;",$text);	//&ordf;
str_replace("«",	"&#171;",$text);	//&laquo;
str_replace("¬",	"&#172;",$text);	//&not;
str_replace("­",	"&#173;",$text);	//&shy;
str_replace("®",	"&#174;",$text);	//&reg;
str_replace("¯",	"&#175;",$text);	//&masr;
str_replace("°",	"&#176;",$text);	//&deg;
str_replace("±",	"&#177;",$text);	//&plusmn;
str_replace("²",	"&#178;",$text);	//&sup2;
str_replace("³",	"&#179;",$text);	//&sup3;
str_replace("´",	"&#180;",$text);	//&acute;
str_replace("µ",	"&#181;",$text);	//&micro;
str_replace("¶",	"&#182;",$text);	//&para;
str_replace("·",	"&#183;",$text);	//&middot;
str_replace("¸",	"&#184;",$text);	//&cedil;
str_replace("¹",	"&#185;",$text);	//&sup1;
str_replace("º",	"&#186;",$text);	//&ordm;
str_replace("»",	"&#187;",$text);	//&raquo;
str_replace("¼",	"&#188;",$text);	//&frac14;
str_replace("½",	"&#189;",$text);	//&frac12;
str_replace("¾",	"&#190;",$text);	//&frac34;
str_replace("¿",	"&#191;",$text);	//&iquest;
str_replace("À",	"&#192;",$text);	//&Agrave;
str_replace("Á",	"&#193;",$text);	//&Aacute;
str_replace("Â",	"&#194;",$text);	//&Acirc;
str_replace("Ã",	"&#195;",$text);	//&Atilde;
str_replace("Ä",	"&#196;",$text);	//&Auml;
str_replace("Å",	"&#197;",$text);	//&Aring;
str_replace("Ç",	"&#199;",$text);	//&Ccedil;
str_replace("È",	"&#200;",$text);	//&Egrave;
str_replace("É",	"&#201;",$text);	//&Eacute;
str_replace("Ê",	"&#202;",$text);	//&Ecirc;
str_replace("Ë",	"&#203;",$text);	//&Euml;
str_replace("Ì",	"&#204;",$text);	//&Igrave;
str_replace("Í",	"&#205;",$text);	//&Iacute;
str_replace("Î",	"&#206;",$text);	//&Icirc;
str_replace("Ï",	"&#207;",$text);	//&Iuml;
str_replace("Ð",	"&#208;",$text);	//&eth;
str_replace("Ñ",	"&#209;",$text);	//&Ntilde;
str_replace("Ò",	"&#210;",$text);	//&Ograve;
str_replace("Ó",	"&#211;",$text);	//&Oacute;
str_replace("Ô",	"&#212;",$text);	//&Ocirc;
str_replace("Õ",	"&#213;",$text);	//&Otilde;
str_replace("Ö",	"&#214;",$text);	//&Ouml;
str_replace("×",	"&#215;",$text);	//&times;
str_replace("Ø",	"&#216;",$text);	//&Oslash;
str_replace("Ù",	"&#217;",$text);	//&Ugrave;
str_replace("Ú",	"&#218;",$text);	//&Uacute;
str_replace("Û",	"&#219;",$text);	//&Ucirc;
str_replace("Ü",	"&#220;",$text);	//&Uuml;
str_replace("Ý",	"&#221;",$text);	//&Yacute;
str_replace("Þ",	"&#222;",$text);	//&thorn;
str_replace("ß",	"&#223;",$text);	//&szlig;
str_replace("À",	"&#224;",$text);	//&agrave;
str_replace("Á",	"&#225;",$text);	//&aacute;
str_replace("Â",	"&#226;",$text);	//&acirc;
str_replace("Ã",	"&#227;",$text);	//&atilde;
str_replace("Ä",	"&#228;",$text);	//&auml;
str_replace("Å",	"&#229;",$text);	//&aring;
str_replace("Æ",	"&#230;",$text);	//&aelig;
str_replace("Ç",	"&#231;",$text);	//&ccedil;
str_replace("È",	"&#232;",$text);	//&egrave;
str_replace("É",	"&#233;",$text);	//&eacute;
str_replace("Ê",	"&#234;",$text);	//&ecirc;
str_replace("Ë",	"&#235;",$text);	//&euml;
str_replace("Ì",	"&#236;",$text);	//&igrave;
str_replace("Í",	"&#237;",$text);	//&iacute;
str_replace("Î",	"&#238;",$text);	//&icirc;
str_replace("Ï",	"&#239;",$text);	//&iuml;
str_replace("Ð",	"&#240;",$text);	//&eth;
str_replace("Ñ",	"&#241;",$text);	//&ntilde;
str_replace("Ò",	"&#242;",$text);	//&ograve;
str_replace("Ó",	"&#243;",$text);	//&oacute;
str_replace("Ô",	"&#244;",$text);	//&ocirc;
str_replace("Õ",	"&#245;",$text);	//&otilde;
str_replace("Ö",	"&#246;",$text);	//&ouml;
str_replace("÷",	"&#247;",$text);	//&divide;
str_replace("Ø",	"&#248;",$text);	//&oslash;
str_replace("Ù",	"&#249;",$text);	//&ugrave;
str_replace("Ú",	"&#250;",$text);	//&uacute;
str_replace("Û",	"&#251;",$text);	//&ucirc;
str_replace("Ü",	"&#252;",$text);	//&uuml;
str_replace("Ý",	"&#253;",$text);	//&yacute;
str_replace("Þ",	"&#254;",$text);	//&thorn;
str_replace("Ÿ",	"&#255;",$text);	//&yuml;
str_replace("\"",	"&#34;",$text);	//	&quot;
str_replace("&",	"&#38;",$text);	//	&amp;
str_replace("<",	"&#139;",$text);	//	&lt;
str_replace(">",	"&#155;",$text);	//	&gt;
return $text;
}

//*********************************************************************************************
//supprimer les caractères spéciaux .... pratique dans les formulaire
//*********************************************************************************************
function stripspecialchar($txt)
{
$txt = str_replace('œ', '', $txt);
$txt = str_replace('Œ', '', $txt);
$txt = str_replace('æ', '', $txt);
$txt = str_replace('"', '', $txt);
$txt = str_replace("'", '', $txt);
$txt = str_replace('\'', '', $txt);
$txt = str_replace('\\', '', $txt);
$txt = str_replace('/', '', $txt);
str_replace("™",	"",$text);  
str_replace("®",	"",$text);  
str_replace("©",	"",$text);  
str_replace("€",	"",$text); 
str_replace("\"",	"",$text);	//&quot;
str_replace("&",	"",$text);	//&amp;
str_replace("€",	"",$text);//&euro;
str_replace("ƒ",	"",$text);	
str_replace("…",	"",$text);	
str_replace("†",	"",$text);	
str_replace("‡",	"",$text);
str_replace("ˆ",	"",$text);	
str_replace("‰",	"",$text);	
str_replace("‘",	"",$text);	
str_replace("’",	"",$text);	
str_replace("“",	"",$text);	
str_replace("”",	"",$text);	
str_replace("•",	"",$text);	
str_replace("–",	"",$text);	
str_replace("—",	"",$text);	
str_replace("-",	" ",$text);	
str_replace("˜",	"",$text);	
str_replace("™",	"",$text);	
str_replace("›",	"",$text);	//&gt;
str_replace("¢",	"",$text);	//&cent;
str_replace("£",	"",$text);	//&pound;
str_replace("¤",	"",$text);	//&curren;
str_replace("¥",	"",$text);	//¥
str_replace("¦",	"",$text);	//&brvbar;
str_replace("§",	"",$text);	//&sect;
str_replace("¨",	"",$text);	//&uml;
str_replace("©",	"",$text);	//&copy;
str_replace("ª",	"",$text);	//&ordf;
str_replace("«",	"",$text);	//&laquo;
str_replace("¬",	"",$text);	//&not;
str_replace("­",	"",$text);	//&shy;
str_replace("®",	"",$text);	//&reg;
str_replace("¯",	"",$text);	//&masr;
str_replace("°",	"",$text);	//&deg;
str_replace("±",	"",$text);	//&plusmn;
str_replace("²",	"",$text);	//&sup2;
str_replace("³",	"",$text);	//&sup3;
str_replace("´",	"",$text);	//&acute;
str_replace("µ",	"",$text);	//&micro;
str_replace("¶",	"",$text);	//&para;
str_replace("·",	"",$text);	//&middot;
str_replace("¸",	"",$text);	//&cedil;
str_replace("¹",	"",$text);	//&sup1;
str_replace("º",	"",$text);	//&ordm;
str_replace("»",	"",$text);	//&raquo;
str_replace("¼",	"",$text);	//&frac14;
str_replace("½",	"",$text);	//&frac12;
str_replace("¾",	"",$text);	//&frac34;
str_replace("¿",	"",$text);	//&iquest;
str_replace("÷",	"",$text);	//&divide;
str_replace("Ø",	"",$text);	//&oslash;
str_replace("\"",	"",$text);	//	&quot;
str_replace("&",	"",$text);	//	&amp;
str_replace("<",	"",$text);	//	&lt;
str_replace(">",	"",$text);	//	&gt;
	return $txt;
} 
//*********************************************************************************************
/// convert des caractères bizarres de word (en test)
//*********************************************************************************************

function msword_text_to_ascii($str){
 $search = array('œ','•','&','<','>','"',chr(212),chr(213),chr(210),chr(211),chr(209),chr(208),chr(201),chr(145),chr(146),chr(147),chr(148),chr(151),chr(150),chr(133));
$replace = array('&#156','&#149;','&amp;','&lt;','&gt;','&quot;','&#8216;','&#8217;','&#8220;','&#8221;','&#8211;','&#8212;','&#8230;','&#8216;','&#8217;','&#8220;','&#8221;','&#8211;','&#8212;','&#8230;');
	$str = str_replace($search,$replace , $str);
	return $str;
}
//*********************************************************************************************
// convertir en boucle une collection de parametre en utf8
//*********************************************************************************************
function toUTF8($param)
{
    if(is_array($param)) {
     	foreach ($param as $p){
		 	toUTF8($p);
	 	}
	}else{
		 $p=utf8_encode($p);
	}
}
//*********************************************************************************************
// récupération de l'ensemble des noms de plantes pour auto complé
//*********************************************************************************************
function gen_french_chars_care ( $str) {
	
	
		$text = str_replace( 'Ã©', '&eacute;', $str );
		$text = str_replace( 'Ã¢', '&acirc;', $text );
		$text = str_replace( 'Ã§', '&ccedil;', $text );
		$text = str_replace( 'Ã´', '&ocirc;', $text );
		$text = str_replace( 'Ã¨', '&egrave;', $text );
		$text = str_replace( 'Â°', '&deg;', $text );
		$text = str_replace( 'Ãª', '&ecirc;', $text );
		$text = str_replace( 'Ã'.chr(160), '&agrave;', $text );
		$text = str_replace( 'Â²', '&sup2;', $text );
		$text = str_replace( 'â‚¬', '&euro;', $text );
		$text = str_replace( 'â€™', '\'', $text );
	
	
	return $text;
}
// ************************************************************
// Used to apply (add/strip)slashes to array entries,
// if magic_quotes are off. Also it converts french unicode
// symbols to HTML equivalents if not in plain mode.
//
// !!!BE CAREFUL!!!, if an array
// entry is an array, this function will spoil this
// entry without any error messages.
//
// Parameters:
//
// $dirty_array - [array] array to filter
//
// $mode 		- [string]
//		add 	- to apply addslashes
//		strip 	- to apply stripslashes
//
// $plain_mode 	- [boolean]
//		true 	- to convert french characters to latin letters
//		false 	- to convert french characters to HTML equivalents
//
// $ascii 		- [boolean] for debugging...
//		true	- produce output of every character in ascii codes
//		false	- common output
//
// ************************************************************
function gen_magic_filter ( $dirty_array, $mode, $plain_mode = false, $ascii = false ) {
	if ( isset( $dirty_array ) && is_array( $dirty_array ) && ( $mode == 'strip' ) ) {
		$text = '';
		foreach ($dirty_array as $key => $value) {
			if ( $plain_mode ) {
				$text = gen_french_chars_care( htmlspecialchars( $value ), true );
			} else {
				$text = gen_french_chars_care( htmlspecialchars( $value ) );
			}
			$dirty_array[ $key ] = nl2br( $text );
		}
	}
	if ( !get_magic_quotes_gpc() && isset( $dirty_array ) && is_array( $dirty_array ) ) {
			$clean_array = array();
			
			if ( $mode == 'add' ) $func = 'addslashes';
			elseif ( $mode == 'strip' ) $func = 'stripslashes';
			
			$text = '';
			foreach ($dirty_array as $key => $value) {
				
				if ( !$ascii ) {
					$clean_array[ $key ] = @$func( $value );
				}
				else {
					$tmp = $func( $value );
					$chars = '';
					if ( !empty( $tmp ) ) {
						for ( $i = 0; $i < strlen( $tmp ); $i++ ) {
							$chars .= ord( $tmp[ $i ] ) . ';';
						}
					}
					$clean_array[ $key ] = $chars;
				}
			}
	} else {
		$clean_array = $dirty_array;
	}
	
	return $clean_array;
}
//*********************************************************************************************
//fourni l'hexa inverse (pratique pour les couleurs)
//*********************************************************************************************
function inverseHex($color)
{
     str_replace("#","",$color);
     $r = dechex(255-hexdec(substr($color,0,2)));
     $r = (strlen($r)>1)?$r:'0'.$r;
     $g = dechex(255-hexdec(substr($color,2,2)));
     $g = (strlen($g)>1)?$g:'0'.$g;
     $b = dechex(255-hexdec(substr($color,4,2)));
     $b = (strlen($b)>1)?$b:'0'.$b;
 	 return $r.$g.$b;
}
//*********************************************************************************************
//test connection https
//*********************************************************************************************
function is_it_secure(){
	if(SECURECONNECTION==1){
		if($_SERVER["SERVER_PORT"] == 80){
			header('location:'.BASEURL);	
		}
	}
}
function gl_recupurlrewrite_start()
{
	
	$baseurl="";
	$tab=explode('/',$_SERVER['REQUEST_URI']);
	$counttab=count($tab);
	for($i=0;$i<3;$i++){$baseurl.=$tab[$i].'/';}
	return $baseurl;
	
}
function gl_recupurlrewrite_end()
{
	$baseurl="";
	$tab=explode('/',$_SERVER['REQUEST_URI']);
	return $tab[count($tab)-1];
	
}

//*********************************************************************************************
//test OS
//*********************************************************************************************
// function getOS( $ua = '' )
// {
// if( ! $ua ) $ua = $_SERVER['HTTP_USER_AGENT'];
// $os = 'Système d&#39;exploitation inconnu';
// $os_arr = Array(
// // -- Windows
// 'Windows NT' => 'Windows NT',
// 'Windows NT 6.1' => 'Windows Seven',
// 'Windows NT 6.0' => 'Windows Vista',
// 'Windows NT 5.2' => 'Windows Server 2003',
// 'Windows NT 5.1' => 'Windows XP',
// 'Windows NT 5.0' => 'Windows 2000',
// 'Windows 2000' => 'Windows 2000',
// 'Windows CE' => 'Windows Mobile',
// 'Win 9x 4.90' => 'Windows Me.',
// 'Windows 98' => 'Windows 98',
// 'Windows 95' => 'Windows 95',
// 'Win95' => 'Windows 95',

// // -- Linux
// 'Ubuntu' => 'Linux Ubuntu',
// 'Fedora' => 'Linux Fedora',
// 'Linux' => 'Linux',
// // -- Mac
// 'Mac' => 'Mac',
// 'Macintosh' => 'Mac',
// 'Mac OS X' => 'Mac OS X',
// 'Mac_PowerPC' => 'Mac OS X',
// 'iPad' => 'Ipad',
// 'ipod' => 'Ipad',
// 'iphone' => 'Iphone',

// // -- Autres ...
// 'FreeBSD' => 'FreeBSD',
// 'Unix' => 'Unix',
// 'Playstation portable' => 'PSP',
// 'OpenSolaris' => 'SunOS',
// 'SunOS' => 'SunOS',
// 'Nintendo Wii' => 'Nintendo Wii',

// 'android' => 'android',
// 'palm' => 'palm',
// );

// $ua = strtolower( $ua );
// foreach( $os_arr as $k => $v )
// {
// if( ereg( strtolower( $k ), $ua ) )
// {
// $os = $v;

// }
// }
// return $os;
// }

//*********************************************************************************************
//test tablette
//*********************************************************************************************
function is_it_tab(){
if (
 strripos($_SERVER['HTTP_USER_AGENT'], 'Tablet') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'Sony') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'Nokia') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'Blackberry') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'Pocket') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'Windows CE') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false
 || strripos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false
 ){$_SESSION['istablet']=true;}else{$_SESSION['istablet']=false;}
}


//*********************************************************************************************
//enlève les caractères ASCII à un texte
//*********************************************************************************************
function unhtmlentities($string)
{
	// Remplace les entités numériques
	$string = preg_replace('~([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
	$string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
	// Remplace les entités litérales
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}

//*********************************************************************************************
//lecture d'un répertoire d'image renvoi la liste des images du répertoire
//*********************************************************************************************
function readphotos($path,$number)
{
	$num=0;
	//$path='./Photo_plantes';
	echo $path."<br />";
	$MyDirectory = opendir($path) or die('Erreur');
	while($Entry = readdir($MyDirectory)) {
		//echo $Entry.'<br />';
		if($Entry != '.' && $Entry != '..' ) {
             $picturelist[]=$Entry;
			 //echo $Entry.'__Cest OK!!!!!!!!!!!!!!!!!<br />';
		}
		$num++;
	}
	
  @closedir($path);
  $ct=count($picturelist);
 
  for($i=0;$i<$number;$i++)
  {
	 $hasard = rand(0, $ct-1) ; 
	 $list[] =$picturelist[$hasard];
	  
   }
 // $picturelist=shuffle($picturelist);
  return $list;
}   

function redimensionner_img($file, $x, $y,$largorig,$hautorig) {

	$size = getimagesize($file);
	
	if($largorig>$hautorig){//format paysage
	//imagecopyresized ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
	// imagecopyresized() prendra une forme rectangulaire src_image 
	//d'une largeur de src_w et d'une hauteur src_h 
	$src_w=$hautorig;
	$src_h=$hautorig;
	//à la position (src_x,src_y) et 
	$src_y=0;
	$src_x=($largorig-$hautorig)/2;
	//le placera dans une zone rectangulaire dst_image 
	//d'une largeur de dst_w et d'une hauteur de dst_h à la position (dst_x,dst_y). 
	$dst_w=$x;
	$dst_h=$y;
	$dst_x=0;
	$dst_y=0;
	}else if($hautorig>$largorig){//format portrait
	$src_w=$largorig;
	$src_h=$largorig;
	$src_y=($hautorig-$largorig)/2;
	$src_x=0;
	$dst_w=$x;
	$dst_h=$y;
	$dst_x=0;
	$dst_y=0;
	}else{
	$src_w=$largorig;
	$src_h=$hautorig;
	$src_y=0;
	$src_x=0;
	$dst_w=$x;
	$dst_h=$y;
	$dst_x=0;
	$dst_y=0;
		}
	
	
	
	if ($size) {
	//echo 'Image en cours de redimensionnement...';

	if ($size['mime']=='image/jpeg' ) {
	$img_big = imagecreatefromjpeg($file); # On ouvre l'image d'origine
	$img_new = imagecreate($x, $y);
	# création de la miniature
	$img_mini = imagecreatetruecolor($x, $y)
	or $img_mini = imagecreate($x, $y);

	// copie de l'image, avec le redimensionnement.
	imagecopyresized($img_mini,$img_big,$dst_x ,$dst_y ,$src_x ,$src_y , $dst_w ,$dst_h ,$src_w ,$src_h);

	$imageredim = imagejpeg($img_mini,$file );

	}
	elseif ($size['mime']=='image/png' ) {

	$img_big = imagecreatefrompng($file); # On ouvre l'image d'origine
	$img_new = imagecreate($x, $y);
	# création de la miniature
	$img_mini = imagecreatetruecolor($x, $y)
	or $img_mini = imagecreate($x, $y);

	// copie de l'image, avec le redimensionnement.
	imagecopyresized($img_mini,$img_big,$dst_x ,$dst_y ,$src_x ,$src_y , $dst_w ,$dst_h ,$src_w ,$src_h);

	$imageredim = imagepng($img_mini,$file );

	}
	elseif ($size['mime']=='image/gif' ) {
	$img_big = imagecreatefromgif($file); # On ouvre l'image d'origine
	$img_new = imagecreate($x, $y);
	# création de la miniature
	$img_mini = imagecreatetruecolor($x, $y)
	or $img_mini = imagecreate($x, $y);

	// copie de l'image, avec le redimensionnement.
	imagecopyresized($img_mini,$img_big,$dst_x ,$dst_y ,$src_x ,$src_y , $dst_w ,$dst_h ,$src_w ,$src_h);

	$imageredim = imagegif($img_mini,$file );

	}
	//echo 'Image redimensionnée !';
	}
	
	return $imageredim;

} 


//*********************************************************************************************
//converti n'importe quel jeu de caractère en utf8
//*********************************************************************************************
function convert_charset($item)
    {
        if ($unserialize = unserialize($item))
        {
            foreach ($unserialize as $key => $value)
            {
                $unserialize[$key] = @iconv('windows-1256', 'UTF-8', $value);
            }
            $serialize = serialize($unserialize);
            return $serialize;
        }
        else
        {
            return @iconv('windows-1256', 'UTF-8', $item);
        }
    } 
    
    
function content8859_in_UTF8($str)
{

 if ( strlen($str) == 0 ) { return; }
 // cette fonction ne retourne de valeur si la chaine est en UTF8
 // cette fonction retourne un tableau contenant les chaines accentuées
 preg_match_all('/.{1}|[^\x00]{1,1}$/us', $str, $ar);
 $chars = $ar[0];
 $str_fr = 0;
 foreach ( $chars as $i => $c ){
 $ud = 0;
 // Calcul les codes ASCII des chaines en UTF8
 if (ord($c{0})>=0 && ord($c{0})<=127) { continue; } // ASCII - next please
 if (ord($c{0})>=192 && ord($c{0})<=223) { $ord = (ord($c{0})-192)*64 + (ord($c{1})-128); }
 if (ord($c{0})>=224 && ord($c{0})<=239) { $ord = (ord($c{0})-224)*4096 + (ord($c{1})-128)*64 + (ord($c{2})-128); }
 if (ord($c{0})>=240 && ord($c{0})<=247) { $ord = (ord($c{0})-240)*262144 + (ord($c{1})-128)*4096 + (ord($c{2})-128)*64 + (ord($c{3})-128); }
 if (ord($c{0})>=248 && ord($c{0})<=251) { $ord = (ord($c{0})-248)*16777216 + (ord($c{1})-128)*262144 + (ord($c{2})-128)*4096 + (ord($c{3})-128)*64 + (ord($c{4})-128); }
 if (ord($c{0})>=252 && ord($c{0})<=253) { $ord = (ord($c{0})-252)*1073741824 + (ord($c{1})-128)*16777216 + (ord($c{2})-128)*262144 + (ord($c{3})-128)*4096 + (ord($c{4})-128)*64 + (ord($c{5})-128); }
 if (ord($c{0})>=254 && ord($c{0})<=255) { $chars{$i} = $unknown; continue; } //error
 //Test si les caractères contient les accents (à, é,è,ù,ç,ê,â,û,........)
 if(($ord == 224) || ($ord == 226) || ($ord == 235) || ($ord == 249) || ($ord == 250) ||
 ($ord == 252) || ($ord == 251) || ($ord == 233) || ($ord == 234) || ($ord == 232) ||
 ($ord == 231) || ($ord == 228) || ($ord == 256) || ($ord == 128) || ($ord == 156) ||
 ($ord == 230) || ($ord == 231) || ($ord == 244) || ($ord == 225) || ($ord == 236) ||
 ($ord == 227) || ($ord == 237) || ($ord == 238) || ($ord == 249) || ($ord == 239) ||
 ($ord == 257)){
 $str_fr =1;
 }
 }
 if($str_fr == 1){
 return "TRUE";
 }else{
 return "FALSE";
 }
} 
   
//*********************************************************************************************
//fonction de création du lien de renvoi identifier dans les mails à réceptionner
//********************************************************************************************* 
function generer_lien($db,$idmail,$recepteur) 
{
$infos_mail=select_infos_mail($db,$idmail,$recepteur);
$infos=$infos_mail['IDMESSAGE']."_".$infos_mail['DESTINATAIRE']."_".$infos_mail['ENVOYEUR']."_".$infos_mail['DATE_ENVOYER'];
//echo"<br />//////////////////////////////////<br />";
//echo $infos;
//echo"<br />//////////////////////////////////<br />";
$infos_encode=Crypte($infos,mdp);

//echo $infos_encode;
//echo"<br />//////////////////////////////////<br />";
$infos_decode=Decrypte($infos_encode,mdp);
//echo $infos_decode;
//echo"<br />//////////////////////////////////<br />";

$lien=BASEURL."/mon-compte/".$infos_encode."/courriel.html";

return $lien;
}

//*********************************************************************************************
//fonction de création du lien crypté: sur base d'un nmot de passe cryptage md5
//********************************************************************************************* 
// function GenerationCle($Texte,$CleDEncryptage)
//   {
//   $CleDEncryptage = md5($CleDEncryptage);
//   $Compteur=0;
//   $VariableTemp = "";
//   for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++)
//     {
//     if ($Compteur==strlen($CleDEncryptage))
//       $Compteur=0;
//     $VariableTemp.= substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1);
//     $Compteur++;
//     }
//   return $VariableTemp;
//   }

// function Crypte($Texte,$Cle)
//   {
//   srand((double)microtime()*1000000);
//   $CleDEncryptage = md5(rand(0,32000) );
//   $Compteur=0;
//   $VariableTemp = "";
//   for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++)
//     {
//     if ($Compteur==strlen($CleDEncryptage))
//       $Compteur=0;
//     $VariableTemp.= substr($CleDEncryptage,$Compteur,1).(substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1) );
//     $Compteur++;
//     }
//   return base64_encode(GenerationCle($VariableTemp,$Cle) );
//   }

// function Decrypte($Texte,$Cle)
//   {
//   $Texte = GenerationCle(base64_decode($Texte),$Cle);
//   $VariableTemp = "";
//   for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++)
//     {
//     $md5 = substr($Texte,$Ctr,1);
//     $Ctr++;
//     $VariableTemp.= (substr($Texte,$Ctr,1) ^ $md5);
//     }
//   return $VariableTemp;
//   } 


















//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
function get_form_data()
{
	if (!empty($_GET)) 
	{
		$formdata = (object)$_GET;
	}
	else if (!empty($_POST)) 
	{
		$formdata = (object)$_POST;	     
	}
	
	if (!isset($formdata->action))
		$formdata->action = false;
	
	return $formdata;
}
//*********************************************************************************************
//
//*********************************************************************************************
function goto_url($url)
{
	global $CFG;
	header("Location: ".$CFG->wwwroot."/$url");
	exit;
}
//*********************************************************************************************
//
//*********************************************************************************************
function require_login($level = null)
{
	global $CFG, $userid, $db;

	if (!isset($_SESSION['islogged']) or !($_SESSION['islogged']) or ($level > $_SESSION['menu']))
	{
		goto_url('login/index.php');
	}
}
//*********************************************************************************************
//
//*********************************************************************************************
function load_user_language()
{
	/*if (isset($_SESSION['user_lang']))
	{
		$MyLang = 'lang/iso_'.$_SESSION['user_lang'].'.php';
	}
	else
	{*/
		$MyLang = 'lang/iso_FRA.php';
	//}

	return $MyLang;
}
//*********************************************************************************************
//
//*********************************************************************************************
function htmlconvertaux(&$vars)
{
	foreach ($vars as $k => $v)
	{ 
		if (($k == 'script') || ($k == 'cfg') || ($k == 'lang')){
			continue;
		}
		else if (is_array($v)){
			htmlconvertaux($vars[$k]);
		}
		else if (!in_array($v, get_html_translation_table(HTML_ENTITIES))){
			if (( (substr_count($vars[$k],"<center" )>0) 
				|| (substr_count($vars[$k],"<html>" )>0)
				|| (substr_count($vars[$k],"<head" )>0)
				|| (substr_count($vars[$k],"<body" )>0)
				|| (substr_count($vars[$k],"<div" )>0)
				|| (substr_count($vars[$k],"<td" )>0)
				|| (substr_count($vars[$k],"<tr" )>0) 
				|| (substr_count($vars[$k],"<img" )>0)
				|| (substr_count($vars[$k],"<br>" )>0) 
				|| (substr_count($vars[$k],"<input" )>0)
				|| (substr_count($vars[$k],"<a" )>0)
				|| (substr_count($vars[$k],"<table" )>0))){
				$vars[$k] = htmlentities($v);
			}
		}
	}
}


//*********************************************************************************************
//test de la validité d'un mail coté serveur
//*********************************************************************************************
function gl_test_mail($mail)
{
	
	if($mail==''){
	$test=1;//vide	
	}else{
		
		if(is_numeric($mail)){	
			$test=2;//pas le bon format
		}else{
			$pattern="#^([a-zA-Z0-9]+(([\.\-\_]?[a-zA-Z0-9]+)+)?)\@(([a-zA-Z0-9]+[\.\-\_])+[a-zA-Z]{2,4})$#";
			if (preg_match($pattern, $mail)){
				
					$test=0;	//OK
				
			}else{
				$test=3;// adresse mail non conforme
			}
		}
	}
	return $test;
}
//*********************************************************************************************
//convertion de temp anglais en timestamp unix
//*********************************************************************************************
function isoTimeToStamp($iso) 
{
  sscanf($iso,"%4u-%u-%uT%u:%2u:%2uZ",$annee,$mois,$jour,$heure,$minute,$seconde);
  $newTstamp = mktime($heure,$minute,$seconde,$mois,$jour,$annee);
  return $newTstamp;
}

//*********************************************************************************************
//convertion de temp francais en timestamp unix
//*********************************************************************************************
function isoTimefrenchToStamp($smartydate) 
{
  $year = substr($smartydate,6,4);
  $month= substr($smartydate,3,2);
  $day=substr($smartydate,0,2);
  $hour=substr($smartydate,11,2);
  $minute=substr($smartydate,14,2);
  $second=substr($smartydate,17,2);
  $date_english=$year."-".$month."-".$day." ".$hour.":".$minute.":".$second;
  $newTstamp = isoTimeToStamp($date_english);
  return $newTstamp;
}
function is_google() {
if (strpos($_SERVER[‘HTTP_USER_AGENT’],"Googlebot"))
 {return true;}
else
 {return false;}
}

function getNav()
{
	$var_nav = explode(' ',$_SERVER['HTTP_USER_AGENT']);
$var_nav = $var_nav[0];
$var_message = "" ;
if (ereg("MSIE 9", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "MSIE 9";
} else if (ereg("MSIE 8", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "MSIE 8";
}else if (ereg("MSIE 7", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "MSIE 7";
} else if (ereg("MSIE 6", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "MSIE 6";
} else if (ereg("MSIE 5", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "MSIE 5";
} else if (ereg("MSIE", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "MSIE <= 4";
} else if (ereg("Firefox/1", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "Firefox 1";
} else if (ereg("Firefox/2", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "Firefox 2";
} else if (ereg("Firefox/3", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "Firefox 3";
} else if (ereg("Firefox/", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "Firefox";
} else if (ereg("Opera/", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "Opera";
	
}else if (ereg("Chrome/", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "Chrome";
	
}else if (ereg("Safari/", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message .= "Safari";
	
} else if (ereg("Mozilla/", $_SERVER["HTTP_USER_AGENT"])) {
	$var_message.= " Mozilla compatible Netscape";
} else {
	$var_message.= " Non déterminé";
} 
return 	$var_message;
	
}

/*******************************************************
* Fonction : getOs ADAPTEE
*----------------------------------------------
* @Desc : Retourne le nom de l'os grâce à l'user agent
* @Param : $ua (str) : l'user agent dont on veux trouver l'os
* @Return : (str) le nom de l'os trouvé sinon "Système d'exploitation inconnu"
* @licence : http://opensource.org/licenses/lgpl-license.php GNU LGPL
*********************************************************/
function getOS( $ua = '' )
{
if( ! $ua ) $ua = $_SERVER['HTTP_USER_AGENT'];
$os = 'Système d&#39;exploitation inconnu';
$os_arr = Array(
// -- Windows
'Windows NT' => 'Windows NT',
'Windows NT 6.1' => 'Windows Seven',
'Windows NT 6.0' => 'Windows Vista',
'Windows NT 5.2' => 'Windows Server 2003',
'Windows NT 5.1' => 'Windows XP',
'Windows NT 5.0' => 'Windows 2000',
'Windows 2000' => 'Windows 2000',
'Windows CE' => 'Windows Mobile',
'Win 9x 4.90' => 'Windows Me.',
'Windows 98' => 'Windows 98',
'Windows 95' => 'Windows 95',
'Win95' => 'Windows 95',

// -- Linux
'Ubuntu' => 'Linux Ubuntu',
'Fedora' => 'Linux Fedora',
'Linux' => 'Linux',
// -- Mac
'Mac' => 'Mac',
'Macintosh' => 'Mac',
'Mac OS X' => 'Mac OS X',
'Mac_PowerPC' => 'Mac OS X',
'iPad' => 'Ipad',
'ipod' => 'Ipad',
'iphone' => 'Iphone',

// -- Autres ...
'FreeBSD' => 'FreeBSD',
'Unix' => 'Unix',
'Playstation portable' => 'PSP',
'OpenSolaris' => 'SunOS',
'SunOS' => 'SunOS',
'Nintendo Wii' => 'Nintendo Wii',

'android' => 'android',
'palm' => 'palm',
);

$ua = strtolower( $ua );
foreach( $os_arr as $k => $v )
{
if( ereg( strtolower( $k ), $ua ) )
{
$os = $v;

}
}
return $os;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// test d'une liste d'erreurs
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
function gl_testerrorlist($errorlist)
{
	$result=0;
	
		if($errorlist['name']!=0){$result=$result+1;}
		if($errorlist['mail']!=0){$result=$result+1;}
		if($errorlist['text']!=0){$result=$result+1;}
	return $result;
	
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// contrôle des erreurs de champs de saisie d'inscription page basketinfo.php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
function gl_mistake_sign($name,$mail,$text)
{

if($name==''){
	$errorlist['name']=1;	
}else{
	
	if(is_numeric($name)){	
		$errorlist['name']=2;
	}
	else{
		if(strlen($name)>100){
			$errorlist['name']=3;
		}else{
			$errorlist['name']=0;		
		}
	}
}
	
	

if($mail==''){
	$errorlist['mail']=1;	
}else{
	
	if(is_numeric($mail)){	
		$errorlist['mail']=2;
	}
	else{
		$pattern="#^([a-zA-Z0-9]+(([\.\-\_]?[a-zA-Z0-9]+)+)?)\@(([a-zA-Z0-9]+[\.\-\_])+[a-zA-Z]{2,4})$#";
		if (preg_match($pattern, $mail)){
			$errorlist['mail']=0;		
		}else{
			$errorlist['mail']=3;
		}
	}
}

if($text==''){
	$errorlist['text']=1;	
}else{
	
	if(is_numeric($name)){	
		$errorlist['text']=2;
	}
	else{
		if(strlen($name)>100){
			$errorlist['text']=3;
		}else{
			$errorlist['text']=0;		
		}
	}
}
		
return $errorlist;	
}






///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// génération des tags fichiers sur serveur pour optimisation de cache
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function etag_generate($file)
{
$eTag = "ci-".dechex(crc32($file.$lastModified));	
//header('ETag: "'.$eTag.'"');	
return $etag;
	
	
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// accepte un objet LibXMLError comme seul paramètre. Ensuite un switch permet de déterminer le type de l'erreur, en parcourant la sévérité de l'erreur (une des constantes suivantes : LIBXML_ERR_WARNING, LIBXML_ERR_ERROR ou LIBXML_ERR_FATAL). Lorsque le niveau est déterminé, le code source produit une chaîne qui indique le niveau approprié, en fonction du code de l'erreur.
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function libxml_display_error($error) {
  $return = "<br/>\n";
  switch ($error->level) {
     case LIBXML_ERR_WARNING:
         $return .= "<b>Warning $error->code</b>: ";
         break;
     case LIBXML_ERR_ERROR:
         $return .= "<b>Error $error->code</b>: ";
         break;
     case LIBXML_ERR_FATAL:
         $return .= "<b>Fatal Error $error->code</b>: ";
         break;
  }
  $return .= trim($error->message);
  if ($error->file) {
     $return .=    " in <b>$error->file</b>";
  }
  $return .= " on line <b>$error->line</b>\n";
   
  return $return;
}
 
function libxml_display_errors($display_errors = true) {
  $errors = libxml_get_errors();
  $chain_errors = "";
   
  foreach ($errors as $error) {
   $chain_errors .= preg_replace('/( in\ \/(.*))/', '', strip_tags(libxml_display_error($error)))."\n";
   if ($display_errors) {
     trigger_error(libxml_display_error($error), E_USER_WARNING);
   }
  }
  libxml_clear_errors();
   
  return $chain_errors;
}
 
//*********************************************************************************************
//création de chaine pour les requete sql sur IN
//*********************************************************************************************
function concat($liste_concat,$type)//NUM ou TEXTE ou EREGSQL
{
  //fonction de concaténation de chaine à partir d'une liste pour renvoi de liste dans une requete sql
  
  if($type=='NUM')// sans guillemet
  {
    $concatene="";
    for($i=0;$i<count($liste_concat);$i++)
    {
      if($i==0){
        $concatene=$liste_concat[$i];
      }
      else{
        $concatene=$concatene.','.$liste_concat[$i];
      }
    }
  }
  else if($type=='TEXTE')
  {
    $concatene="'";
    for($i=0;$i<count($liste_concat);$i++)
    {
      if($i==0){
        $concatene=$liste_concat[$i];
      }
      else{
        $concatene=$concatene."','".$liste_concat[$i];
      }
 
    }
    $concatene=$concatene."'";
  }
  else if($type=='EREGSQL')
  {
    $concatene="";
    for($i=0;$i<count($liste_concat);$i++)
    {
      if($i==0)
      {
        $concatene=$liste_concat[$i];
      }
      else
      {
        $concatene=$concatene."|".$liste_concat[$i];
      }

    }
    
  }
  
  
  else 
  {
  }
  return $concatene;
}



//*********************************************************************************************
//fonction de création du lien crypté: sur base d'un nmot de passe cryptage md5
//********************************************************************************************* 
function GenerationCle($Texte,$CleDEncryptage)
  {
  $CleDEncryptage = md5($CleDEncryptage);
  $Compteur=0;
  $VariableTemp = "";
  for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++)
    {
    if ($Compteur==strlen($CleDEncryptage))
      $Compteur=0;
    $VariableTemp.= substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1);
    $Compteur++;
    }
  return $VariableTemp;
  }

function Crypte($Texte,$Cle)
  {
  srand((double)microtime()*1000000);
  $CleDEncryptage = md5(rand(0,32000) );
  $Compteur=0;
  $VariableTemp = "";
  for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++)
    {
    if ($Compteur==strlen($CleDEncryptage))
      $Compteur=0;
    $VariableTemp.= substr($CleDEncryptage,$Compteur,1).(substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1) );
    $Compteur++;
    }
  return base64_encode(GenerationCle($VariableTemp,$Cle) );
  }

function Decrypte($Texte,$Cle)
  {
  $Texte = GenerationCle(base64_decode($Texte),$Cle);
  $VariableTemp = "";
  for ($Ctr=0;$Ctr<strlen($Texte);$Ctr++)
    {
    $md5 = substr($Texte,$Ctr,1);
    $Ctr++;
    $VariableTemp.= (substr($Texte,$Ctr,1) ^ $md5);
    }
  return $VariableTemp;
  } 


?>
