<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'vanc_nashvan');

/** MySQL database username */
define('DB_USER', 'vanc_nashvan');

/** MySQL database password */
define('DB_PASSWORD', 'RJa2vTBrEU9xRpL');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'TzFV-.:S+xM+-VV4FH,@CsHQ<8hz2$(~2@5|k!`]fqb)6[x7L+bZe!PFPDtNqpg4');
define('SECURE_AUTH_KEY',  '2X8IG;Ao{jV0qcP{cU}Xbr|}+pY[T)(B@E-!?*Wnt}KOp7#>=Hpy0xjRiCc&3*4t');
define('LOGGED_IN_KEY',    ']Dd_uHS]:([wk||Lr(q+D>2VC*n9qD6|2gT8-hHP5[i|~SW~2kAg?CvN]`I )u`g');
define('NONCE_KEY',        'nz%Kn`yP8%`^I:S$%S5;:Nr6S=OF2&o/GPdpcb7q]- bkqm8.I.Nf0,yK?PCY9L1');
define('AUTH_SALT',        'bfO+KE;N/q`C#YvKy*eBoN-ZA0Ufv tq*Cxy5_c_7}IHwuJs Op6#`qtvzs]+s!>');
define('SECURE_AUTH_SALT', ':ih8+;pR*-n_zXqF|v&A-j#6<,xME&KqIvcN~Y$4C5t}~^ITc0bY`1)MnL3UX<*+');
define('LOGGED_IN_SALT',   '+Cul~x`@v@ u;@yq$hzvEHr<e^-ISF9g-~Z0bMI+i:@6X%8FfdQo!@.y$(~Gh*H%');
define('NONCE_SALT',       'R,g*-D BA, L#k`hrdFh7U`OSl.0$BevOh`5z/+v+!D6;ya6MB[or, {T6Z*XRRV');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
