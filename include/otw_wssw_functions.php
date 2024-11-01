<?php

/**
 * Init function
 */
if( !function_exists( 'otw_wssw_init' ) ){
	
	function otw_wssw_init(){
		
		global $otw_wssw_plugin_url, $otw_wssw_plugin_options, $otw_wssw_shortcode_component, $otw_wssw_shortcode_object, $otw_wssw_form_component, $otw_wssw_validator_component, $otw_wssw_form_object, $wp_wssw_cs_items, $otw_wssw_js_version, $otw_wssw_css_version, $wp_widget_factory;
		
		if( is_admin() ){
			
		
			add_action('admin_menu', 'otw_wssw_init_admin_menu' );
			
			add_action('admin_print_styles', 'otw_wssw_enqueue_admin_styles' );
			
			add_action('admin_enqueue_scripts', 'otw_wssw_enqueue_admin_scripts');
		}
		otw_wssw_enqueue_styles();
		
		include_once( plugin_dir_path( __FILE__ ).'otw_wssw_dialog_info.php' );
		
		//shortcode component
		$otw_wssw_shortcode_component = otw_load_component( 'otw_shortcode' );
		$otw_wssw_shortcode_object = otw_get_component( $otw_wssw_shortcode_component );
		$otw_wssw_shortcode_object->js_version = $otw_wssw_js_version;
		$otw_wssw_shortcode_object->css_version = $otw_wssw_css_version;
		$otw_wssw_shortcode_object->editor_button_active_for['page'] = true;
		$otw_wssw_shortcode_object->editor_button_active_for['post'] = true;
		
		$otw_wssw_shortcode_object->add_default_external_lib( 'css', 'style', get_stylesheet_directory_uri().'/style.css', 'live_preview', 10 );
		
		if( isset( $otw_wssw_plugin_options['otw_wssw_theme_css'] ) && strlen( $otw_wssw_plugin_options['otw_wssw_theme_css'] ) ){
			
			if( preg_match( "/^http(s)?\:\/\//", $otw_wssw_plugin_options['otw_wssw_theme_css'] ) ){
				$otw_wssw_shortcode_object->add_default_external_lib( 'css', 'theme_style', $otw_wssw_plugin_options['otw_wssw_theme_css'], 'live_preview', 11 );
			}else{
				$otw_wssw_shortcode_object->add_default_external_lib( 'css', 'theme_style', get_stylesheet_directory_uri().'/'.$otw_wssw_plugin_options['otw_wssw_theme_css'], 'live_preview', 11 );
			}
		}
		
		if( isset( $wp_widget_factory->widgets ) && count( $wp_widget_factory->widgets ) ){
		
			$widgets_order = 30001;
			$widgets_children = array();
			foreach( $wp_widget_factory->widgets as $wp_widget_class => $wp_widget ){
				
				if( preg_match( "/^WP_Widget_/", $wp_widget_class ) ){
					
					if( !in_array( 'widget_shortcode_'.$wp_widget->id_base, $widgets_children ) ){
						
						$otw_wssw_shortcode_object->shortcodes['widget_shortcode_'.$wp_widget->id_base] = array( 'title' => $wp_widget->name ,'enabled' => true,'children' => false, 'parent' => 'wp_widgets', 'order' => $widgets_order++,'path' => dirname( __FILE__ ).'/otw_components/otw_shortcode/', 'url' => $otw_wssw_plugin_url.'include/otw_components/otw_shortcode/', 'dialog_text' => $otw_wssw_dialog_text  );
						$widgets_children[] = 'widget_shortcode_'.$wp_widget->id_base;
					}
				}
			}
			
			if( count( $widgets_children ) ){
				$otw_wssw_shortcode_object->shortcodes['wp_widgets'] = array( 'title' => esc_html__('WordPress Widgets', 'otw_wssw'),'enabled' => true,'children' => $widgets_children, 'parent' => false, 'order' => $widgets_order,'path' => dirname( __FILE__ ).'/otw_components/otw_shortcode/', 'url' => $otw_wssw_plugin_url.'include/otw_components/otw_shortcode/', 'dialog_text' => $otw_wssw_dialog_text  );
			}
		}
		
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_wssw_shortcode_object.labels.php' );
		$otw_wssw_shortcode_object->init();
		
		//form component
		$otw_wssw_form_component = otw_load_component( 'otw_form' );
		$otw_wssw_form_object = otw_get_component( $otw_wssw_form_component );
		$otw_wssw_form_object->js_version = $otw_wssw_js_version;
		$otw_wssw_form_object->css_version = $otw_wssw_css_version;
		
		include_once( plugin_dir_path( __FILE__ ).'otw_labels/otw_wssw_form_object.labels.php' );
		$otw_wssw_form_object->init();
		
		//validator component
		$otw_wssw_validator_component = otw_load_component( 'otw_validator' );
		$otw_wssw_validator_object = otw_get_component( $otw_wssw_validator_component );
		$otw_wssw_validator_object->init();
		
	}
}

/**
 * include needed styles
 */
if( !function_exists( 'otw_wssw_enqueue_styles' ) ){
	function otw_wssw_enqueue_styles(){
		global $otw_wssw_plugin_url, $otw_wssw_css_version;
	}
}


/**
 * Admin styles
 */
if( !function_exists( 'otw_wssw_enqueue_admin_styles' ) ){
	
	function otw_wssw_enqueue_admin_styles(){
		
		global $otw_wssw_plugin_url, $otw_wssw_css_version;
		
		wp_enqueue_style( 'otw_wssw_admin', $otw_wssw_plugin_url.'/css/otw_wssw_admin.css', array( 'thickbox' ), $otw_wssw_css_version );
	}
}

/**
 * Admin scripts
 */
if( !function_exists( 'otw_wssw_enqueue_admin_scripts' ) ){
	
	function otw_wssw_enqueue_admin_scripts( $requested_page ){
		
		global $otw_wssw_plugin_url, $otw_wssw_js_version;
		
	}
	
}

/**
 * Init admin menu
 */
if( !function_exists( 'otw_wssw_init_admin_menu' ) ){
	
	function otw_wssw_init_admin_menu(){
		
		global $otw_wssw_plugin_url;
		
		add_menu_page(__('Widgets as Shortcodes', 'otw_wssw'), esc_html__('Widgets as Shortcodes', 'otw_wssw'), 'manage_options', 'otw-wssw-settings', 'otw_wssw_settings', $otw_wssw_plugin_url.'images/otw-sbm-icon.png');
		add_submenu_page( 'otw-wssw-settings', esc_html__('Settings', 'otw_wssw'), esc_html__('Settings', 'otw_wssw'), 'manage_options', 'otw-wssw-settings', 'otw_wssw_settings' );

	}
}

/**
 * Settings page
 */
if( !function_exists( 'otw_wssw_settings' ) ){
	
	function otw_wssw_settings(){
		require_once( 'otw_wssw_settings.php' );
	}
}



/**
 * Keep the admin menu open
 */
if( !function_exists( 'otw_open_wssw_menu' ) ){
	
	function otw_open_wssw_menu( $params ){
		
		global $menu;
		
		foreach( $menu as $key => $item ){
			if( $item[2] == 'otw-cm-settings' ){
				$menu[ $key ][4] = $menu[ $key ][4].' wp-has-submenu wp-has-current-submenu wp-menu-open menu-top otw-menu-open';
			}
		}
	}
}


?>