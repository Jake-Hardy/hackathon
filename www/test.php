<?php
#$SKIP_SESSION = 1; // Tell init to skip session check
require_once('lib/init.php');
//include('includes/header.html');
/*
$pubKey = openssl_get_publickey('file://../public.pem');
openssl_public_encrypt('101-99-1237',$crypt,$pubKey);
echo bin2hex($crypt).'<br>';
echo strlen(bin2hex($crypt)).'<br>';
openssl_free_key($pubKey);

$pKey = openssl_get_privatekey('file://../private.pem');
openssl_private_decrypt($crypt,$clear,$pKey);
echo $clear.'<br>';
openssl_free_key($pKey);

echo 'mhash test<br>';
$hash = mhash(MHASH_SHA256, $clear, '2007');
echo "The 256 hash is ".strlen(bin2hex($hash))." long - " . bin2hex($hash) . "<br />\n";
$hash = mhash(MHASH_SHA512, $clear, '2007');
echo "The 512 hash is ".strlen(bin2hex($hash))." long - " . bin2hex($hash) . "<br />\n";
$hash = mhash(MHASH_SHA1, $clear, '2007');
echo "The 160 hash is ".strlen(bin2hex($hash))." long - " . bin2hex($hash) . "<br />\n";
$one = genHash('uwags!70');
echo "genHash size is ".strlen($one)." long - ".$one.'<br>';
echo date('Y');
*/

//echo phpinfo();
//exit;
//include('includes/footer.html');

//define('PUB_KEY', 'file:///var/www/vhosts/csrachristmas.org/public.pem');
$pubKey = openssl_pkey_get_public(file_get_contents(PUB_KEY));
openssl_public_encrypt($clear,$crypt,$pubKey);
openssl_free_key($pubKey);
//return bin2hex($crypt);

?> 
