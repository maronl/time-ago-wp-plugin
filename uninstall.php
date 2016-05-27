<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
exit();
}

// make script to clean up WP after removing your plugin
// now we don't need to do nothing
