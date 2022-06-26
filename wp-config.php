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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'expressfood_db' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'h+T40BL(p7k1::@uwzl?Wp;F>0j8O(BW5N$VZJ+@&(]l-;j{J%av*Dm;vOme4NcO' );
define( 'SECURE_AUTH_KEY',  '8L:9g}hb^a.KwYiOor{Vuz,6zIiFW,~>A7]f&.ZoZi388(D7Ux GNC&{>/~E3xvE' );
define( 'LOGGED_IN_KEY',    'v]l/kPW[1|7`|EYn/mn1 So4p621,hy-6Uz-%LT>Rth2_8+b&.%y2DyQ?M5D4W^q' );
define( 'NONCE_KEY',        '6%eZSQz(xC?R#ygnX8D97%4S$!$opFa<`[7i+UyC`L{gSq[4=48he!X2$o45QmT6' );
define( 'AUTH_SALT',        'x~27OdhP$eOjPk2dECbJoW/$nmymCs|2p<q/rFZU0KZRtS ]#*=,]y,F5J_<D4xH' );
define( 'SECURE_AUTH_SALT', 'F7NmA(/JR66B.L+.j/k~)=6_Q@)wj&5Tdkx5anJ3XU&kuBe6+G-,n`Ix?E2o[?v}' );
define( 'LOGGED_IN_SALT',   'ct~pqy7 ;Vo?I>qUwEhq2{%f5Ejj`xGq0/Ekb)+[<{uZc2f)?O*V|H*OzK%RHG&0' );
define( 'NONCE_SALT',       'd1/zK>KsH7m~Fp7Y,HWM)]cql^Y8V=A6|Vcsdg/<[*X%#(6QW)`Jh.Pc?x5 m<2`' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
