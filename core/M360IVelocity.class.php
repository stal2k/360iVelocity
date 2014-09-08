<?php
/**
 * Autoload classes
 */
spl_autoload_register('M360IVelocity::autoload');
/**
 * Plugin Version Constant
 */
define('M360IVELOCITY_VERSION', M360IVelocity::get_plugin_data(M360IVELOCITY_PLUGIN_FILE
)->Version);

class M360IVelocity {

	protected static $instance;

	/**
	 * Get M360IVelocity instance.
	 *
	 * @return M360IVelocity instance
	 */
	public static function get_instance() {
		null === self::$instance && self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Callback static method for spl_autoload_register.
	 *
	 * @param $class Class name to load.
	 */
	public static function autoload($class) {
		if ('M360IVelocity' !== mb_substr($class, 0, 13)) {
			return;
		}
		$path = M360IVELOCITY_PLUGIN_DIR_PATH;
		$file = str_replace('M360IVelocity', '', $class);
		if (strpos($class, 'Admin') !== false) {
			require_once("{$path}core/controllers/admin/$file.php");
		} else if (strpos($class, 'Front') !== false) {
			require_once("{$path}core/controllers/front/$file.php");
		} else if (strpos($class, 'View') !== false) {
			require_once("{$path}core/controllers/$file.php");
		} else {
			require_once("{$path}core/lib/$file.php");
		}
	}

	/**
	 * Get plugin header data.
	 *
	 * @param string $file Absolute path to main plugin file which contains the header.
	 *
	 * @return object The plugin data fetched from the main file header.
	
	public static function get_plugin_data($file = __FILE__) {
		$default_headers = array('Name'        => 'Plugin Name',
		                         'PluginURI'   => 'Plugin URI',
		                         'Version'     => 'Version',
		                         'Description' => 'Description',
		                         'Author'      => 'Author',
		                         'AuthorURI'   => 'Author URI',
		                         'TextDomain'  => 'Text Domain',
		                         'DomainPath'  => 'Domain Path',
		                         'Network'     => 'Network',
			// Site Wide Only is deprecated in favor of Network.
		                         '_sitewide'   => 'Site Wide Only',
		);

		return (object)get_file_data($file, $default_headers, 'plugin');
	}

	public function M360IVelocity() {
		$this->__construct();
	}

	public function __construct() {
		;
	}
 */
	/**
	 * Initialize plugin
	 */
	public function init() {
        load_plugin_textdomain( M360IVELOCITY_DOMAIN, false, M360IVELOCITY_DOMAIN . '/lang/' );
		add_action('admin_menu', array(M360IVelocityAdminController::get_instance(), 'admin_menu'));
		add_action('admin_init',
		           array(M360IVelocityAdminController::get_instance(), 'm360ivelocity_settings')
		);
	}
}
class AdminController {

    protected static $instance;

    protected $top_level_hook_suffix;

    protected $dashboard_hook_suffix;

    protected $admin_settings_hook_suffix;

    protected $admin_page_slug;

    protected $plugin_options_key;

    /**
     * Get 360iVelocityAdminController instance.
     *
     * @return 360iVelocityAdminController
     */
    public static function get_instance() {
        null === self::$instance && self::$instance = new self;

        return self::$instance;
    }

    public function M360IVelocityAdminController() {
        $this->__construct();
    }

    public function __construct() {
        $this->admin_page_slug    = 'm360ivelocity';
        $this->plugin_options_key = 'm360ivelocity_settings';
    }

    /**
     * Get the plugin options key.
     *
     * @return string The plugin options key.
     */
    public function get_plugin_options_key() {
        return $this->plugin_options_key;
    }

    /**
     * Add top level and sub menus.
     */
    public function admin_menu() {
        $options     = get_option( $this->plugin_options_key );
        $default_cap = $cap = 'manage_options';
        $user        = wp_get_current_user();
        if ( !empty( $options ) && !empty( $options['allowed_roles'] ) ) {
            $cap = array_intersect( $options['allowed_roles'], (array) $user->roles );
            $cap = ( !empty( $cap ) )
                ? $cap[0]
                : $default_cap;
        }
        // Add parent admin menu
        $this->top_level_hook_suffix = add_menu_page(
            __( 'Dashboard', M360IVELOCITY_DOMAIN ),
            __( '360IVelocity', M360IVELOCITY_DOMAIN ),
            $cap,
            $this->admin_page_slug,
            array( self::get_instance(), 'render_dashboard_page' ),
            plugins_url(
                "images/360i-icon16.png",
                M360IVELOCITY_PLUGIN_FILE
            )
        );
        $this->dashboard_hook_suffix = add_submenu_page(
            $this->admin_page_slug,
            __( 'Dashboard', M360IVELOCITY_DOMAIN ),
            __( 'Dashboard', M360IVELOCITY_DOMAIN ),
            $cap,
            $this->admin_page_slug,
            array( self::get_instance(), 'render_dashboard_page' )
        );

        $this->admin_settings_hook_suffix = add_submenu_page(
            $this->admin_page_slug,
            __( 'Settings', M360IVELOCITY_DOMAIN ),
            __( 'Settings', M360IVELOCITY_DOMAIN ),
            'administrator',
            "$this->admin_page_slug-settings",
            array( self::get_instance(), 'render_settings_page' )
        );

        // Enqueue admin page stylesheet
        add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_page_css' ) );
    }

