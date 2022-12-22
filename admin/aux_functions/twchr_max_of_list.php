<?php
/**
 * Give a list y choice the item most high from this list.
 *
 * @param array   $list
 * @param string  $num
 * @param string  $title_var
 * @param boolean $cf
 * @return void
 */
function twchr_max_of_list( array $list, string $num, string $title_var, bool $cf = false ) {
	$mostViwed = false;
	$viewed = array();
	foreach ( $list as $item ) {
		$view = '';
		if ( $cf ) {
			$id = $item->{'ID'};
			$view = get_post_meta( $id, $num, true );
		} else {
			$view = $item->{$num};
		}
		array_push( $viewed, $view );
	}

	foreach ( $list as $item ) {
		$max_view = max( $viewed );
		$title = $item->{$title_var};
		$view;
		// show_dump($title);
		if ( $cf ) {
			$id = $item->{'ID'};
			$view = get_post_meta( $id, $num, true );
		} else {
			$view = $item->{$num};
		}

		if ( $view == $max_view ) {
			$mostViwed = array(
				'view' => $max_view,
				'title' => $title,
			);
		}
	}

	return $mostViwed;
}

