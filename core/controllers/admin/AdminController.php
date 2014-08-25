<?php

class M360IVelocityAdminController {

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
