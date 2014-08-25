<?php
/*
Plugin Name: 360i Velocity
Plugin URI: https://github.com/stal2k/360iVelocity
Description: Mashable Velocity WP Integration
Version: 0.0.5
Author: Greg Garritani
Author URI: http://www.gtek-sc.com
License: GPL2 or later
*/
/*
You should have received a copy of the GNU General Public License
along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*/
if ( basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    exit( 'This page cannot be called directly.' );
}
/**
 * Plugin file constants
 */
define( 'M360IVELOCITY_PLUGIN_SLUG', plugin_basename( __FILE__ ) );
define( 'M360IVELOCITY_PLUGIN_FILE', __FILE__ );
define( 'M360IVELOCITY_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'M360IVELOCITY_DOMAIN', dirname( M360IVELOCITY_PLUGIN_SLUG ) );
/**
 * Start M360IVELOCITY
 */
require_once( 'core/M360IVelocity.class.php' );
