<?php
/*
Plugin Name: The Time Ago WP Plugin
Plugin URI:  https://github.com/maronl/time-ago-wp-plugin
Description: Simple wordpress plugin to show the time since the post was published (1 second ago, 2 days ago, 1 month ago). The main code of this plugin
was from Jason Bobich. http://www.jasonbobich.com/wordpress/a-better-way-to-add-time-ago-to-your-wordpress-theme/
Version:     1.0
Author:      Luca Maroni
Author URI:  http://maronl.it
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: time-ago-wp-plugin
*/

function tawpp_load_plugin_textdomain() {
    load_plugin_textdomain( 'time-ago-wp-plugin', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'tawpp_load_plugin_textdomain' );

if(!function_exists('get_the_time_ago')){
    function get_the_time_ago() {

        global $post;

        $date = get_post_time('G', true, $post);

        // Array of time period chunks
        $chunks = array(
            array( 60 * 60 * 24 * 365 , __( 'year', 'time-ago-wp-plugin' ), __( 'years', 'time-ago-wp-plugin' ) ),
            array( 60 * 60 * 24 * 30 , __( 'month', 'time-ago-wp-plugin' ), __( 'months', 'time-ago-wp-plugin' ) ),
            array( 60 * 60 * 24 * 7, __( 'week', 'time-ago-wp-plugin' ), __( 'weeks', 'time-ago-wp-plugin' ) ),
            array( 60 * 60 * 24 , __( 'day', 'time-ago-wp-plugin' ), __( 'days', 'time-ago-wp-plugin' ) ),
            array( 60 * 60 , __( 'hour', 'time-ago-wp-plugin' ), __( 'hours', 'time-ago-wp-plugin' ) ),
            array( 60 , __( 'minute', 'time-ago-wp-plugin' ), __( 'minutes', 'time-ago-wp-plugin' ) ),
            array( 1, __( 'second', 'time-ago-wp-plugin' ), __( 'seconds', 'time-ago-wp-plugin' ) )
        );

        if ( !is_numeric( $date ) ) {
            $time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
            $date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
            $date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
        }

        $current_time = current_time( 'mysql', $gmt = 0 );
        $newer_date = strtotime( $current_time );

        // Difference in seconds
        $since = $newer_date - $date;

        // Something went wrong with date calculation and we ended up with a negative date.
        if ( 0 > $since )
            return __( 'sometime', 'time-ago-wp-plugin' );

        //Step one: the first chunk
        for ( $i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];

        // Finding the biggest chunk (if the chunk fits, break)
            if ( ( $count = floor($since / $seconds) ) != 0 )
                break;
        }

        // Set output var
        $output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];


        if ( !(int)trim($output) ){
            $output = '0 ' . __( 'seconds', 'time-ago-wp-plugin' );
        }

        $output .= __(' ago', 'time-ago-wp-plugin');

        return $output;

    }
}

if(!function_exists('the_time_ago')){
    function the_time_ago() {
        echo get_the_time_ago();
    }
}
