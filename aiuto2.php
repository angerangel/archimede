<?php
//utiliziamo le sessioni
session_start();
//controlliamo che sia rimasto questo aiuto
if (!$_SESSION['archimede']['cinqecinq']) {
	#lo rimandiamo alla pagina delle domande
	//il redirect in PHP deve essere con path assoluto, quindi meglio scrivere la seguente formula sempre valida:	
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'domande.php';
	header("Location: http://$host$uri/$extra");
	}

#Ha consumato l'aiuto
$_SESSION['archimede']['cinqecinq'] = false ;
//connettiamoci al database
$db = new PDO('sqlite:secret/domande.sqlite');
##costruiamo la query da richiedere
$query = "SELECT esatta FROM domande WHERE chiave=" . $_SESSION['archimede']['chiave'] ;
//facciamo la richiesta
$row = $db->query($query)->fetch();
//ricordiamoci che in PHP gli elementi cominciano da zero
$esatta = $row[0];

$_SESSION['archimede']['rispostaa'] = $_SESSION['archimede']['rispostab'] = $_SESSION['archimede']['rispostac'] = $_SESSION['archimede']['rispostad'] = true ;

switch ($esatta) {
	case "a":
		$pos = 1;
		unset($_SESSION['archimede']['rispostaa']);
		break;
	case "b":
		$pos = 2;
		unset($_SESSION['archimede']['rispostab']);
		break;
	case "c":
		$pos = 3;
		unset($_SESSION['archimede']['rispostac']);
		break;
	case "d":
		$pos = 4;
		unset($_SESSION['archimede']['rispostad']);
		break;
	}


#estraiamo a caso quella sbagliata da conservare
$conserv = $pos + rand(1,3);
if ($conserv > 4) { $conserv = $conserv - 4 ;}


switch ($conserv) {
	case 1 :		
		unset($_SESSION['archimede']['rispostaa']);
		break;
	case 2 :		
		unset($_SESSION['archimede']['rispostab']);
		break;
	case 3 :		
		unset($_SESSION['archimede']['rispostac']);
		break;
	case 4 :		
		unset($_SESSION['archimede']['rispostad']);
		break;
	}

require 'domande.php' ;

?>



