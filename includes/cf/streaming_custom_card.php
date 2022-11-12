<style>
    
.twchr_custom_card--contain{
    background: linear-gradient(317.7deg, rgba(0, 0, 0, 0.4) 0%, rgba(255, 255, 255, 0.4) 105.18%), #FFFFFF;
    background-blend-mode: soft-light, normal;
    border: 1px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 0 5px #A6ABBD;
    border-radius: 15px;
    padding: 26pt 78pt 35pt 78pt;
    box-sizing:border-box;
    font-family: 'Comfortaa';
    font-style: normal;
    position: absolute;
    top: 1cm;
    width: 95%;
}
.twchr_custom_card--contain.closed{
    display: none;
}
#twitcher_stream .inside.active{
    min-height: 550px;
}
.twchr_custom_card--contain .twchr_card_header{
    display: grid;
    grid-template-columns:1fr 220px;
    grid-template-rows:68px 100px;
}
.twchr_custom_card--contain .twchr_card_header--title{
    display: flex;
    align-items:center;
}
.twchr_custom_card--contain .twchr_card_header--title img{
    height: 27px;
    margin-right:10pt;
}
.twchr_custom_card--contain .twchr_card_header--title h3{
    
    
    font-weight: 700;
    font-size: 32px;
    line-height: 36px;
}
.twchr_custom_card--contain .twchr_card_header-description h4{
    
    
    font-weight: 300;
    font-size: 16px;
    line-height: 18px;
    margin: 0;
}
.twchr_custom_card--contain .twchr_card_header--img{
    grid-row:1/3;
    grid-column:2/3;
}

.twchr_custom_card--contain .twchr_card_header--img img{
    width: 100%;
    height: 100%;
    object-fit:contain;
}

.twchr_custom_card--contain .twchr_card_body{
    display: grid;
    grid-template-columns:1fr 220px;
}

.twchr_custom_card--contain .twchr_card_body--list ul{
    display: flex;
    flex-direction:column;
    justify-content:space-between;
    height: 100%;
    margin: 0;
}
.twchr_custom_card--contain .twchr_card_body--list li{
    display: grid;
    grid-template-columns:111px auto;
    margin-bottom: 10pt;
}
.twchr_custom_card--contain .twchr_card_body--list li span.label{
    
    
    font-weight: 300;
    font-size: 14px;
    line-height: 16px;
    color: #C9BBBB;
}
.twchr_custom_card--contain .twchr_card_body--list li span.value{
    
    
    font-weight: 300;
    font-size: 14px;
    line-height: 16px;
    color: #000000;
}
.twchr_custom_card--contain .twchr_card_body--status .item{
    width:max-content;
    margin:0 auto;
    text-align:center;
}

.twchr_custom_card--contain .twchr_card_body--status .item h3{
    color:var(--twchr-purple);
    font-weight: 700;
    font-size: 32px;
    line-height: 36px;
    margin: 0 auto 10pt auto;
}

.twchr_custom_card--contain .twchr_card_body--status .item h3.on{
    color:#0C884C;
}
.twchr_custom_card--contain .twchr_card_body--status .item h3.failed{
    color:tomato;
}

</style>
<div class="twchr_custom_card--contain closed">
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
