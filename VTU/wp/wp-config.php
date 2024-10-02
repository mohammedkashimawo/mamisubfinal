<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'omoluabi_wp721' );

/** Database username */
define( 'DB_USER', 'omoluabi_wp721' );

/** Database password */
define( 'DB_PASSWORD', 'SA!p637A(a' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('DISABLE_WP_CRON', true);

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'zccnopsd0l6orwddu00cyvvrkoz1bpzlxqhl6mrycmrd6fj5agjemftcr4p6wwfc' );
define( 'SECURE_AUTH_KEY',  'lzm7okeylepxkoybpqwmba5vnt6r3sdvtaktlccbtk6hg860dwz6ivthkl5xriqi' );
define( 'LOGGED_IN_KEY',    'mkbvmpuv6phzharwwk2hzuc3gaa1jxf3ahr4rzmhdrysadnph7tcv3vjwukvhaxj' );
define( 'NONCE_KEY',        'zdwgy3xktcn4cbcua9ednzzgcjmejrwzn39ae3qvb5sq6ajrnjmvoihieycrn55l' );
define( 'AUTH_SALT',        'tg2yues1pbmlpj8l8tkdxrm5hsap9mc2l9bkk3op5ssawpq3snesxenkr9vb1txa' );
define( 'SECURE_AUTH_SALT', '3nddwhimfcnssulf3a1mkfhzzgm8x9cby9qmtvvamzdqgytwpf6z1lyt5ylvigvi' );
define( 'LOGGED_IN_SALT',   'bafk2vyxwiomtbgswa66rgnduilhpvz1rnb9t5xibekfaexnzjev47ddsu1p56mh' );
define( 'NONCE_SALT',       'yesxwuzd47jbk4ajdxw1v7cywxuzkgl5zehc9y6bzj9pu4ci7gelujplyshib9gt' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp4h_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';