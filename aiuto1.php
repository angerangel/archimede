<?php
//utiliziamo le sessioni
session_start();
//controlliamo che sia rimasto questo aiuto
if (!$_SESSION['archimede']['dacasa']) {
	#lo rimandiamo alla pagina delle domande
	//il redirect in PHP deve essere con path assoluto, quindi meglio scrivere la seguente formula sempre valida:	
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'domande.php';
	header("Location: http://$host$uri/$extra");
	}

#Ha consumato l'aiuto
$_SESSION['archimede']['dacasa'] = false ;
//connettiamoci al database
$db = new PDO('sqlite:secret/domande.sqlite');
##costruiamo la query da richiedere
$query = "SELECT esatta FROM domande WHERE chiave=" . $_SESSION['archimede']['chiave'] ;
//facciamo la richiesta
$row = $db->query($query)->fetch();
//ricordiamoci che in PHP gli elementi cominciano da zero
$esatta = $row[0];

#fingiamo che il computer abbia una cultura casuale sull'argomento
$cultura = rand(1, 100);
$risposte[0]= "a";
$risposte[1]= "b";
$risposte[2]= "c";
$risposte[3]= "d";
$risposte[4]= $esatta;

#1 a 25 non la sa, e tira ad indovinare
if ($cultura <= 25) {
$consiglio = rand(0,3);
$frase = "...non so... la risposta mi sembra ..." . $risposte[$consiglio] ;
}

#da 26 a 50  ha il 50% di ndovinare
if ($cultura <= 50 and $cultura > 25) {
$test = rand(1,100);
if ($test <= 50 ) {
	#non la sa e tenta di indovinare	
	$consiglio = rand(0,3);
	} else {
	#la sa
	$consiglio = 4;
	}
$frase = "...forse... potrebbe esser la..." . $risposte[$consiglio] ;
}

#Da 50 a 75 ha il 75% di indovinare
if ($cultura <= 75 and $cultura > 50) {
$test = rand(1,100);
if ($test <= 75 ) {
	# la sa
	$consiglio = 4;
	} else {
	#non la sa
	#non la sa e tenta di indovinare	
	$consiglio = rand(0,3);
	}
$frase = "...fammi pensare... credo sia la " . $risposte[$consiglio] ;
}

if ( $cultura > 75) {
$frase = "...si... dovrebbe essere la " . $esatta ;
}
?>
<div align=center>
<h2>	IL RESPONSO DEL COMPUTER E':</H2>
<tt>"<?php echo $frase;?>"</tt>
</div>
<?php require 'domande.php' ;?>

