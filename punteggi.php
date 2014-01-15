<div align=center>
<h1>PUNTEGGI MIGLIORI</h1>
<a href=index.php >Torna indietro</a>
<table width=100% cellspacing=0 >
<tr>
	<th align=center>Punteggio</th>
	<th align=center>Nome</th>
	<th align=center>Data</th>
</tr>
<?php
//qui dovrebbero esserci tutti i punteggi realizzati
#db connect
$db = new PDO('sqlite:punteggi.sqlite');
$query = "SELECT punteggio, nome, giorno FROM punteggi ORDER BY punteggio DESC,giorno,nome  ";

$contalinee = 0;
$primo = 0;
$secondo = 0 ;
foreach ($db->query($query) as $row) {
	$punteggio = $row['punteggio'];
	$nome = $row['nome'];
	$giorno = $row['giorno'];
	$contalinee += 1 ;
	if ($contalinee % 2 == 0) { 
		$terzo = 255 ;
		} else {
		$terzo = 200 ;
		}
	
	echo "<tr style=\"background-color:rgb(255,255,$terzo)\" >
		<td align=center>$punteggio</td>
		<td align=center>$nome</td>
		<td align=center>$giorno</td>
		</tr>
	";        
    }
?>
</table>


</div>