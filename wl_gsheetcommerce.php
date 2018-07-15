<?php
/*  
Plugin Name: WL GSheet Commerce
Plugin URI: http://www.weblibre.net
Description:Plugin to connect a Google sheet and import data in sheet to Woocommerce as an external database where you can upload data from a mobile app like appsheet.
Author: Jaime Pérez
Version: 0.1
Author URI: http://www.weblibre.net
License: GPL3
*/

add_action("admin_menu", "wl_opciones_gsheet_c");
add_action("admin_init", "wl_opciones_gsheet_c_init");

/**
* Acá registramos los campos que usaremos en nuestro panel de configuración del plugin
**/

if(!function_exists("wl_opciones_gsheet_c_init"))
{
	function wl_opciones_gsheet_c_init()
	{
		register_setting("wl-group","wl_gsheet_ccliente");
		register_setting("wl-group","wl_gsheet_csegura");
	}
}

/**
* Acá inicializamos el panel del plugin
**/

if(!function_exists("wl_opciones_gsheet_c"))
{
	function wl_opciones_gsheet_c()
	{
		add_options_page("Gsheet Commerce", "Gsheet Commerce", "manage_options", "WLGSC", "get_wl_opciones_gsheet_c");
	}
}

/**
* Acá creamos el código html del panel de opciones
**/

if(!function_exists("get_wl_opciones_gsheet_c"))
{
	function get_wl_opciones_gsheet_c()
	{
		?>
		<div class="wrap">
			<?php screen_icon()?><h2>Opciones de WL Gsheet Commerce</h2>
			<form method="post" action="options.php">
				<?php settings_fields("wl-group")?>
				<?php @do_settings_fields("wl-group")?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label for="wl_gsheet_ccliente">Clave de cliente</label>
						</th>
						<td>
							<input class="regular-text" type="text" name="wl_gsheet_ccliente" value="<?php echo get_option("wl_gsheet_ccliente")?>">
								<br />
								<small>Ingrese el valor de la clave de cliente que generó en Woocommerce para conectar con la API.</small>
							</input>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="wl_gsheet_csegura">Clave segura</label>
						</th>
						<td>
							<input class="regular-text" type="text" name="wl_gsheet_csegura" value="<?php echo get_option("wl_gsheet_csegura")?>">
								<br />
								<small>Ingrese el valor de la clave segura que generó Woocommerce para conectar con la API.</small>
							</input>
						</td>
					</tr>
				</table>
				<?php @submit_button()?>
			</form>
		</div>
		<?php
	}
}
	
?>