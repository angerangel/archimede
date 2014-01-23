<?php
//utiliziamo le sessioni
require_once 'sessione.php' ; 
?>
<h1>Pagina amministrazione Archimede</h1>

<?php
#carichiamo gli user e password
require 'secret/admins.php';

if (isset($_POST['nome']) and ($_POST['password'] === $admins[$_POST['nome']]  ) ) {
	echo "
	<h2>Aggiungi domande</h2>";
	#verifichiamo se ha mandato una query e tutti gli elementi hanno un valore
	if ( isset($_POST['livello']) and !empty($_POST['livello']) and 
		isset($_POST['domanda']) and !empty($_POST['domanda']) and
		isset($_POST['a']) and !empty($_POST['a']) and
		isset($_POST['b']) and !empty($_POST['b']) and
		isset($_POST['c']) and !empty($_POST['c']) and
		isset($_POST['d']) and !empty($_POST['d']) and
		isset($_POST['esatta']) and !empty($_POST['esatta']) 
		) {
			#facciamo una funzione per rasformare le sttringhe inqualcosa di digeribile er SQLite
			function perSQLite ( $temp) {
				#trasformiamo i carattri strani per HTML
				$temp = htmlspecialchars($temp);
				#raddoppiamo gli eventuali apostrofi, come richiede la sintassi SQLite per le stringhe
				$temp = str_replace("'","''",$temp);
				return $temp ;
				}
			
			$livello = $_POST['livello'];
			$domanda = perSQLite($_POST['domanda']) ;
			$a = perSQLite($_POST['a']) ;
			$b = perSQLite($_POST['b']) ;
			$c = perSQLite($_POST['c']) ;
			$d = perSQLite($_POST['d']) ;
			$esatta = $_POST['esatta'] ;
			//connettiamoci al database
			$db = new PDO('sqlite:secret/domande.sqlite');
			#costruiamo la query
			$query = "INSERT INTO domande (livello,domanda,a,b,c,d,esatta) VALUES ($livello,'$domanda','$a','$b','$c','$d','$esatta')";
			#eseguiamo la query
			$db->query($query);
			#debug 
			echo "Hai inserito la seguente domanda: <b><i>$domanda</i></b><br>";
			}

	echo "<form action=admin.php method=post>
	<input type=hidden name=nome value=" . $_POST['nome'] . ">
	<input type=hidden name=password value=" . $_POST['password'] . ">
	<table border=1 >
		<tr><th>Livello di difficolta'</th><th>Domanda</th><th>A</th><th>B</th><th>C</th><th>D</th><th>Esatta <br><small>(mettere a,b,c o d)</small></th></tr>
		<tr>
			<td>
			<select name=livello>
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
				<option value=4>4</option>
				<option value=5>5</option>
				<option value=6>6</option>
				<option value=7>7</option>
				<option value=8>8</option>
				<option value=9>9</option>
				<option value=10>10</option>
				<option value=11>11</option>
				<option value=12>12</option>
				<option value=13>13</option>
				<option value=14>14</option>
				<option value=15>15</option>
			</select> 
			</td>
			<td><textarea required name=domanda ></textarea></td>
			<td><textarea required name=a ></textarea></td>
			<td><textarea required name=b ></textarea></td>
			<td><textarea required name=c ></textarea></td>
			<td><textarea required name=d ></textarea></td>
			<td>
			<select name=esatta>
				<option value=a>A</option>
				<option value=b>B</option>
				<option value=c>C</option>
				<option value=d>D</option>				
			</select> 
			</td>
		</tr>	
	</table>
	<input type=submit >
	</form>
	";
	} else {
	echo "
	<h2>Login</h2>
	<form action=admin.php method=post>
	Nome: <input type=text name=nome><br>
	Password: <input type=password name=password ><br>
	<input type=submit >
	</form>";
	}

?>