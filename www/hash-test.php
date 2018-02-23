<?php

	// hex2bin function by chaos79 taken from http://php.net/manual/en/function.bin2hex.php
	function hex2bin($h) {
		if (!is_string($h)) return null;
		$r = '';
		for ($a = 0; $a < strlen($h); $a += 2) {
			$r .= chr(hexdec($h{$a}.$h{($a + 1)}));
		}
		return $r;
	}

	$plainText = '253517877';
	$salt = '9Dj2a1x3He'; 
	//$hash = mhash(MHASH_SHA256,$plainText,$salt);
	$hash = hash('sha256',$plainText.$salt);
	
	echo $plainText;
	echo "<br/>";
	echo "HASH: ".$hash; 		
	echo "<br/>";
	echo "BIN2HEX(HASH): ".bin2hex($hash); 		
	echo "<br/>";
	echo "HEX2BIN(HASH): ".hex2bin($hash);

	echo "<hr/>";
	$hash2 = hash_hmac('sha256',$plainText,$salt);
	echo "HEX2BIN(HASH): ".hex2bin($hash2);
	echo "<br/>";
	echo "BIN2HEX(HASH): ".bin2hex(hex2bin($hash2)); 		


?> 
