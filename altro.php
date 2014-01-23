<?php
#put your custom contente here
require_once 'detectmobile.php' ;
if ($mobile) {
	echo "<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
		<!-- archimede3 -->
		<ins class=\"adsbygoogle\"
		style=\"display:inline-block;width:320px;height:50px\"
		data-ad-client=\"ca-pub-9291179133147047\"
		data-ad-slot=\"8965379764\"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
	";
	} else {
	echo "<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
		<!-- archimede -->
		<ins class=\"adsbygoogle\"
		style=\"display:inline-block;width:728px;height:90px\"
		data-ad-client=\"ca-pub-9291179133147047\"
		data-ad-slot=\"7727808963\"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>";
	}
?>


