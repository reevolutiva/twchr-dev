<?php
function twchr_clipboard_include(){ ?>

    <script>
    var btns = document.querySelectorAll('button');
    var clipboard = new ClipboardJS(btns);

    clipboard.on('success', function(e) {
        console.log(e);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
</script>
<?php
}

add_action('shutdown ', 'twchr_clipboard_include');