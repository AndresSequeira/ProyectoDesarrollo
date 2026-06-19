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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '{^[7XbXV1Smgr@tZLAUyu:6jyco/rDn.5Y PVq^C;MiJ.Gu0%U,5OE`;aDiS22d>' );
define( 'SECURE_AUTH_KEY',   ']f<6d%HtLC}P9#t.)NpDh%lHq>uZF<zo%w0^81ptoDbg3H%sWz8B2wQ!~)j=4{c}' );
define( 'LOGGED_IN_KEY',     'qT2jM`9FJze1|d!ZJbO+J#pv1;+[g.^]83MfKr%(x^RLQ:xzk{5o8f`?R}%OR|E^' );
define( 'NONCE_KEY',         'B.%0G`VTIV!-C6eI[Jfx:wo5L^kK>e$rk@mDi,*O8zxMoH{:M4!pe[^H+faK%ezs' );
define( 'AUTH_SALT',         'A}CgT<*,qNz+nwmV D4mz[L)rw&c<9(ru2*8yQ3/:b?MhDeyVL@y^:]t(+NGzdX:' );
define( 'SECURE_AUTH_SALT',  ',`F/DYpbxV/O$yHh*?q<{(z6+b(E@wxLM%OP_eCs893{)2r%El MD%KV4Ir;oKY*' );
define( 'LOGGED_IN_SALT',    'd7|.yh;t~=Er*_FZIb=|p.UEKi+qw=|X|S0v[XV!wd1<3pE)!zmxpMc~c1ez/9)`' );
define( 'NONCE_SALT',        'U%N)|Q3cd3nJuJDU#yCrJ`?t<G238@3zH[g{bF[20H?D>E/nsPA;8RDTET>H2@}A' );
define( 'WP_CACHE_KEY_SALT', 'RL|z?<biI#}d*OUv!`E wmx&sH0d#&Vu f~,t(Ds;qi@4`~:>Ddm>vrvd<)ac^xL' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
