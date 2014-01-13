<?php
//utiliziamo le sessioni
session_start();

//controlliamo se ha gia' la domanda ed e' del livello corretto
	if (!empty($_SESSION['archimede']['chiave'])) {		
		//ha la chiave, vediamo il livello corrisposndente
		//db connect
		$db = new PDO('sqlite:secret/domande.sqlite');
		//costruiamo la query da richiedere
		$query = "SELECT livello FROM domande WHERE chiave=" . $_SESSION['archimede']['chiave'] ;
		//facciamo la richiesta
		$row = $db->query($query)->fetch();
		//ricordiamoci che in PHP gli elementi cominciano da zero
		$livello = $row[0];		
		if ($livello == $_SESSION['archimede']['livello']) {
			//ha la chiavegiusta, controlliamo la risposta
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
					echo "
					<div align=center>
					<h1>HAI VINTO!</h1>
					<h2>CONGRATULAZIONI $nome</h2>
					Hai superato 15 difficilissime domande! Il tuo nome e' memorizzato nel nella lista dei vincitori!
					<form action=punteggi.php method=get >
					<input type=submit namme=submit value=Punteggi >
					</form>
					</div>
					";
					unset($_SESSION['archimede']);	
					} else {
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
					$livello = $_SESSION['archimede']['livello'];
					echo "
						<div align=center>
						<h1>HAI SBAGLIATO!</h1>
						Il tuo punteggio e' di $livello. <br>
						Non ti abbattere, la prossima volta andra' meglio.
						<form action=espulso.php method=get >
						<input type=submit namme=submit value=\"Ricomincia!\" >
						</form>
						</div>
						";
					} else {
					#non ha preso neanche una domanda
					echo "
						<div align=center>
						<h1>HAI SBAGLIATO!</h1>						
						Non ti abbattere, la prossima volta andra' meglio.
						<form action=espulso.php method=get >
						<input type=submit namme=submit value=\"Ricomincia!\" >
						</form>
						</div>";
					}
				unset($_SESSION['archimede']);	
				}
			
			} else {
			//chiave non corrisponde al livello, sta cercando di hackerare il sito
			require 'espulso.php' ;
			}
		
		} else {	
		//non ha la chiave, espelliamolo
		 require 'espulso.php' ;
		}

?>