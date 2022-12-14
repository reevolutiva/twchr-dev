<?php if(get_post_type() === 'twchr_streams'): ?>
    <div class="twchr_custom_card--contain">
        <div class="twchr_custom_card_header">
            <div>
                <h3>To API</h3>
            </div>
            <div>
                <h3>Streaming Data</h3>
            </div>
        </div>
        <div class="custom_card_row">
            <section>
                <?php require_once 'streaming_custom_tab1.php'; ?>
            </section>
            <section>
                <?php require_once 'streaming_custom_tab2.php';?>
            </section>
        </div>
        <script>
            <?php require 'script_streaming_single.js';?>
        </script>
    </div>
<?php endif; ?>