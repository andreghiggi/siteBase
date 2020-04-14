<?php 
include("../s_acessos.php");
include("../funcoes.php");

$action = mysql_real_escape_string($_POST['action']);
$updateRecordsArray = $_POST['recordsArray'];

if ($action == "updateRecordsListings")
{	
	$ind = 1;
	foreach ($updateRecordsArray as $recordIDValue)
	{	
		$codigo = $recordIDValue;

		$str = "UPDATE banners SET ordem = '$ind' WHERE codigo = '$codigo'";
        $rs  = mysql_query($str) or die(mysql_error());	

		$ind = $ind + 1;	
	}

	if($ind == (count($updateRecordsArray) + 1))
	{
		echo 'Banners ordenados com sucesso!';
	}
}
?>