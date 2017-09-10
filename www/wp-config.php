<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache


/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
//echo strpos($_SERVER['SERVER_NAME'], 'reviewdev');
//exit;
if (strpos($_SERVER['SERVER_NAME'], 'scmreview') > 0) {
    /** The name of the database for WordPress */
    define('DB_NAME', 'staging_blush_production');
    /** MySQL database username */
    define('DB_USER', 'blushdb');
    /** MySQL database password */
    define('DB_PASSWORD', '63Q@LWmUFbfB');
    /** MySQL hostname */
    define('DB_HOST', 'halfslidevpc.czvoexoklzdt.us-west-2.rds.amazonaws.com');
    define('WP_HOME', 'http://blush.scmreview.com');
    define('WP_SITE_URL', 'http://blush.scmreview.com');
} else {
    // ** MySQL settings - You can get this info from your web host ** //
    /** The name of the database for WordPress */
    define('DB_NAME', 'blush');
    /** MySQL database username */
    define('DB_USER', 'blushdb');
    /** MySQL database password */
    define('DB_PASSWORD', '63Q@LWmUFbfB');
    /** MySQL hostname */
    define('DB_HOST', 'halfslidevpc.czvoexoklzdt.us-west-2.rds.amazonaws.com');

}
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'FwHq([{T$EIyc,uQ86u6S!mBwl+3H&(+Vyx}%[O4+^xV%!vzct|>.D+8<9J/~jlt');
define('SECURE_AUTH_KEY',  'wm1QQY+&=@|0P_+TH3 dH+oC:&dpK6.=p?->+-gJnkSqR0hx]}*skKVI!|-M(I;+');
define('LOGGED_IN_KEY',    'Xcg+N6Fd;0GFWKa4yfb{Q~#8I vw$- L-$iMx1CSU3/=<u=xq-HA|Tzber7`.F9I');
define('NONCE_KEY',        'oi[%(HV|qRq<!pQ,BW.x9zQjNTox.2+$}vwnGW{kl{YF.tV*N{J#%XwE;LC)>Qo+');
define('AUTH_SALT',        'Fd |h|vLZ/XX11K_~;N(<J2v^=(<>LOlr|Xj@FrL>+=v[==S2U5#m(.5^dy#^<,|');
define('SECURE_AUTH_SALT', 'hEhOI!AC?W3X|LKS.IXkJwnv >o_Q*4^8d=-.W*e6-M`qK/BN{H5=$4d&6kR)h%4');
define('LOGGED_IN_SALT',   '!<TaYG}jRs|B5S?3,2R$VMcXfPU!bZk-s7T|8:Mqz}e[L8eq+pcYFE&Q(;%y g$ ');
define('NONCE_SALT',       'I/-o7->|PK.xR!,VwU|-,^C9mriRz%8s^e!Aa=[rBf{.7,qO|OjpBt?@zrYPZmB-');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('ALTERNATE_WP_CRON', true);

