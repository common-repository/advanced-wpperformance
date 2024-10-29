<?php

function awpp_add_serverpush_htaccess() {
	awpp_get_instance()->Http2Push->add_serverpush_htaccess();
}

function awpp_get_critical_keys() {

	$ids = [ 'index' ];

	/**
	 * Special Pages
	 */
	if ( is_front_page() || is_search() || is_404() ) {

		if ( is_front_page() ) {
			$ids[] = 'front-page';
		}

		if ( is_search() ) {
			$ids[] = 'search';
		}
		if ( is_404() ) {
			$ids[] = '404';
		}
	}

	if ( is_singular() ) {
		$ids[] = 'singular';
		$ids[] = 'singular-' . get_post_type();
		$ids[] = 'singular-' . get_the_id();
	}

	if ( is_archive() || is_home() ) {

		$ids[] = 'archive';

		if ( is_post_type_archive() || is_home() ) {

			$pt = get_query_var( 'post_type', 1 );
			if ( is_home() ) {
				$pt = 'post';
			}
			$ids[] = "archive-$pt";

		} elseif ( is_author() ) {

			$ids[] = 'archive-author';
			$ids[] = 'archive-author-' . get_query_var( 'author_name' );

		} elseif ( is_date() ) {

			$ids[] = 'archive-date';
			$date  = 'year';

			if ( is_month() ) {
				$date = 'month';
			} elseif ( is_day() ) {
				$date = 'day';
			}

			$ids[] = 'archive-date-' . $date;
		} elseif ( is_tax() || is_category() || is_tag() ) {

			$ids[] = 'archive-taxonomy';
			$ids[] = 'archive-taxonomy-' . get_term( get_queried_object()->term_id )->term_id;
			$ids[] = 'archive-taxonomy-' . get_term( get_queried_object()->term_id )->taxonomy;

		}
	} // End if().

	return $ids;
}

function awpp_is_frontend() {
	if ( is_admin() || 'wp-login.php' == $GLOBALS['pagenow'] ) {
		return false;
	}

	return true;
}

function awpp_exit_ajax( $type, $msg = '', $add = [] ) {

	$return = [
		'type'    => $type,
		'message' => $msg,
		'add'     => $add,
	];

	echo json_encode( $return );

	wp_die();
}

function awpp_get_setting( $key ) {
	return awpp_settings()->get_setting( $key );
}

function awpp_settings_page_server() {
	return awpp_settings()->add_page( 'server', __( 'Server Settings', 'awpp' ) );
}

function awpp_settings_page_assets() {
	return awpp_settings()->add_page( 'assets', __( 'Asset Delivery', 'awpp' ) );
}

function awpp_convert_date( $timestamp = '', $type = 'datetime' ) {
	if ( '' == $timestamp ) {
		$timestamp = time();
	}
	switch ( $type ) {
		case 'date':
			return date( get_option( 'date_format' ), $timestamp );
			break;
		case 'time':
			return date( get_option( 'time_format' ), $timestamp );
			break;
		default:
			return date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $timestamp );
			break;
	}
}