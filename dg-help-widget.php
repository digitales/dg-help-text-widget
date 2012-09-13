<?php
/*
Plugin Name: Dg help widget
Description: Widget to display help text to the users.
Version: 1.0
Author: Ross Tweedie
Author URI: http://www.dachisgroup.com/
*/

add_action( 'widgets_init', create_function( '', 'return register_widget( "DgHelpWidget" );' ) );

class DgHelpWidget extends WP_Widget
{
	protected $classes = array();

    protected $number_of_fields = 4;

    function __construct()
	{
		parent::__construct( false, 'DG help widget' );

        if ( ! is_admin() ){
            /**
            * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
            */
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts') );
        }
	}


    /**
     * Enqueue plugin scripts and stylesheets
     */
    function enqueue_scripts() {

        wp_register_style( 'help-css', plugins_url('css/help.css', __FILE__), null, '1', 'all' );
        wp_enqueue_style( 'help-css' );

        wp_register_script( 'help-js', plugins_url('js/help.js', __FILE__), 'jquery', '1', true );
        wp_enqueue_script( 'help-js' );
    }


    function widget($args,$instance)
	{
        extract( $args );

        $widget_title = apply_filters( 'widget_title', $instance['title'] );

        echo '<div ' . $this->get_classes() . '>';

            echo $before_widget;

            if ( isset( $widget_title ) && !empty( $widget_title ) ){
                echo $before_title . __( $title ) . $after_title;
            }


            if ( $this->number_of_fields ):
                for( $i= 1; $i < $this->number_of_fields; $i++  ):
                    if ( isset( $instance['title_' . $i ] ) && !empty( $instance['title_' . $i ] ) && $instance['title_' . $i ] !=='' ):
                    ?>
                        <div class="help-box">
                            <h5 class="title"><?php echo $instance['title_' . $i ]; ?></h5>
                            <div class="info">
                                <?php echo wpautop( $instance['description_' . $i ] );?>
                            </div>
                        </div>
                    <?php
                    endif;
                endfor;
            endif;

            echo $after_widget;
        echo  '</div>';
	}


    /**
     * Update the data
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update($new_instance, $old_instance) {
	    // create a back up we can access it when updating content
	    $instance       = $old_instance;

        if ( $this->number_of_fields ):
            for( $i= 1; $i < $this->number_of_fields; $i++  ):
                $instance['title_' . $i ]           = strip_tags($new_instance['title_' . $i ] );
                $instance['description_' . $i ]     = strip_tags($new_instance['description_' . $i ], '<p><a><b><em><span>');
            endfor;
        endif;

	    return $instance;
	}


    /** @see WP_Widget::form */
	function form($instance) {

        if ( $this->number_of_fields ):
            for( $i= 1; $i <= $this->number_of_fields; $i++  ):
            ?>
                <p>
                    <label for="<?php echo $this->get_field_id( 'title_'. $i ); ?>"><?php _e( 'Title ' . $i .':' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title_' . $i ); ?>" name="<?php echo $this->get_field_name( 'title_' . $i ); ?>" type="text" value="<?php echo esc_attr( $instance['title_' . $i ] ) ; ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('description_' . $i ); ?>"><?php _e( 'Text ' . $i .':' ); ?></label>
                    <textarea class="widefat" id="<?php echo $this->get_field_id( 'description_' . $i ); ?>" name="<?php echo $this->get_field_name( 'description_' . $i ); ?>"><?php echo esc_attr( $instance['description_' . $i ] ); ?></textarea>
                </p>
            <?php
            endfor;
        endif;
	}


    function get_classes()
	{
		return (count($this->classes)) ? 'class="'.implode(' ',$this->classes).'"' : '';
	}

}
