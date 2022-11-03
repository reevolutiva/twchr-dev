<div class="twchr_custom_card--contain postbox-container">
    <div class="twchr_card_header">
        <div class="twchr_card_header--title">
            <img src="<?= plugins_url('/twitcher/includes/assets/twitch_logo.png') ?>" alt="logo-twitch">
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
        <div class="twchr_card_body--list">
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

<script>
    const stream_isset = document.querySelectorAll('.twchr_card_body--list ul li span.value');
    const stream_isset_array = [];
    for (let i = 0; i < stream_isset.length; i++) {
        const element = stream_isset[i];
        //console.log(element.value);
        if(element.textContent === undefined){
            stream_isset_array.push(true);
        }else{
            stream_isset_array.push(false)
        }        
    }
    if(stream_isset_array.every(item => item === true)){
        document.querySelector('.twchr_custom_card--contain').style.display = 'none';
    }
</script>