    /**
     * @param string $hook_suffix Current hook suffix.
     */
    public function admin_page_css( $hook_suffix = "" ) {
        switch ( $hook_suffix ) {
            case $this->top_level_hook_suffix:
            case $this->dashboard_hook_suffix:
            case $this->admin_settings_hook_suffix:
                wp_enqueue_style(
                    "$this->admin_page_slug-admin-style",
                    plugins_url( 'css/admin-styles.css', M360IVELOCITY_PLUGIN_FILE )
                );
                break;
        }
    }

    /**
     * Register and add settings secion & fields.
     */
    public function m360ivelocity_settings() {
        register_setting(
            'm360ivelocity_settings',
            'm360ivelocity_settings',
            array( self::get_instance(), 'm360ivelocity_settings_validate' )
        );
        add_settings_section(
            'm360ivelocity_allowed_roles',
            __( 'User Permissions', M360IVELOCITY_DOMAIN ),
            '__return_false',
            'm360ivelocity-plugin-settings-section'
        );
        add_settings_field(
            'm360ivelocity_user_roles',
            __( 'Allow Dashboard access for the following user roles:', M360IVELOCITY_DOMAIN ),
            array( self::get_instance(), 'm360ivelocity_settings_fields' ),
            'm360ivelocity-plugin-settings-section',
            'm360ivelocity_allowed_roles',
            array( 'field' => 'set_user_roles' )
        );
        do_action( 'm360ivelocity_settings_field' );
    }

    /**
     * Render input fields.
     *
     * @param array $args
     */
    public function m360ivelocity_settings_fields( $args = array() ) {
        if ( !empty( $args ) ) {
            $options = get_option( $this->plugin_options_key );
            switch ( $args['field'] ) {
                case 'set_user_roles':
                    $roles = get_editable_roles();
                    foreach ( $roles as $role => $data ) {
                        $checked = '';
                        if ( $role === 'administrator' ) {
                            continue;
                        }
                        if ( !empty( $options ) && !empty( $options['allowed_roles'] ) && in_array(
                                $role,
                                $options['allowed_roles']
                            )
                        ) {
                            $checked = ' checked';
                        }
                        echo "<input type='checkbox' name='m360ivelocity_settings[allowed_roles][]' value='$role' multiple{$checked}> " . $data['name'] . "<br>";
                    }
                    break;
                default:
                    printf( __( "Unknown field: %s", M360IVELOCITY_DOMAIN ), $args['field'] );
            }
        }
    }

    /**
     * Plugin settings sanitize callback.
     *
     * @param array $data Input from POST
     *
     * @return mixed Filtered input array
     */
    public function m360ivelocity_settings_validate( $data ) {
        return $data;
    }
    /** function add_scripts() {

		wp_enqueue_script( 'm360ivelocity', plugin_dir_url( __FILE__ ) . '/core/js/m360ivelocity-post.js', 'jquery' );

		$type = "";

		if ( preg_match( "/youtube\.com\/watch/i", self::$url ) )
			$type = 'video';
		elseif ( preg_match( "/vimeo\.com\/[0-9]+/i", self::$url ) )
			$type = 'video';
		elseif ( preg_match( "/flickr\.com/i", self::$url ) )
			$type = 'photo';

		$data = array(
			'pressThisUrl' => plugin_dir_url( 'M360IVelocity-post.php' ),
			'content' => self::$content,
			'url' => self::$url,
			'urlEncoded' => urlencode( self::$url ),
			'type' => $type
		);

		wp_localize_script( 'm360ivelocity', 'M360IVelocity', $data );
	}
	*/
	
    /**
     * Callback method that renders the plugin 'Dashboard' admin page.
     */
    public function render_dashboard_page() {
        M360IVelocityViewController::get_instance()->make_view( 'admin-m360ivelocity-dashboard' );
    }

    /**
     * Callback method that renders the plugin 'Settings' admin page.
     */
    public function render_settings_page() {
        M360IVelocityViewController::get_instance()->make_view( 'admin-m360ivelocity-settings' );
    }
}
add_action('plugins_loaded', array(M360IVelocity::get_instance(), 'init'));
