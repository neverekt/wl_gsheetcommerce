<?php
$baseUrl = 'https://spreadsheets.google.com/feeds/list/';
$idGsheet = '1xvmcXGErWGQigpjVkXJrq4kD2lw6hXWrBX6JNSY26v8';
$endUrl = '/od6/public/values?alt=json';
$sheetUri = $baseUrl.$idGsheet.$endUrl;
$file= file_get_contents($sheetUri);
$json = json_decode($file);
$rows = $json->{'feed'}->{'entry'};
foreach($rows as $row) {
  echo '<p>';
  $code = $row->{'gsx$codigo'}->{'$t'};
  $prodName = $row->{'gsx$nombreproducto'}->{'$t'};
  $stock = $row->{'gsx$stock'}->{'$t'};
  echo "codigo: ". $code . ' / ' . "nombre producto: ". $prodName . ' / ' . "stock: " . $stock;
  echo '</p>';
}
?>