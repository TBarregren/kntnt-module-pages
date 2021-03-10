<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || die;

delete_option( 'kntnt-plugin' );

$upload_dir = wp_upload_dir()['basedir'];
@unlink( "$upload_dir/kntnt-plugin/kntnt-plugin.css" );
@rmdir( "$upload_dir/kntnt-plugin" );
