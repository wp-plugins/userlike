<?php
/*
Plugin Name: Userlike
Plugin URI: http://userlike.com
Description: Userlike live chat integration for Wordpress
Version: 1.0
Author: Sven Gebhardt
Author URI: http://userlike.com
License: GPL2

Copyright 2012 Sven Gebhardt <sg@unkreativ.org>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class UserLike {
	static $is_enabled = false;
	static $json;
	static $plugin_dir;

	function init(){
		add_option("userlike_app_key");
		add_option("userlike_secret");
		add_option("userlike_json");
		add_option("userlike_json_expire");
		load_plugin_textdomain('userlike', false, dirname(plugin_basename(__FILE__))); 
		self::$is_enabled = (get_option("userlike_app_key") && get_option("userlike_secret"));
		self::$plugin_dir = get_option('siteurl').'/'.PLUGINDIR.'/userlike/';
		if(function_exists('current_user_can') && current_user_can('manage_options')){
			add_action('admin_menu', array(__CLASS__, 'add_settings_page'));
			add_filter('plugin_action_links', array(__CLASS__, 'register_actions'), 10, 2);
		}
		
		if(self::$is_enabled){
			add_action('wp_footer', array(__CLASS__, 'insert_code'));
		} else {
			add_action('admin_notices', array(__CLASS__, 'admin_notice'));
		}
	}

	function insert_code(){
		/* Insert Userlike javascript code into the page */
		if(!self::$is_enabled) return false;
		$app_key = get_option("userlike_app_key");
		$secret = get_option("userlike_secret");
		$json = self::get_json();
		echo "<script src=\"//userlike.com/static/chat/javascripts/userlike.min.js\"></script><script>userlikeInit('".$app_key."','".$secret."','".$json."');</script>";
	}

	function admin_notice(){
		if(!self::$is_enabled) 
			echo '<div class="error"><p><strong>'.sprintf(__('Userlike integration has not been set up. Please go to the <a href="%s">plugin page</a> and enter your Userlike credentials to enable it.' ), admin_url('options-general.php?page=userlike')).'</strong></p></div>';
	}

	function add_settings_page(){
		add_action('admin_init', array(__CLASS__, 'register_settings'));
		add_submenu_page('options-general.php', 'Userlike', 'Userlike', 'manage_options', 'userlike', array(__CLASS__, 'settings_page'));
	}

	/* Helper Functions */
	function get_json($force=false){
		/* 
			Get the Userlike JSON data from cache, DB or Userlike servers (in that order).
			Set force to true if you want this function to fetch new data regardless of the cached value.
			Note that it will then fail if a JSON store cannot be received.
		 */
		if(self::$json && !$force) return self::$json;
		if(time() < get_option("userlike_json_expire") && !$force)
			$json = get_option("userlike_json");
		else
			$json = false;
		if($json && !$force){
			self::$json = $json;
			return $json;
		} else {
			$secret = get_option("userlike_secret");
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch,CURLOPT_URL,'http://userlike.com/api/chat/widget/config?widget_key='.$secret);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
			$data = curl_exec($ch);
			curl_close($ch);
			if(preg_match("/NOT FOUND/i", $data)){
				if($force)
					return false;
				else 
					return get_option("userlike_json");
			}
			self::$json = base64_encode($data);
			update_option("userlike_json", self::$json);
			update_option("userlike_json_expire", time()+21600); // expires every 6 hours
			return self::$json;
		}
	}

	function register_settings(){
		register_setting('userlike', 'userlike_app_key');
		register_setting('userlike', 'userlike_secret');
		add_settings_section('userlike', 'Userlike', '', 'userlike');
	}

	function register_actions($links, $file){
		$this_plugin = plugin_basename(__FILE__);
		if($file == $this_plugin && function_exists('admin_url')){
			$settings_link = '<a href="'.admin_url('options-general.php?page=userlike').'">'.__('Settings', 'userlike').'</a>';
			array_unshift($links, $settings_link);
		}
		return($links);
	}

	function settings_page(){
		if(self::$is_enabled){
			if(!self::get_json(true)){
				echo '<div id="setting-error-settings_error" class="error settings-error"><p><strong>Could not fetch configuration from Userlike. Please make sure that your credentials are correct! <a href="#" onClick="return userlikeChat();">Click here for live help</a></strong></p></div>';
			}
		}
		$plugin_dir = self::$plugin_dir;
		$in_userlike = true;
		require_once "userlike.admin.php";
	}
}

add_action('init', array('UserLike', 'init'));