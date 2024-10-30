<?php
	/*
	Plugin Name: Kento Latest Tabs
	Plugin URI: http://pluginspoint.com
	Description: TDisplay Latest/Update/Popular Posts, Reccent Posts and Comments on sidebar.
	Version: 1.4
	Author: pluginspoint
	Author URI: http://pluginspoint.com/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
	*/


	/**********************************************************
	 * Exit if accessed directly
	 **********************************************************/

	if ( ! defined( 'ABSPATH' ) )
	die("Can't load this file directly");

 
	define('KENTO_LATEST_TABS_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
	define('kento_latest_tabs_plugin_dir', plugin_dir_path( __FILE__ ) );


	function kento_latest_tabs_scripts() {
		wp_enqueue_style( 'KENTO_LATEST_TABS_STYLE', KENTO_LATEST_TABS_PLUGIN_PATH.'css/style.css' );		 
	    wp_enqueue_script( 'jquery');
		wp_enqueue_script( 'kento-highlight', KENTO_LATEST_TABS_PLUGIN_PATH.'js/kento-highlight.js', array('jquery'));
		wp_localize_script( 'kento-highlight', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));
	}
	add_action('wp_enqueue_scripts', 'kento_latest_tabs_scripts');

	class Kento_Latest_Tabs_Plugin extends WP_Widget {

		function Kento_Latest_Tabs_Plugin() {
			$widget_ops = array( 'classname' => 'kento-latest-tabs-plugin', 'description' => __('A widget to display Popular posts, latest posts and latest comments.', 'kento_latest_tabs_plugin') );

			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'kento-latest-tabs-widget' );

			parent::__construct( 'kento-latest-tabs-widget', __('Kento Latest Tabs Widget', 'kento_latest_tabs_plugin'), $widget_ops, $control_ops );
		}
		
		function widget( $args, $instance ) {
			extract( $args );

			//Our variables from the widget settings.
	        $title 			= apply_filters('widget_title', $instance['title'] );
	        $limit = ($instance['limit']) ? $instance['limit'] : 5;

			$hi_com_post = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $limit, 'no_found_rows' => true, 'post_status' => 'publish',  'orderby' =>'comment_count', 'ignore_sticky_posts' => true ) ) ); 
			$recent = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $limit, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) ); 

			if ($hi_com_post->have_posts()) :
			echo $before_widget;
			echo '<h2 class="widget-title">'.$title.'</h2>';
			?>
	        <div id="kento-highlight-widget">
	    		<?php
	     		$active_color 	= get_option( 'kento_latest_tabs_active' );
				$hover_color 	= get_option( 'kento_latest_tabs_hover' );
				$img_select 	= get_option( 'kento_latest_tabs_img' );
				$kento_tm_style = get_option( 'kento_thumb_style' );

				if ( ($active_color || $hover_color) || ($active_color && $hover_color)  ){?>
					<style>
						#kento-highlight-widget ul.tabs li.active a{
							background-color: <?php echo $active_color ;?> !important;	
						}
						#kento-highlight-widget ul.tabs li.active:hover a{
						background-color: <?php echo $active_color ;?> !important;
						}
						#kento-highlight-widget ul.tabs li a:hover{
							background-color: <?php echo $hover_color ;?> !important;
						}
						<?php
						if($kento_tm_style == 1){?>
							#kento-highlight-widget .kento-post-thumbnail {
								border-radius: 0%;
							}
						<?php
						} elseif($kento_tm_style == 2){?>
							#kento-highlight-widget .kento-post-thumbnail {
								border-radius: 50%;
								display: block;
								overflow: hidden;
							}
						<?php
						} ?>
					</style>
				<?php } ?>
					<div class="widget-container">
						<div class="widget-top">
							<ul class="tabs post-taps">
								<li class="tabs active">
	                           		<a class="tab1">
										<?php $pop_title = get_option( 'kento_latest_tabs_pop_title' );
	                                    if ( isset($pop_title) && !empty($pop_title) ) echo $pop_title;
											elseif (empty($pop_title)){
												echo 'Popular';
											}
										 ?>
	                                </a>
								</li>
								<li class="tabs">
									<a class="tab2">
	                                    <?php $rp_title = get_option( 'kento_latest_tabs_rp_title' );
										if ( isset($rp_title) && !empty($rp_title) ) echo $rp_title;
											elseif (empty($rp_title)){  
												echo 'Recent' ;
											}
										?>
	                                </a>
								</li>
								<li class="tabs">
									<a class="tab3">
	                                    <?php
										$lc_title = get_option( 'kento_latest_tabs_lc_title' );
										if ( isset($lc_title) && !empty($lc_title) )
										echo $lc_title;
										elseif (empty($lc_title)){  
										echo 'Comments' ;
										} ?>
	                                </a>
								</li>
							</ul>
						</div>
	                 
						<div id="tab1" class="tabs-wrap" style="display:block;">
							<ul> 
	                     	   	<?php 
	                        	while( $hi_com_post->have_posts() ) : $hi_com_post->the_post(); ?>
								<li>
									<div class="kento-post-thumbnail">
										<?php
										if (isset($img_select)){
										if (isset($img_select) && $img_select=='gravatar'){
										 echo get_avatar( get_the_author_meta('email'), '60' );
										} else{
											if(isset($img_select) && $img_select=='post-thumb' && has_post_thumbnail() ){
										  the_post_thumbnail('thumbnail');
											} else{ echo '<img src="' . plugins_url( 'css/images/images.png' , __FILE__ ) . '" > ';}
										}
										} else{
											echo '<img src="' . plugins_url( 'css/images/images.png' , __FILE__ ) . '" > ';	
										}
										?>
									</div>
									<div class="kento-post-details">
										<a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?>
										</a>
										<span class="date"><?php comments_number( 'no comments', 'one comment', '% comments' ); ?> &nbsp;
											<span class="post-date"><?php the_time('d M, Y'); ?></span>
										</span>
									</div>
								</li>
								<?php endwhile; ?>
	                            <?php endif;
								wp_reset_query(); ?>
							</ul>
						</div><!-- end of tab1 -->
	                    
						<div id="tab2" class="tabs-wrap" style="display:none;">
							<ul> <?php if ($recent->have_posts()) : ?>
								<?php  while ( $recent->have_posts() ) : $recent->the_post(); ?>
								<li>
									<div class="kento-post-thumbnail">
										<?php
										if (isset($img_select)){
											if (isset($img_select) && $img_select=='gravatar'){
												echo get_avatar( get_the_author_meta('email'), '60' );
											} else{ 
												if(isset($img_select) && $img_select=='post-thumb' && has_post_thumbnail() ){
											 		 the_post_thumbnail('thumbnail');
												} else{ echo '<img src="' . plugins_url( 'css/images/images.png' , __FILE__ ) . '" > ';}
											}
										}else{
											echo '<img src="' . plugins_url( 'css/images/images.png' , __FILE__ ) . '" > ';	
										}
									?>
									</div>

									<div class="kento-post-details">
										<a href="<?php the_permalink() ?>" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?>
										</a>
										<span class="date">
											<span class="post-date"><?php the_time('d M, Y'); ?></span>
										</span>
									</div>
								</li>
								<?php endwhile; wp_reset_query(); ?>
							</ul>
						</div>		<!-- end of tab2 -->
	                    
	                    <div id="tab3" class="tabs-wrap" style="display:none;">
							<ul>
	                            <?php 
	                            $args = array(
									'status' => 'approve',
									'number' => '5'
								);
								$comments = get_comments($args);
								foreach($comments as $comment) : ?>
	                          	<li>
									<div class="kento-post-thumbnail">
										<?php $GLOBALS['comment'] = $comment; echo get_avatar( $comment, 60 ); ?>
									</div>
									<div class="kento-post-details">
	                                    <a id="dot1" href="<?php echo get_permalink($comment->comment_post_ID); ?>#comment-<?php echo $comment->comment_ID; ?>"><?php $str = $comment->comment_content; 
												$len_count = strlen($str);
											if ( $len_count >=51){
													echo '<b>'.($comment->comment_author . ':</b> ' .substr($str,0,50).'...' ) ;
											} else {
												echo '<b>'.($comment->comment_author . ':</b> ' .$str ) ; 
											} ?>
										</a>
									</div>
								</li>
	                            <?php endforeach; ?>
							</ul>
						</div>
					</div>
			</div>
	        <?php
			echo $after_widget;
			wp_reset_postdata();
		 endif;
		}


		//Update the widget 
		 
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] 			= strip_tags( $new_instance['title'] );
			$instance['limit'] 			= strip_tags( $new_instance['limit'] );
			return $instance;
		}

	    function form($instance) {
	      if(!isset($instance['title'])) $instance['title'] = __('Latest Tab' , 'dsf');
	      if(!isset($instance['limit'])) $instance['limit'] = 5;
	    	?>
	      <b><label style="width:100px;" for="<?php echo $this->get_field_id('title'); ?>">
	      <?php _e('Title ','dsf') ?></label></b>
	      <br />
	      <input type="text" style="float:left; clear: both; width: 100%; padding: 3px;" value="<?php echo esc_attr($instance['title']); ?>" name="<?php echo $this->get_field_name('title'); ?>" id="<?php $this->get_field_id('title'); ?>" />
	      <br />
	      <b><label style="width:100px;" for="<?php echo $this->get_field_id('limit'); ?>">
	      <?php _e('Limit Posts Number ','dsf') ?></label></b>
	      <br />
	      <input type="text" style="float:left; clear: both; width: 100%; padding: 3px;" value="<?php echo esc_attr($instance['limit']); ?>" name="<?php echo $this->get_field_name('limit'); ?>" id="<?php $this->get_field_id('limit'); ?>" />
	      <br />
	    	<?php
	    }
	}

	/////////////////////////////////////////////
	///////////  Admin Menu Page ////////////////
	/////////////////////////////////////////////

	add_action('admin_init', 'kento_latest_tabs_init' );
	add_action('admin_menu', 'kento_latest_tabs_admin_page');


	function kento_latest_tabs_admin_page() {
		add_menu_page(__('Kento Latest Tabs','kentothemes'), __('Kento Latest Tabs','kentothemes'), 'manage_options', 'latesttabssettings', 'kh_settings_page');
	}

	function kh_settings_page(){
		include('admin-page.php');	
	}

	function kento_latest_tabs_init(){
        register_setting( 'kento_highlight_plugin_options', 'kento_latest_tabs_active' );
		register_setting( 'kento_highlight_plugin_options', 'kento_latest_tabs_hover' );
		register_setting( 'kento_highlight_plugin_options', 'kento_latest_tabs_img' );
		register_setting( 'kento_highlight_plugin_options', 'kento_latest_tabs_pop_title' );
		register_setting( 'kento_highlight_plugin_options', 'kento_latest_tabs_rp_title' );
		register_setting( 'kento_highlight_plugin_options', 'kento_latest_tabs_lc_title' );
	}
	add_action( 'admin_enqueue_scripts', 'enqueue_color_picker' );

	function enqueue_color_picker( $hook_suffix ) {
		// first check that $hook_suffix is appropriate for your admin page
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'my-script-handle', KENTO_LATEST_TABS_PLUGIN_PATH.'js/pic-color.js', array( 'wp-color-picker' ), false, true );
	}

	//Register Latest Tab Widget
	if (!function_exists('kento_latest_tabs_plugin')) {
		function kento_latest_tabs_plugin() {
			register_widget( 'Kento_Latest_Tabs_Plugin' );
		}
		add_action( 'widgets_init', 'kento_latest_tabs_plugin' );
	}