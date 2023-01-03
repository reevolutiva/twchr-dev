<?php

function twchr_dinamyc_admin_js_enqueue() {

	echo "<script type='text/javascript'>";
	echo "let twchr_admin_url ='" . admin_url() . "';";
		// Llamo el modulo de error handel para js
		require_once 'js/error_handler.js';
	echo '</script>';
}

add_action( 'admin_footer', 'twchr_dinamyc_admin_js_enqueue' );



