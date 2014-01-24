<?php
//utiliziamo le sessioni
session_start();

#eliminiamo le variabili  di sessione create da aiuto2.php
unset($_SESSION['archimede']['rispostaa'], $_SESSION['archimede']['rispostab'],  $_SESSION['archimede']['rispostac'] , $_SESSION['archimede']['rispostad'] ) ;

#controllo mobile
require_once 'detectmobile.php' ;

//controlliamo se ha gia' la domanda 
	if (!empty($_SESSION['archimede']['chiave'])) {	
		#vediamo se ci sta provando
		$test = 0 ;		
		if (isset($_POST['a'])) { $test++;}
		if (isset($_POST['b'])) { $test++;}
		if (isset($_POST['c'])) { $test++;}
		if (isset($_POST['d'])) { $test++;}
		if ($test > 1) { require 'espulso.php' ;}
		
		//ha la chiave
		//db connect
		$db = new PDO('sqlite:secret/domande.sqlite');
		//controlliamo la risposta
		//costruiamo la query da richiedere
		$query = "SELECT esatta FROM domande WHERE chiave=" . $_SESSION['archimede']['chiave'] ;
		//facciamo la richiesta
		$row = $db->query($query)->fetch();
		//ricordiamoci che in PHP gli elementi cominciano da zero			
		$esatta = $row[0];			
		if (isset($_POST[$esatta])){
			#risposto esttamente
			$_SESSION['archimede']['livello']++ ; //aumenta di uno
			if ($_SESSION['archimede']['livello'] == 16) {
				#ha vinto
				#registriamo la il puntaggio
				$db = new PDO('sqlite:punteggi.sqlite'); #connessione
				#il nome potrebbe contenere apostrofi (che vanno raddoppiati in SQLite) e caratteri strani, meglio provvedere:
				$nome = htmlspecialchars($_SESSION['archimede']['nome']);
				$nome = str_replace("'","''", $nome);
				#limitiamo il nome a 30 caratteri, così limitiamo la pubblicita'
				$nome = substr($nome, 0, 30);
				$query = "INSERT INTO punteggi (nome, punteggio, giorno ) VALUES ('$nome',". $_SESSION['archimede']['livello'] .", date('now') ) ";
				$db->query($query);		
				$nome = strtoupper($nome);
				echo "<div " ;				
				if (!$mobile) {echo " align=center ";}
				echo "><h1>HAI VINTO!</h1>
				<h2>CONGRATULAZIONI $nome</h2>
				Hai superato 15 difficilissime domande! Il tuo nome e' memorizzato nel nella lista dei vincitori!
				<form action=punteggi.php method=get >
				<input type=submit namme=submit value=Punteggi >
				</form>
				</div>
				";
				unset($_SESSION['archimede']);	
				} else {
				#ancora non ha vinto, bisogna proporgli un'altra domanda
				unset($_SESSION['archimede']['chiave']); //cancelliamo la domanda, cosi' ne verra' proposta una nuova
				//il redirect in PHP deve essere con path assoluto, quindi meglio scrivere la seguente formula sempre valida:	
				$host  = $_SERVER['HTTP_HOST'];
				$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				$extra = 'domande.php';
				header("Location: http://$host$uri/$extra");
				}
			} else {
			//ha perso				
			#registriamo la il puntaggio
			#vediamo se ha risposto almento ad una domanda:
			if ($_SESSION['archimede']['livello'] > 1){
				$livello = $_SESSION['archimede']['livello'] - 1 ;
				$db = new PDO('sqlite:punteggi.sqlite'); #connessione
				#il nome potrebbe contenere apostrofi (che vanno raddoppiati in SQLite) e caratteri strani, meglio provvedere:
				$nome = htmlspecialchars($_SESSION['archimede']['nome']);
				$nome = str_replace("'","''", $nome);
				#limitiamo il nome a 30 caratteri, così limitiamo la pubblicita'
				$nome = substr($nome, 0, 30);				
				$query = "INSERT INTO punteggi (nome, punteggio, giorno ) VALUES ('$nome',$livello, date('now') ) ";
				$db->query($query);				
				echo "<div " ;				
				if (!$mobile) {echo " align=center ";}
				echo ">
					<h1>HAI SBAGLIATO!</h1>
					Il tuo punteggio e' di $livello. <br>
					Non ti abbattere, la prossima volta andra' meglio.
					<form action=espulso.php method=get >
					<input type=submit namme=submit value=\"Ricomincia!\" >
					</form>
					<br>
					<a href=punteggi.php >Punteggi</a>
					</div>
					";
				} else {
				#non ha preso neanche una domanda
				echo "<div " ;				
				if (!$mobile) {echo " align=center ";}
				echo ">
					<h1>HAI SBAGLIATO!</h1>						
					Non ti abbattere, la prossima volta andra' meglio.
					<form action=espulso.php method=get >
					<input type=submit namme=submit value=\"Ricomincia!\" >
					</form>
					</div>";
				}				
			unset($_SESSION['archimede']); #in ogni caso bisogna cancellare la sessione
			}
		} else {	
		//non ha la chiave, espelliamolo
		 require 'espulso.php' ;
		}

?>