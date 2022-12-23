<?php
function twchr_clipboard_include(){ ?>

    <script>
    const twchr_btns = document.querySelectorAll('button.twchr_clipboard');
    const twcjr_clipboard = new ClipboardJS(btns);

    clipboard.on('success', function(e) {
        console.log(e);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
</script>
<?php
}

add_action('wp_footer', 'twchr_clipboard_include');