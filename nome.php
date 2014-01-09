<?php
//utiliziamo le sessioni
session_start();
//controlliamo che ci sia il nome
if (isset($_POST['submit'])  and !empty($_POST['nome'])) {
	//impostiamo il nome
	$_SESSION['archimede']['nome']= $_POST['nome'];	
	//impostiamo il livello
	$_SESSION['archimede']['livello']= 1 ;
	//lo mandiamo alla pagine delle domande
	
	} else {
	// e' un hacker, cancelliamo la sessione e lo rimandiamo alla pagina iniziale (index.php)
	 unset($_SESSION['archimede'])
	//il redirect in PHP deve essere con path assoluto, quindi meglio scrivere la seguente formula sempre valida:	
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'index.php';
	header("Location: http://$host$uri/$extra");
	}