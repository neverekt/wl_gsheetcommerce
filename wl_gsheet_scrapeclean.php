<?php
//header("Content-Type: application/json");
$baseUrl = 'https://spreadsheets.google.com/feeds/list/';
$idGsheet = '1xvmcXGErWGQigpjVkXJrq4kD2lw6hXWrBX6JNSY26v8';
$endUrl = '/od6/public/values?alt=json';
$appName = 'PreciosFaisanStore';
$appUri = 'https://www.appsheet.com/template/gettablefileurl?appName='.$appName.'-643368&tableName=Inventario&fileName=';
$sheetUri = $baseUrl.$idGsheet.$endUrl;
$file= file_get_contents($sheetUri);
$json = json_decode($file);
$rows = $json->{'feed'}->{'entry'};
$arr = array();

function url($url) {
   $url = utf8_encode($url);
   $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
   $url = trim($url, "-");
   $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
   $url = strtolower($url);
   $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
   return $url;
}

foreach($rows as $row) {
	$countImg = 0;
	while($countImg <= 9) {
		$fotoNum = 'gsx$foto'.$countImg;
		if (empty($row->{$fotoNum}->{'$t'})){
			${'image'.$countImg} = null;
		} else {
			${'image'.$countImg} = $appUri.$row->{$fotoNum}->{'$t'};
		}
		$countImg++;
	}
	
	
	array_push($arr, array(					
								'sku' => $row->{'gsx$codigo'}->{'$t'},
								'categories' => $row->{'gsx$categoria'}->{'$t'},
								'name' => $row->{'gsx$nombreproducto'}->{'$t'},
								'slug' => url($row->{'gsx$nombreproducto'}->{'$t'}),
								'type' => 'simple',
								'description' => $row->{'gsx$descripcion'}->{'$t'},
								'image0' => $image0,
								'image1' => $image1,
								'image2' => $image2,
								'image3' => $image3,
								'image4' => $image4,
								'image5' => $image5,
								'image6' => $image6,
								'image7' => $image7,
								'image8' => $image8,
								'image9' => $image9,
								'stock_quantity' => $row->{'gsx$stock'}->{'$t'},
								'regular_price' => $row->{'gsx$precio'}->{'$t'},
								'dimensions' => array (
									'length' => $row->{'gsx$largo'}->{'$t'},
									'height' => $row->{'gsx$alto'}->{'$t'},
									'width' => $row->{'gsx$ancho'}->{'$t'},
									),
								'tags' => array (
									array_map('trim', explode(',', $row->{'gsx$tags'}->{'$t'})),
									),
							)
					);

  // echo "<pre>";
  // print_r($arr);
  // echo "</pre>";
  
  // echo '<p>';
  // $code = $row->{'gsx$codigo'}->{'$t'};
  // $prodName = $row->{'gsx$nombreproducto'}->{'$t'};
  // $stock = $row->{'gsx$stock'}->{'$t'};
  // echo "codigo: ". $code . ' / ' . "nombre producto: ". $prodName . ' / ' . "stock: " . $stock;
  // echo '</p>'; 
}

//echo json_encode($arr);

$fp = fopen('productos.json', 'w+');
fwrite($fp, json_encode($arr));
fclose($fp);
echo "archivo creado";
?>