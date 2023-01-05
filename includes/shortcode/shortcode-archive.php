<?php 
function twchr_archive_streams_shortcode(){
    $servicios = get_posts(array(
        'post_type' => 'twchr_streams'
    ));

    ?>
    <style>
        .archive-servicios{
            display: grid;
            grid-template-columns: repeat(2,300px);
            justify-content: space-around;
            row-gap: 10pt;
        }
        .archive-servicios .terapeuta-item {
            width: 100%;
            height: 250px;
        }
    </style>
    <div class='archive-servicios'>
    <?php
    foreach($servicios as $terapeuta):
        $title = $terapeuta->post_title;
        $link = $terapeuta->guid;
    ?>
        <article class="stream-item">
            <a href="<?php echo $link?>">
                <?php
                    if(has_post_thumbnail($terapeuta)){
                        $img = get_the_post_thumbnail($terapeuta);
                        echo $img;
                    }
                ?>
                <h3><?php echo $title?></h3>
            </a>
        </article>
    <?php
    endforeach;
    echo "</div>";
}

add_shortcode('twchr_archive_streams','twchr_archive_streams_shortcode');

?>