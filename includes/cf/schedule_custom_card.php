<?php if(get_post_type() === 'twchr_streams'): ?>
    <div class="twchr_custom_card--contain">
        <div class="twchr_custom_card_header">
            <div>
                <h3 class="active"><?php _e('Schedule Future Streaming','twitcher');?></h3>
            </div>
            <div>
                <h3 class="active twchr_button_get_videos"><?php _e('Assign Twitch Streaming or Video','twitcher');?></h3>
            </div>
            <div>
                <h3 class="active"><?php _e('Assign Youtube Link Video','twitcher');?></h3>
            </div>
        </div>
        <div class="custom_card_row">
            <section>
                <?php require_once 'streaming_custom_tab1.php'; ?>
            </section>
            <section>
                <?php require_once 'streaming_custom_tab2.php';?>
            </section>
            <section>
                <?php require_once 'streaming_custom_tab3.php';?>
            </section>
        </div>
        <script>
            <?php require 'script_streaming_single.js';?>
        </script>
    </div>
<?php endif; ?>