<?php
function twchr_max_of_list($list,$num,$title,$cf = false){
    
    $mostViwed = false;
    $viewed = array();
    foreach($list as $item){
        $view; 
        if($cf){
            $id = $item->{'ID'};
            $view = get_post_meta( $id, $num, true );
        }else{
            $view = $item->{$num};
        }                                   
        array_push($viewed,$view);
    }
    foreach($list as $item){
        $max_view = max($viewed);
        $title = $item->{$title};
        $view;
        if($cf){
            $id = $item->{'ID'};
            $view = get_post_meta( $id, $num, true );
        }else{
            $view = $item->{$num};
        }
        
        if($view == $max_view){
            $mostViwed = array(
                    'view' => $max_view,
                    'title' => $title
                );
            }
                                   
    }

    return $mostViwed;
}