<?php  
// Add Shortcode

function shorcode_twchr_setUp() {
		add_option('twchr_setInstaled',1,'',true );
		$twchrKeysJSON = get_option('twitcher_keys');
		$clientId =  $twchrKeysJSON != false ? json_decode($twchrKeysJSON)->{'client-id'} : '';
		$clientSecret =  $twchrKeysJSON != false ? json_decode($twchrKeysJSON)->{'client-secret'} : '';
		

	    $html = "<div id='twchr-setUp'>
	                <section class='step1'>
						<div>
							<h1>Bienvenido al proseso de instalacion de Twittcher Plugin</h1>
							<h3>Antes de comenzar es necesario que obtengas los siguiente datos de tu cuenta de Twitch</h3>
							<ul>
								<li>client-id</li>
								<li>client-secret</li>
							</ul>
							<p>Puedes entrar al link que esta más abajo para conseguirlos.</p>
							<a class='twchr-button-setUp id='button_next_1' href='https://dev.twitch.tv/console' target='_blank'>Obtener Credenciales</a>
							<p>Una vez entres sigue los siguientes pasos:</p>
							<ol>
								<li>En la zona izquierda de la pantalla, has click en 'Registra tu aplicación'</li>
								<li>En la casilla nombre escribe el nombre de tu wordpress</li>
								<li>En la casilla 'URL de redireccionamiento de OAuth' pega lo siguiente <span style='color:red;'>".$_SERVER['SERVER_NAME']."/wp-admin/edit.php?post_type=twchr_streams&page=twchr-settings</span></li>
								<li>En la casilla Categoria selecciona 'Others'</li>
								<li>Resuelve el Captcha</li>
								<li>Click en crear</li>
							</ol>

							<p>De manera automatica le devolvera su client-id y su client-secret.<p>
							
							<form method='GET' action='https://".$_SERVER['SERVER_NAME']."/wp-admin/edit.php'>
								<input type='hidden' name='post_type' value='twchr_streams'>
								<input type='hidden' name='page' value='twchr-settings'>
								<input type='hidden' name='from' value='setUp-plugin'>
								<input id='client-id' type='text' placeholder='Client ID' name='client-id' value='$clientId'>
								<input id='client-secret' type='password' placeholder='Client Secret' name='client-secret' value='$clientSecret'>								
								<input type='submit' value='sincronizar' name='sincronizar'>
							</form>
	
						</div>
					</section>
	            </div>";
	   return $html;

}
add_shortcode( 'twchr-setup', 'shorcode_twchr_setUp' );


//[twchr-setup]

?>