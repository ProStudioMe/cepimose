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
define( 'AUTH_KEY',          'ge0[-b!Dx0Xi(Oz<$NJO3.%-T]W:w1-9{K] I{NHIE=U`@Pp^Am=nw*|,g-XZqu.' );
define( 'SECURE_AUTH_KEY',   '&HRIfdqAv7m(z _YHS&_Fo`eMEUw`3EU-MTGjs%/SDX4kVJ89#fPC4c&I9%+IQRW' );
define( 'LOGGED_IN_KEY',     'X4^DtF3#`F=,gMTP*T^09Aso.W)q-,%p@x^tU}<L1a0Z@=,fx,hT4|P5B~S<L|&(' );
define( 'NONCE_KEY',         '-3N@Y1NnEQ4TJga@~U4s1)+wBCwIHmoXFLJITczdOjHkhfKj!19q7a%L#I7$<Kz9' );
define( 'AUTH_SALT',         'aAN(*.3o0~VDSH_tkcTWCvn(SlHR;jt`9miL]8 kE_X*YtmLQkjOV%p<B|rz}y8r' );
define( 'SECURE_AUTH_SALT',  ' ,n+bTK.9ycEiIEg*D1svt~~sT#zCKBe4{3g)3f9JQRT$ux4;-BnQZ({NakYvM.0' );
define( 'LOGGED_IN_SALT',    '5[BN^-<4P!$_;[jCktU|=Hd%xK)i]7<YD48#$FTz;#%OR[T8*]D<G+`#TGLU(-?7' );
define( 'NONCE_SALT',        'IlBYa,?vxBd9u%ZVV<4xQTf4>v6MRUfn~X1|yr8&%qgsu`LiGTZt1<++Z,jS[Lm_' );
define( 'WP_CACHE_KEY_SALT', '],7@3>#!kjW71f/^}q5QU5U[=~5U{H66INC#N5WHvjUKE>r4`]?m#1WG-UJn(nL<' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */

define('WP_DEVELOPMENT_MODE', 'theme');

// define('ALLOW_UNFILTERED_UPLOADS', true);


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