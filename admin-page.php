<?php

	    if( !defined( 'ABSPATH' ) ){
	        exit;
	    }

		if(isset($_POST['oscimp_hidden']) == 'Y') {
			//Form data sent
			$active_color = $_POST['kento_latest_tabs_active'];
			update_option('kento_latest_tabs_active', $active_color);

			$hover_color = $_POST['kento_latest_tabs_hover'];
			update_option('kento_latest_tabs_hover', $hover_color);

			$kento_latest_tabs_img = stripslashes_deep($_POST['kento_latest_tabs_img']);
			update_option('kento_latest_tabs_img', $kento_latest_tabs_img);	

			$kento_thumb_style = stripslashes_deep($_POST['kento_thumb_style']);
			update_option('kento_thumb_style', $kento_thumb_style);

			$pop_title = $_POST['kento_latest_tabs_pop_title'];
			update_option('kento_latest_tabs_pop_title', $pop_title);

			$rp_title = $_POST['kento_latest_tabs_rp_title'];
			update_option('kento_latest_tabs_rp_title', $rp_title);

			$lc_title = $_POST['kento_latest_tabs_lc_title'];
			update_option('kento_latest_tabs_lc_title', $lc_title);

			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.' ); ?></strong></p></div>
			<?php
		} else {
			//Normal page display
			$active_color 			= get_option( 'kento_latest_tabs_active' );
			$hover_color 			= get_option( 'kento_latest_tabs_hover' );
			$kento_latest_tabs_img 	= get_option( 'kento_latest_tabs_img' );	
			$kento_thumb_style 		= get_option( 'kento_thumb_style' );	
			$pop_title 				= get_option( 'kento_latest_tabs_pop_title' );
			$rp_title 				= get_option( 'kento_latest_tabs_rp_title' );
			$lc_title 				= get_option( 'kento_latest_tabs_lc_title' );
		}
		?>
		<div class="wrap">
			<div id="icon-tools" class="icon32"></div><?php echo "<h2>" . __( 'Kento Latest Tabs Settings') . "</h2>"; ?>
			<form name="kento_latest_tabs" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                <input type="hidden" name="oscimp_hidden" value="Y">

                <?php 
                	settings_fields( 'kento_highlight_plugin_options' ); //related with register_setting in index.php
                    do_settings_sections( 'kento_highlight_plugin_options' );
                ?>

                <table class="form-table">
               		<tr valign="top">
                        <th scope="row"><label for="pop_title"><?php echo __('Title for Popular Posts Tab'); ?>: </label></th>
                        <td style="vertical-align:middle;"><input name="kento_latest_tabs_pop_title" id="pop_title" type="text" value="<?php if ( isset( $pop_title ) ) echo $pop_title; ?>" /><span> Example: 'Popular'.</span></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="rp_title"><?php echo __('Title for Recent Posts Tab'); ?>: </label></th>
                        <td style="vertical-align:middle;"><input name="kento_latest_tabs_rp_title" id="rp_title" type="text" value="<?php if ( isset( $rp_title ) ) echo $rp_title; ?>" /><span> Example: 'Recent'.</span></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="lc_title"><?php echo __('Title for Latest Comments Tab'); ?>: </label></th>
                        <td style="vertical-align:middle;"><input name="kento_latest_tabs_lc_title" id="lc_title" type="text" value="<?php if ( isset( $lc_title ) ) echo $lc_title; ?>" /><span> Example: 'Comments'.</span></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                        	<label for="link_color"><?php echo __('Active Tab\'s Background Color '); ?>: </label>
                        </th>
                        <td style="vertical-align:middle;">
                        	<input name="kento_latest_tabs_active" id="active-color" type="text" value="<?php if ( isset( $active_color ) ) echo $active_color; ?>" />
                        </td>
                    </tr>

                  	<tr valign="top">
                        <th scope="row"><label for="link_color"><?php echo __('Tabs Hover Color '); ?></label></th>
                        <td style="vertical-align:middle;"><input name="kento_latest_tabs_hover" id="hover-color" type="text" value="<?php if ( isset( $hover_color ) ) echo $hover_color; ?>" /></td>
                    </tr>

			        <tr valign="top">
			            <th scope="row"><label for="kento_latest_tabs_img"><?php echo __('Display Thumbnails Image '); ?></label></th>
			            <td style="vertical-align:middle;">
				            <select name="kento_latest_tabs_img">
				                <option value="post-thumb" <?php if($kento_latest_tabs_img=='post-thumb') echo "selected"; ?> >Post Thumbnails</option>
				                <option value="gravatar" <?php if($kento_latest_tabs_img=='gravatar') echo "selected"; ?> >Post Author Thumbnails</option>
				            </select>
			            </td>
					</tr>

			        <tr valign="top">
			            <th scope="row"><label for="kento_thumb_style"><?php echo __('Thumbnails Style '); ?></label></th>
			            <td style="vertical-align:middle;">
				            <select name="kento_thumb_style">
				                <option value="1" <?php if($kento_thumb_style=='1') echo "selected"; ?> >Square Style</option>
				                <option value="2" <?php if($kento_thumb_style=='2') echo "selected"; ?> >Round Style</option>
				            </select><br />
				            <span><?php echo __('Choose Latest Tab Thumbnails Style.'); ?> </span>
			            </td>
					</tr>

                </table> 
                <p class="submit">
                    <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes' ) ?>" />
                </p>
			</form>
		</div>