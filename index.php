<?php
#questa deve essere la pagina di avvio
#Tutti i valori duraturi nel temp saranno memorizzati in una sessione
//Prossimamente metteremo un controllo per vedere se la partita era conclusa o riprenderla dal momento in cui si era interrotta
# controllo mobile
require_once 'detectmobile.php' ;
?>
<div <?php  if (!$mobile) {echo "align=center";}?> >
<h1>ARCHIMEDE</h1>

Benvenuto su Archimede, il gioco di intelligenza che ti mettera' a dura prova. Riuscirai a superare tutte e 15 le domande?<br>
Per prima cosa ci serve sapere il tuo nome:
<form action=nome.php method=post >
<input type=text name=nome >
<br>
<input type=submit name=submit value="Comincia!" >
</form>
<hr>
<a href=punteggi.php ><b>Punteggi migliori</b></a>
<br> - <br>
<a href=https://github.com/angerangel/archimede >Codice sorgente</a> - <a href=mailto:angerangel@gmail.com >Contattaci</a>
<hr>
<!-- qui potete mettere altre aggiunte-->
<?php require 'altro.php' ; ?>
</div>