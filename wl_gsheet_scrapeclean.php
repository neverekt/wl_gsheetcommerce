<?php
header("Content-Type: application/json");
$baseUrl = 'https://spreadsheets.google.com/feeds/list/';
$idGsheet = '1xvmcXGErWGQigpjVkXJrq4kD2lw6hXWrBX6JNSY26v8';
$endUrl = '/od6/public/values?alt=json';
$sheetUri = $baseUrl.$idGsheet.$endUrl;
$file= file_get_contents($sheetUri);
$json = json_decode($file);
$rows = $json->{'feed'}->{'entry'};
$arr = array();
foreach($rows as $row) {
  
	array_push($arr, array(					
								'sku' => $row->{'gsx$codigo'}->{'$t'},
								'categories' => $row->{'gsx$categoria'}->{'$t'},
								'name' => $row->{'gsx$nombreproducto'}->{'$t'},
								'type' => 'simple',
								'stock_quantity' => $row->{'gsx$stock'}->{'$t'}
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

echo json_encode($arr);
?>