<div class="twchr_car_tab2">
    <?php
        $twch_data_prime = get_option('twchr_keys') == false ? false : json_decode(get_option('twchr_keys'));
        $data_broadcaster = get_option( 'twchr_data_broadcaster', false ) == false ?  false :  json_decode(get_option( 'twchr_data_broadcaster'));
        $broadcaster_id = $data_broadcaster->{'data'}[0]->{'id'};
        $twch_data_app_token = get_option('twchr_app_token');
        // domain.net/wp-admin/post-new.php
        // Divide la url por sus "/" y escoje el ultimo item
        $dataUrl = sanitize_url($_SERVER['REQUEST_URI']);
        $dataUrl = explode('/',$dataUrl)[2];
       
        ?>
            <style>
                a.twchr_button_get_videos {
                    text-decoration: none;
                    padding: 5px 10px;
                    display: block;
                    margin-bottom: 10pt;
                    width: max-content;
                    border-radius: 5px;
                    background-color: var(--twchr-purple);
                    color: #fff;
                }
    
                stream.twchr_modal_get_videos{
                    position: static;
                    border: 0;
                    box-shadow: none;
                    background: 0;
                    display: none;
                }
                stream.twchr_modal_get_videos.active{
                    display: block;
                }

                
    
                .twchr-modal .twchr_help_button {
                    display: block;
                    width: 40px;
                    height: 40px;
                    background-image: url(<?php echo TWCHR_URL_ASSETS.'help.png'?>);
                    background-size: contain;
                    background-repeat: no-repeat;
                    margin-right: 6pt;
                }
                .previw_card.disabled{
                    display: none;
                }
    
            </style>
            <stream class="twchr_modal_get_videos twchr-modal active">
                
                <div class="twchr-modal-selection__info">
                    <h3><?php twchr_esc_i18n('Asign video to post','html') ?></h3>
    
                    <picture>
                        <img src="<?php echo TWCHR_URL_ASSETS.'Isologo_twitcher.svg';?>" alt="logo-twitcher">
                    </picture>
                </div>
    
                <div id="twchr_button_get_videos__content">
                    <ul class="twchr-modal-selection__list">
                        <li><?php twchr_esc_i18n('Streaming name','html'); ?></li>
                        <li><?php twchr_esc_i18n('Date','html'); ?></li>
                        <li><?php twchr_esc_i18n('Already saved?','html'); ?></li>
                        <li><?php twchr_esc_i18n('Import','html'); ?></li>
                    </ul>
                    <div class="content">
    
                    </div>
                    
                </div>
    
                <div class="twchr-modal-footer">
                    <span class="twchr_help_button">
                        <p><?php twchr_esc_i18n('The folowing list is the avaiable videos in your twitch account. Select the video that you want to asign to this post.','html'); ?>
                        </p>
                        
                    </span>
                    <button id="twchr-modal-selection__btn"><?php twchr_esc_i18n('Asign','html');?></button>
                </div>
            </stream>
            <script>
            const twchr_modal = document.querySelector(".twchr_modal_get_videos.twchr-modal");
            
            </script>
            <div class="previw_card">
                <div class="twchr_card_header">
                    <div class="twchr_card_header--title">
                        <img src="<?php echo TWCHR_URL_ASSETS.'twitch_logo.png'; ?>" alt="logo-twitch">
                        <h3>Twitch Developers 101</h3>
                    </div>
                    <div class="twchr_card_header-description">
                        <h4>"Welcome to Twitch development! Here is a quick overview of our products and information to help you get started."</h4>
                    </div>
                    <div class="twchr_card_header--img">
                        <img src="" alt="twtich-img">
                    </div>
                </div>
                <div class="twchr_card_body">
                    <div class="twchr_card_body--list previw_card__status">
                        <ul>
                            <li><span class="label">Created at</span><span class="value">en</span></li>
                            <li><span class="label">Duration</span><span class="value">en</span></li>
                            <li><span class="label">Languaje</span><span class="value">en</span></li>
                            <li><span class="label">Type</span><span class="value">en</span></li>
                            <li><span class="label">Viewable</span><span class="value">en</span></li>
                            <li><span class="label">URL</span><span class="value">en</span></li>
                        </ul>
                    </div>
                    <div class="twchr_card_body--status">
                        <div class="item view">
                            <h3>1.863.062</h3>
                            <p>Views</p>
                        </div>
                        <div class="item status">
                            <h3></h3>
                            <p>Status</p>
                        </div>
                    </div>
                </div>
            </div>
</div>