<?php
//utiliziamo le sessioni
session_start();


//controlliamo che non sia un hacker
if (empty($_SESSION['archimede']['nome']) or empty($_SESSION['archimede']['livello']) or $_SESSION['archimede']['livello'] < 1 or $_SESSION['archimede']['livello'] > 15 ) {
	// e' un hacker, cancelliamo la sessione e lo rimandiamo alla pagina iniziale (index.php)	
	require 'espulso.php' ;
	} else {
	//connettiamoci al database
	$db = new PDO('sqlite:secret/domande.sqlite');
	//controlliamo se ha gia' la domanda ed e' del livello corretto
	if (!empty($_SESSION['archimede']['chiave'])) {		
		//ha la chiave, vediamo il livello corrisposndente
		//costruiamo la query da richiedere
		$query = "SELECT livello FROM domande WHERE chiave=" . $_SESSION['archimede']['chiave'] ;
		//facciamo la richiesta
		$row = $db->query($query)->fetch();
		//ricordiamoci che in PHP gli elementi cominciano da zero
		$livello = $row[0];		
		if ($livello == $_SESSION['archimede']['livello']) {
			//ha la chiave giusta, gli riproponiamo la stssa domanda
			//costruiamo la query da richiedere
			$query = "SELECT chiave,domanda,a,b,c,d,esatta FROM domande WHERE chiave=" . $_SESSION['archimede']['chiave'] ;
			//facciamo la richiesta
			$row = $db->query($query)->fetch();
			//ricordiamoci che in PHP gli elementi cominciano da zero			
			//tasformiaro i caratteri orblmetici dell'HTML
			$domanda = htmlspecialchars($row[1]);
			$a = htmlspecialchars($row[2]);
			$b = htmlspecialchars($row[3]);
			$c = htmlspecialchars($row[4]);
			$d = htmlspecialchars($row[5]);
			$esatta = $row[6];
			//mettiamo i valri importanti nella sessione			
			$_SESSION['archimede']['esatta']= $esatta;				
			} else {						
			require 'espulso.php' ;
			}		
		} else {	
		//non ha la chiave, nuova domanda
		//costruiamo la query da richiedere
		$query = "SELECT chiave,domanda,a,b,c,d,esatta FROM domande WHERE livello=" . $_SESSION['archimede']['livello'] . " ORDER BY RANDOM() LIMIT 1";
		//facciamo la richiesta
		$row = $db->query($query)->fetch();
		//ricordiamoci che in PHP gli elementi cominciano da zero
		$chiave = $row[0];
		//tasformiaro i caratteri orblmetici dell'HTML
		$domanda = htmlspecialchars($row[1]);
		$a = htmlspecialchars($row[2]);
		$b = htmlspecialchars($row[3]);
		$c = htmlspecialchars($row[4]);
		$d = htmlspecialchars($row[5]);
		$esatta = $row[6];
		//mettiamo i valri importanti nella sessione
		$_SESSION['archimede']['chiave']= $chiave;
		$_SESSION['archimede']['esatta']= $esatta;		
		}
	}	
?>
<div align=center>
<h1>Domanda <?php echo $_SESSION['archimede']['livello'];?></h1>
<h2><?php echo $domanda; ?></h2>
<hr>
<form action=check.php method=post >
<table>
  <tr>
    <td align=right ><?php 
    if (!isset($rispostaa)) {
	echo "<input  type=submit name=a value=\"$a\">"; 
	} 
	?></td>
    <td align=left ><?php 
    if (!isset($rispostab)) {
	echo "<input  type=submit name=b value=\"$b\">"; 
	}?></td>
  </tr>
  <tr>
    <td align=right><?php 
    if (!isset($rispostac)) {
	echo "<input  type=submit name=c value=\"$c\">"; 
	}?></td>
    <td align=left><?php 
    if (!isset($rispostad)) {
	echo "<input  type=submit name=d value=\"$d\">"; 
	}
	?></td>
  </tr>
</table>
</form>
<hr>
<?php
#qui mettiamo gli aiuti
if ($_SESSION['archimede']['dacasa'] or  $_SESSION['archimede']['cinqecinq'] or $_SESSION['archimede']['pubblico'] ){
	echo "<h2>AIUTI DISPONIBILI</h2>";
	if ($_SESSION['archimede']['dacasa']) {
		echo "<a href=aiuto1.php>Chiedi al computer</a><br>";
		}
	if ($_SESSION['archimede']['cinqecinq']) {
		echo "<br> <a href=aiuto2.php>Elimina due domande errate</a> <br>";
		}
	if ($_SESSION['archimede']['pubblico']) {
		echo "<br><a href=aiuto3.php>Sondaggio</a>";
		}
	}
?>
</div>