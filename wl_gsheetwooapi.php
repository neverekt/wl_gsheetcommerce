<?php
	// Load Composer autoloader.
	// @link https://github.com/brightnucleus/jasper-client/blob/master/tests/bootstrap.php#L55-L59
	$autoloader = dirname( __FILE__ ) . '/vendor/autoload.php';
	if ( is_readable( $autoloader ) ) {
		require_once $autoloader;
	}

	use Automattic\WooCommerce\Client;
	
	$storeUri = "http://localhost/faisan";
	$storeCk = "ck_5b77b3697f1e9bdc62c2f485bd757b592e103e68";
	$storeCs = "cs_8f4db67ff3d510d63ca9c38135fc274901f5c11d";
	
	
	$woocommerce = new Client(
		$storeUri,
		$storeCk,
		$storeCs,
		[
			'wp_api'  => true,
			'version' => 'wc/v2',
			'query_string_auth' => false,
		]
	);
	
	/**
	 * Parse JSON file.
	 *
	 * @param  string $file
	 * @return array
	 */
	function getJsonFromFile()
	{
		$file = 'productos.json';
		$json = json_decode(file_get_contents($file), true);
		return $json;
	}
	function checkProductBySku($skuCode)
	{
		$woocommerce = getWoocommerceConfig();
		$products = $woocommerce->get('products');
		foreach ($products as $product) {
			$currentSku = strtolower($product['sku']);
			$skuCode = strtolower($skuCode);
			if ($currentSku === $skuCode) {
				return ['exist' => true, 'idProduct' => $product['id']];
			}
		}
		return ['exist' => false, 'idProduct' => null];
	}
	function createProducts()
	{
		$woocommerce = getWoocommerceConfig();
		$products = getJsonFromFile();
		$imgCounter = 0;
		foreach ($products as $product) {
			/*Chec sku before create the product */
			$productExist = checkProductBySku($product['sku']);
			$imagesFormated = array();
			/*Main information */
			$name = $product['name'];
			$slug = $product['slug'];
			$sku = $product['sku'];
			$description = $product['description'];
			$images = $product['images'];
			$articulos = $product['articulos'];
			$categories = $product['categories'];
			$categoriesIds = array();
			foreach ($images as $image) {
				$imagesFormated[] = [
					'src' => $image,
					'position' => 0
				]; /* TODO: FIX POSITON */
				$imgCounter++;
			}
			/* Prepare categories */
			foreach ($categories as $category) {
				$categoriesIds[] = ['id' => getCategoryIdByName($category)];
			}
			$finalProduct = [
				'name' => $name,
				'slug' => $slug,
				'sku' => $sku,
				'description' => $description,
				'images' => $imagesFormated,
				'categories' => $categoriesIds,
				'attributes' => getproductAtributesNames($articulos)
			];
			if (!$productExist['exist']) {
				 $productResult = $woocommerce->post('products', $finalProduct);
			} else {
				/*Update product information */
				$idProduct = $productExist['idProduct'];
				$woocommerce->put('products/' . $idProduct, $finalProduct);
			}
		}
	}
	function createCategories()
	{
		$categoryValues = getCategories();
		$woocommerce = getWoocommerceConfig();
		
		foreach ($categoryValues as $value) {
			if (!checkCategoryByname($value)) {
				$data = [
					'name' => $value
				];
				$woocommerce->post('products/categories', $data);
			}
		}
	}
	function checkCategoryByName($categoryName)
	{
		$woocommerce = getWoocommerceConfig();
		$categories = $woocommerce->get('products/categories');
		foreach ($categories as $category) {
			if ($category['name'] === $categoryName) {
				return true;
			}
		}
		return false;
	}
	/** CATEGORIES  **/
	function getCategories()
	{
		$products = getJsonFromFile();
		$categories = array_column($products, 'categorias');
		foreach ($categories as $categoryItems) {
			foreach ($categoryItems as $categoryValue) {
				$categoryPlainValues[] = $categoryValue;
			}
		}
		$categoryList = array_unique($categoryPlainValues);
		return $categoryList;
	}
	function getCategoryIdByName($categoryName)
	{
		$woocommerce = getWoocommerceConfig();
		$categories = $woocommerce->get('products/categories');
		foreach ($categories as $category) {
			if ($category['name'] == $categoryName) {
				return $category['id'];
			}
		}
	}
	function getproductAtributesNames($articulos)
	{
		$keys = array();
		foreach ($articulos as $articulo) {
			$terms = $articulo['config'];
			foreach ($terms as $key => $term) {
				array_push($keys, $key);
			}
		}
		   /* remove repeted keys*/
		$keys = array_unique($keys);
		$configlist = array_column($articulos, 'config');
		$options = array();
		foreach ($keys as $key) {
			$attributes = array(
				array(
					'name' => $key,
					'slug' => 'attr_' . $key,
					'visible' => true,
					'variation' => true,
					'options' => getTermsByKeyName($key, $configlist)
				)
			);
		}
		return $attributes;
	}
	function getTermsByKeyName($keyName, $configList)
	{
		//var_dump($configList);
		$options = array();
		foreach ($configList as $config) {
			foreach ($config as $key => $term) {
				if ($key == $keyName) {
					array_push($options, $term);
				}
			}
		}
		return $options;
	}
	function prepareInitialConfig()
	{
		echo ('Importing data, wait...')."\n";
		createCategories();
		createProducts();
		echo ('Done!')."\n";
	}
	prepareInitialConfig();
		
	// $prod_data = [
		// 'name'          => 'A great product',
		// 'type'          => 'simple',
		// 'regular_price' => '15.00',
		// 'description'   => 'A very meaningful product description',
		// 'images'        => [
			// [
				// 'src'      => 'https://shop.local/path/to/image.jpg',
				// 'position' => 0,
			// ],
		// ],
		// 'categories'    => [
			// [
				// 'id' => 1,
			// ],
		// ],
	// ];

	// $woocommerce->post( 'products', $prod_data );
?>