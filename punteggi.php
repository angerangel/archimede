<?PHP
#controllo mobile
require_once 'detectmobile.php' ;
?>
<div 
<?php  if (!$mobile) {echo "align=center";}?>
>
<h1>PUNTEGGI MIGLIORI</h1>
<a href=index.php >Torna indietro</a>
<table cellspacing=0 >
<tr>
	<th align=center>Punteggio</th>
	<th align=center>Nome</th>
	<th align=center>Data</th>
</tr>
<?php
//qui dovrebbero esserci tutti i punteggi realizzati
#db connect
$db = new PDO('sqlite:punteggi.sqlite');
$query = "SELECT DISTINCT max(punteggio), nome, giorno FROM punteggi GROUP BY nome ORDER BY max(punteggio) DESC,giorno DESC,nome  ";

$contalinee = 0;
$primo = 0;
$secondo = 0 ;
foreach ($db->query($query) as $row) {
	$punteggio = $row[0];
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

Partite giocate: 
<?php
$query = "SELECT max(rowid) FROM punteggi;" ;
$db->query($query) ;
$row = $db->query($query)->fetch();
$row = $row[0];
echo $row;
?>
</div>