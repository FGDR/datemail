<?php
//*********************************************************************************************
//*********************************************************************************************
// Fonction SQL
//*********************************************************************************************
/*********************************************************************************************
EXEMPLES
//*********************************************************************************************
//selection d'un ligne de table
//*********************************************************************************************
function obtenir_lib_prix_abo_admin($db) 
{
	$sql = "SELECT count(*) FROM ref_tarif, article, ref_typearticle
	WHERE fkid_tarif = id_tarif
	AND fkidref_typearticle = idref_typearticle
	";
	return select($db, $sql);
}
//*********************************************************************************************
//selection de plusieurs lignes
//*********************************************************************************************
function obtenir_lib_prix_abo_admin($db) 
{
	$sql = "SELECT idarticle, lib_tarif, val_tarif FROM ref_tarif, article, ref_typearticle
	WHERE fkid_tarif = id_tarif
	AND fkidref_typearticle = idref_typearticle
	";
	return select_list($db, $sql);
}

//*********************************************************************************************
//selection d'un count
//*********************************************************************************************
function select_count_annonce($db,$sql)
{
	$result=select($db, $sql);
	return $result['COUNT(*)'];
}
//*********************************************************************************************
//creation d'un enregistrement en transacsql
//*********************************************************************************************
function updateimageannonce($db,$idannonce,$file)
{
     $sql="UPDATE annonce set lib_img1='".$idannonce."_a.jpg' WHERE idannonce='".$idannonce."'";
     return update($db,$sql);
	 ou
	 return insert

}*/

//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
//*********************************************************************************************
///////////////////////////////////////////////////////////////////////////////
//		Incrément et Récuperation du dernier identifiant d'une table 	
//
// c'est pas obligatoire, mais vaut mieux y penser pour profiter pleinement de l'abstraction InnoDB
//
////////////////////////////////////////////////////////////////////////////
function getSuivantID($db, $table)
{
	//echo $table;
	$sql="SELECT ID_SUIVANT_ID FROM SUIVANT_ID WHERE LIB_TABLE='".$table."'";
	//Print_r($sql);
	$rslt=select($db, $sql);
	//Print_r($rslt);
	$valmax=$rslt['ID_SUIVANT_ID'];
	//echo 'la derniere id est'.$valmax;
	$valmax++;
	//echo 'la nouvelle id est'.$valmax;
	$db->StartTrans();
	$sqlte = "UPDATE SUIVANT_ID SET ID_SUIVANT_ID=".$valmax." WHERE LIB_TABLE='".$table."'";
	$rste = $db->Execute ($sqlte);
	$db->CompleteTrans();
	//echo 'la nouvelle id a ete enregistrée';
	return $valmax;			
		
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// fonction générale d'effacement
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function delete($db,$sql)
{
	$db->StartTrans();
        $db->execute($sql);
        if($db->CompleteTrans()){ 
             $Result=$id;
        }else{ 
             $Result="SQL_ERREUR";
        }
        return $Result;
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// fonction générale d'enregistrement
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function insert($db,$sql)
{
	$db->StartTrans();
        $db->execute($sql);
        if($db->CompleteTrans()){ 
             $Result=$id;
        }else{ 
             $Result="SQL_ERREUR";
        }
        return $Result;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// fonction générale de mise à jour
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function update($db,$sql)
{
	$db->StartTrans();
        $db->execute($sql);
        if($db->CompleteTrans()){ 
             $Result=$id;
        }else{ 
             $Result="SQL_ERREUR";
        }
        return $Result;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// fonction de concaténations des conditions sql type where
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function dl_concatsqlwhere($sqlwhere)
{
	$sqlconcat="WHERE ";
	for ($i=0;$i<count($sqlwhere);$i++)
	{
		if ($i<count($sqlwhere)-1){
			$sqlconcat=$sqlconcat.$sqlwhere[$i]." AND ";}
		else{
			$sqlconcat=$sqlconcat.$sqlwhere[$i]." ";}		
	}
	if($sqlconcat=="WHERE "){
		return "";
	}else{
	return $sqlconcat;
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// retour d'une valeur unique
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function select(&$db, $sql, $UseDateAndTime=true)
{
	$rs = $db->Execute($sql);
	if (!$rs){
		return false;
	}
	if ($rs->EOF){
	    return array();
	}
	$dtfld = array();
	for ($i=0; $i<$rs->FieldCount(); $i++)
	{
		$fld = $rs->FetchField($i);
		$type = $rs->MetaType($fld->type);
		if (($type == 'D') || ($type == 'T')){
			$dtfld[$fld->name] = $i;
		}
	}

	$dtfld = array_change_key_case($dtfld, CASE_UPPER);

	$row = $rs->GetRowAssoc();
	foreach ($dtfld as $k => $a)
	{
		if ($UseDateAndTime){
			$row[$k] = $rs->UserTimeStamp($rs->fields[$a], 'd/m/Y H:i:s');
		}else{
			$row[$k] = $rs->UserDate($rs->fields[$a], 'd/m/Y');
		}
	}
	
	return $row;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// retour de valeurs multiples
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function select_list(&$db, $sql, $UseDateAndTime=true)
{
	$rs = $db->Execute($sql);
	if (!$rs){
		return array(); 
	}
	
	if ($rs->EOF){
	    return array();
	}
	$dtfld = array();
	for ($i=0; $i<$rs->FieldCount(); $i++)
	{
		$fld = $rs->FetchField($i);
		$type = $rs->MetaType($fld->type);
		if (($type == 'D') || ($type == 'T')){
			$dtfld[$fld->name] = $i;
		}
	}

	$dtfld = array_change_key_case($dtfld, CASE_UPPER);

	$rows = array();
	while(!$rs->EOF)
	{
		$row = $rs->GetRowAssoc();
		foreach ($dtfld as $k => $a)
		{
			if ($UseDateAndTime){
				$row[$k] = $rs->UserTimeStamp($rs->fields[$a], 'd/m/Y H:i:s');
			}else{
				$row[$k] = $rs->UserDate($rs->fields[$a], 'd/m/Y');
			}
		}
		
		$rows[count($rows)] = $row;
		$rs->MoveNext();
	} // while

	return $rows;
}
?>
