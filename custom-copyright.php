<?php
/*
 *
Plugin Name: Custom Copyright
Plugin URI: http://example.com/wordpress-plugins/my-plugin
Description: Acording the word in the title to add a specify copyright message
Version: 0.1
Author:wenzizone
Author URI: http://www.wenzizone.com
License: GPLv2

Copyright 2011  wenzizone  (email : wenzizone@126.com)

This file is part of Custom Copyright

Custom Copyright is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Custom Copyright is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with BackTo Top. If not, see <http://www.gnu.org/licenses/>.
*/
define('WZ_CC_VERSION', 'V0.1');

if (!class_exists('Customcopyright')) {
	class Customcopyright {
		var $wz_cc_init_option = array(	'pHeader' => true,
											'pFooter' => false );
		//构造函数
		function Customcopyright() {
			define('WZ_CC_OPTIONS', 'CustomCopyrightAdminOptions');

			add_action('activate_' . basename(dirname(__FILE__)) . 'custom-copyright.php', array(&$this, 'wz_cc_onActive'));
			add_filter('the_content', array(&$this, 'mycustomcopyright'));
			add_action('admin_menu', array(&$this, 'wz_cc_add_custom_copyright_options_page'));
		}

		//插件激活时加载
		function wz_cc_onActive() {
			$wz_cc_adminoption = $this->wz_cc_getAdminOptions();
			update_option(WZ_CC_OPTIONS, $wz_cc_adminoption);
		}
		//插件删除时加载
		function deActive() {

		}
		//获取参数配置
		function wz_cc_getAdminOptions(){
			$wz_cc_adminoption = $this->wz_cc_init_option;
			//获取参数
			$wz_cc_options = get_option(WZ_CC_OPTIONS);
			//覆盖默认参数
			if(!empty($wz_cc_options)){
				foreach($wz_cc_options as $wz_cc_key => $wz_cc_option){
					$wz_cc_adminoption[$wz_cc_key] = $wz_cc_option;
				}
			}
			return $wz_cc_adminoption;
		}
		function mycustomcopyright($content='') {
			if (is_single() || is_feed()) {

				$postId = get_the_ID();
				$grand_parent = get_page($post->post_parent);
				$post_link = get_permalink($grad_parent);
				$post_title = get_the_title($post->post_parent);
				//$findstr = get_options( "keys");
				$pos = strpos($post_title, "[转]");

				if ($pos) {
					$output = '';
				} else {
					$output = "<strong>版权所有，转载请注明，转载自：<a href=\"" . get_settings('home') . "\">" . get_bloginfo('sitename') . "</a></strong><br />";
					$output .= "<strong>本文链接：</strong><a href=\"" . get_permalink() . '"title="' . $post_title . '">' . $post_title . '</a>' . '<br />';

				}
				$content = $output . $content;
			}
			//$content .= $content;
			return $content;
		}
		//生成管理页面
		function wz_cc_add_custom_copyright_options_page(){
			if(function_exists('add_options_page')){
				add_options_page('custom-copyright-option-page','自定义版权信息',9,basename( __FILE__ ),array(&$this, 'wz_cc_adminpage' ));
			}
		}

		function addContent($content=''){
			$content.="<p>Devlounge Was Here</p>";
			return $content;
		}

		function wz_cc_adminpage() {
			$wz_cc_options = $this->wz_cc_getAdminOptions();
			?>
<div class="wrap">
	<form method="post" id="back2top_form"
		action="<?php echo $GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI']; ?>">
		<h1>
			根据标题添加版权信息插件
			<?php echo WZ_CC_VERSION;?>
			设置
		</h1>
		<p>
			作者：<a href="http://www.wenzizone.cm">深夜的蚊子</a>,欢迎访问我的<a
				href="http://www.wenzizone.com">博客</a>。
		</p>
		<p>		
		<table>
			<tr>
				<td><input type="radio" id="p-header" name="wz_cc_position"
					value="p-header"
					<?php if($wz_cc_options['pHeader'] == 'true') echo 'checked="checked"'; ?>>在文章顶部添加</td>
				<td><input type="radio" id="p-footer" name="wz_cc_position"
					value="p-footer"
					<?php if($wz_cc_options['pFooter'] == 'true') echo 'checked="checked"'; ?>>在文章底部添加</td>
			</tr>
			<tr>
				<td valign="top" width="30%"><strong><?php _e('Header Text:', 'wp-posturl'); ?>
				</strong><br />

				<?php _e('This text will be inserted at beginning of your post if not empty.', 'wp-posturl'); ?><br /><br />
                    <?php _e('Allowed Variables:', 'wp-posturl'); ?>
                    <ul>
                        <li><?php _e('<code>%site_url%</code> - the URL of your site', 'wp-posturl'); ?></li>
                        <li><?php _e('<code>%site_name%</code> - the name of your site', 'wp-posturl'); ?></li>
                        <li><?php _e('<code>%post_url%</code>  - the URL of a post', 'wp-posturl'); ?></li>
                        <li><?php _e('<code>%post_title%</code> -the title of a post', 'wp-posturl'); ?></li>
                    </ul>
                </td>
				<td><textarea name="header_text" id="header_text" cols="64"
						rows="10">
						<?php echo htmlspecialchars(stripslashes($posturl_options['header_text']), ENT_QUOTES); ?></textarea><br />
				</td>
			</tr>
		</table>
		<p>
			<input type="submit" name="save-options" value="保存配置" /> <input
				type="submit" name="reset-options" value="重置配置" />
		</p>
	</form>
</div>
<?php
		}
	} //end class
} //end if

if (class_exists('Customcopyright')) {
	$CC = new Customcopyright();
}
//生成管理页面
if(!function_exists('add_custom_copyright_options_page')){

}
?>

