<?php

function show_dump( $var ) {
	echo "<pre style='background-color:#ededed; padding:20px 15px';>";
	var_dump( $var );
	echo '</pre>';
}

function comparate_WpPost( $post_before, $post_after ) {
	echo "<div style='display:grid;grid-template-columns:1fr 1fr;grid-gap:10px;'>";
		echo "<div style='background-color:#ededed;'>";
			show_dump( $post_before );
		echo '</div>';
		echo "<div style='background-color:#ededed;'>";
			show_dump( $post_after );
		echo '</div>';
	echo '<div>';
	die();
}
