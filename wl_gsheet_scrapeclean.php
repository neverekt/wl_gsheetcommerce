<?php
$url = 'https://spreadsheets.google.com/feeds/list/1xvmcXGErWGQigpjVkXJrq4kD2lw6hXWrBX6JNSY26v8/od6/public/values?alt=json';
$file= file_get_contents($url);
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