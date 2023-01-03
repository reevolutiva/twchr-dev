<?php
function twchr_clipboard_include(){ ?>

	<script>
	const twchr_btns = document.querySelectorAll('.twchr-clipboard-item');
	const twcjr_clipboard = new ClipboardJS(twchr_btns);

	twcjr_clipboard.on('success', function(e) {
		console.log(e);
	});

	twcjr_clipboard.on('error', function(e) {
		console.log(e);
	});
</script>
	<?php
}

add_action( 'wp_footer', 'twchr_clipboard_include' );
