<?php
/**
 * Plugin Name: Dr.Ritchie
 * Description: The only way to learn a new programming language is by writing programs in it.
 * Version:     1.0.0
 * Author:      Abolfazl Sabagh
 * License:     GPLv2 or later
 * Requires PHP: 7.4
 * Requires at least: 5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_notices', function (){
    ?>
    <div class="notice notice-success is-dismissible dr-success-admin-notice">
        <p>
            <?php
            echo esc_html__(
                'The only way to learn a new programming language is by writing programs in it.',
                'dr-success-admin-notice'
            );
            ?>
        </p>
    </div>
    <?php
} );