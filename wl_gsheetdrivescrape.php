<?php
	include('./simplehtmldom/simple_html_dom.php');
	
	$baseGdriveFolder = "https://drive.google.com/open?id=";
	$gdriveId = "1OPkynMvIV_urb_9Uv5BdCPAXYelemteJ";
	// get DOM from URL or file
	$html = file_get_html($baseGdriveFolder.$gdriveId);
	
	foreach($html->find('script') as $e)
    echo $e->outertext ."<br>";
?>