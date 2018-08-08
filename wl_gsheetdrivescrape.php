<?php
	include('./snoopy/Snoopy.class.php');
	
	$snoopy = new Snoopy;
	
	$snoopy->agent = "(Windows NT 6.1; WOW64; Trident/7.0; AS; rv:11.0)";
	$snoopy->referer = "http://www.weblibre.net/";
	
	$baseGdriveFolder = "https://drive.google.com/open?id=";
	$gdriveId = "1OPkynMvIV_urb_9Uv5BdCPAXYelemteJ";
	// get DOM from URL or file
	$uri = $baseGdriveFolder.$gdriveId;
	
	$snoopy->fetchlinks($uri);
	print_r($snoopy->results);
?>