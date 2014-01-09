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
		if ($livello === $_SESSION['archimede']['livello']) {
			//ha la chiavegiusta, controlliamo la risposta
			//costruiamo la query da richiedere
			$query = "SELECT esatta FROM domande WHERE chiave=" . $_SESSION['archimede']['chiave'] ;
			//facciamo la richiesta
			$row = $db->query($query)->fetch();
			//ricordiamoci che in PHP gli elementi cominciano da zero			
			$esatta = $row[0];			
			if (isset($_POST[$esatta])){
				$_SESSION['archimede']['livello']++ ; //aumenta di uno
				unset($_SESSION['archimede']['chiave']); //cancelliamo la domanda, cosi' ne verra' proposta una nuova
				//il redirect in PHP deve essere con path assoluto, quindi meglio scrivere la seguente formula sempre valida:	
				$host  = $_SERVER['HTTP_HOST'];
				$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				$extra = 'domande.php';
				header("Location: http://$host$uri/$extra");
				} else {
				//ha perso
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