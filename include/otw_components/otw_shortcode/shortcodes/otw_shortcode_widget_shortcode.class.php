<?php
class OTW_Shortcode_Widget_Shortcode extends OTW_Shortcodes{
	
	/**
	 * Imported shortcode options key
	*/
	public $widget_shortcodes_option = 'otw_widget_shortcodes';
	
	/**
	 * saved info about the custom shortcode
	*/
	private $widget_shortcode_data = array();
	
	public function __construct(){
		
		$this->has_custom_options = false;
		
		$this->has_preview = false;
		
		parent::__construct();
		
		$wp_widgets = $this->get_wp_widgets();
		
		if( !$this->shortcode_key ){
			
			if( otw_get( 'shortcode', false ) ){
				
				$this->shortcode_key = otw_get( 'shortcode', '' );
			}
			
		}
		if( $this->shortcode_key ){
		
			if( preg_match( "/^widget_shortcode_/", $this->shortcode_key ) ){
				$key = preg_replace( "/^widget_shortcode_/", "", $this->shortcode_key );
				
				if( is_array( $wp_widgets ) && isset( $wp_widgets[ $key ] ) ){
					$this->widget_shortcode_data = $wp_widgets[ $key ];
				}
			}
		}
	}
	
	/**
	 * Get wigets
	 */
	public function get_wp_widgets(){
	
		global $wp_widget_factory;
		
		$widgets = array();
		
		if(  count( $wp_widget_factory->widgets ) ){
			
			foreach( $wp_widget_factory->widgets as $wp_widget_class => $wp_widget ){
				
				if( preg_match( "/^WP_Widget_/", $wp_widget_class ) || preg_match( "/^WP_Nav_Menu_Widget/", $wp_widget_class ) ){
					
					if( !array_key_exists( $wp_widget->id_base, $widgets ) ){
						$widgets[ $wp_widget->id_base ] = $wp_widget;
					}
				}
			}
		}
		return $widgets;
	}
	
	/**
	 * Shortcode admin interface
	 */
	public function build_shortcode_editor_options(){
		
		$html = '';
		
		$source = array();
		if( otw_post( 'shortcode_object', false, array(), 'json' ) ){
			$source = otw_post( 'shortcode_object', array(), array(), 'json' );
		}
		
		if( isset( $this->widget_shortcode_data->id_base ) ){
			$object = $this->widget_shortcode_data;
			$instance = false;
			
			if( is_array( $source ) && count( $source ) ){
				
				$instance = array();
				foreach( $source as $source_key => $source_data ){
					$instance[ str_replace( 'otw-shortcode-element-', '', $source_key ) ] = $source_data;
				}
				
				$instance = $this->rewrite_widget_values( $this->widget_shortcode_data->id_base, $instance );
			}
			
			ob_start();
			$object->form( $instance );
			
			$html .= ob_get_contents();
			
			//replace field names
			$html = str_replace( 'widget-'.$object->id.'-', 'otw-shortcode-element-', $html );
			$html = preg_replace( "/\"".$object->id_base."\-(.*)\-".$object->number."\"/", "otw-shortcode-element-$1", $html );
			
			ob_end_clean();
		}
		
		return $html;
	}
	
	public function rewrite_widget_values( $id_base, $instance ){
		
		switch( $id_base ){
			
			case 'rss':
					if( isset( $instance['show-summary'] ) ){
						$instance['show_summary'] = $instance['show-summary'];
					}
					if( isset( $instance['show-author'] ) ){
						$instance['show_author'] = $instance['show-author'];
					}
					if( isset( $instance['show-date'] ) ){
						$instance['show_date'] = $instance['show-date'];
					}
				break;
		}
		
		return $instance;
	}
	
	/**
	 * Shortcode admin interface custom options
	 */
	public function build_shortcode_editor_custom_options(){
		
		$html = '';
		
		$source = array();
		if( otw_post( 'shortcode_object', false, array(), 'json' ) ){
			$source = otw_post( 'shortcode_object', array(), array(), 'json' );
		}
		
		return $html;
	}
	
	/** build shortcode
	 *
	 *  @param array
	 *  @return string
	 */
	public function build_shortcode_code( $attributes ){
		
		$code = '';
		
		if( !$this->has_error ){
		
			$code = '[otw_shortcode_widget_shortcode';
			
			foreach( $attributes as $att_key => $att_value ){
			
				if( $att_key != 'shortcode_code' ){
					$code .= $this->format_attribute( str_replace( '-', '_', $att_key ), $att_key, $attributes, false, '', true );
				}
			}
			
			$code .= ']';
			
			
			$code .= '[/otw_shortcode_widget_shortcode]';
			
		}
		
		return $code;
	}
	
	/**
	 * Display shortcode
	 */
	public function display_shortcode( $attributes, $content ){
		
		$html = '';
		
		$widgets = $this->get_wp_widgets();
		
		if( preg_match( "/^widget_shortcode_(.*)/", $this->format_attribute( '', 'shortcode_type', $attributes, false, '' ), $matches ) ){
			
			$widget_id = $matches[1];
			
			if( isset( $widgets[ $widget_id ] ) ){
				
				ob_start();
				$attributes = $this->rewrite_widget_values( $widget_id, $attributes );
				the_widget( get_class( $widgets[ $widget_id ] ), $attributes );
				$html .= ob_get_contents();
				ob_end_clean();
			}
		}
		
		return $this->format_shortcode_output( $html );
	}
}