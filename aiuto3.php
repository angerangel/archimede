<?php
//utiliziamo le sessioni
session_start();
//controlliamo che sia rimasto questo aiuto
if (!$_SESSION['archimede']['pubblico']) {
	#lo rimandiamo alla pagina delle domande
	//il redirect in PHP deve essere con path assoluto, quindi meglio scrivere la seguente formula sempre valida:	
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'domande.php';
	header("Location: http://$host$uri/$extra");
	}

#Ha consumato l'aiuto
$_SESSION['archimede']['pubblico'] = false ;
//connettiamoci al database
$db = new PDO('sqlite:secret/domande.sqlite');
##costruiamo la query da richiedere
$query = "SELECT esatta FROM domande WHERE chiave=" . $_SESSION['archimede']['chiave'] ;
//facciamo la richiesta
$row = $db->query($query)->fetch();
//ricordiamoci che in PHP gli elementi cominciano da zero
$esatta = $row[0];

$sondaggio[1] = $sondaggio[2] = $sondaggio[3] = $sondaggio[4] = 0 ;

#piu' e' facile la domanda piu' il risultato vertera' sulla risposta esatta

switch ($esatta) {
	case "a":
		$sondaggio[1] = 4 * (15 - $_SESSION['archimede']['livello'])  ;
		break;
	case "b":
		$sondaggio[2] = 4 * (15 - $_SESSION['archimede']['livello']);
		break;
	case "c":
		$sondaggio[3] = 4 * (15 - $_SESSION['archimede']['livello']);
		break;
	case "d":
		$sondaggio[4] = 4 * (15 - $_SESSION['archimede']['livello']);
		break;
	}

foreach ($sondaggio as &$temp) { #notare la finezza della & per passare la variabile per riferimento e non per valore
	$totale = 100 - ( $sondaggio[1] + $sondaggio[2] + $sondaggio[3] + $sondaggio[4])  ;
	$corr = rand(1,$totale);
	$temp = $temp + $corr;
	}
unset($temp); #da usare sempre dopo un foreach

$indecisi = 100 - ( $sondaggio[1] + $sondaggio[2] + $sondaggio[3] + $sondaggio[4])  ;

switch ($esatta) {
	case "a":
		$sondaggio[1] += $indecisi ;
		break;
	case "b":
		$sondaggio[2] += $indecisi ;
		break;
	case "c":
		$sondaggio[3] += $indecisi ;
		break;
	case "d":
		$sondaggio[4] += $indecisi ;
		break;
	}


$immagineA = ImageCreate( $sondaggio[1], 20);
$blu = imageColorAllocate($immagineA, 0,0,255);
ImageFill($immagineA,0,0,$blu);
ImageJPEG($immagineA, 'sondaggioA.jpg');
$immagineB = ImageCreate($sondaggio[2],20);
$blu = imageColorAllocate($immagineB, 0,0,255);
ImageFill($immagineB,0,0,$blu);
ImageJPEG($immagineB, 'sondaggioB.jpg');
$immagineC = ImageCreate($sondaggio[3],20);
$blu = imageColorAllocate($immagineC, 0,0,255);
ImageFill($immagineC,0,0,$blu);
ImageJPEG($immagineC, 'sondaggioC.jpg');
$immagineD = ImageCreate($sondaggio[4],20);
$blu = imageColorAllocate($immagineD, 0,0,255);
ImageFill($immagineD,0,0,$blu);
ImageJPEG($immagineD, 'sondaggioD.jpg');

#controllo mobile
require_once 'detectmobile.php' ;
?>
<DIV  <?php  if (!$mobile) {echo "align=center";}?> >
<h2>RISULTATO SONDAGGIO</H2>
<table>
<tr><td>Riposta A:</td><td><img src=sondaggioA.jpg ></td><td><?php echo $sondaggio[1] ?>%</td></tr>
<tr><td>Riposta B:</td><td><img src=sondaggioB.jpg ></td><td><?php echo $sondaggio[2] ?>%</td></tr>
<tr><td>Riposta C:</td><td><img src=sondaggioC.jpg ></td><td><?php echo $sondaggio[3] ?>%</td></tr>
<tr><td>Riposta D:</td><td><img src=sondaggioD.jpg ></td><td><?php echo $sondaggio[4] ?>%</td></tr>
</table>
</div>

<?php
require 'domande.php' ;

?>



