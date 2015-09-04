<?php
/*
	Plugin Name: Twitter連携チェックボックス
	Plugin URI:
	Description: Twitter連携チェックボックス
	Version: 1.0
	Author: T-Maru
	Author URI: http://t-maru.net/
	License: srag

	Copyright 2015/05/09 T-Maru
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
use Abraham\TwitterOAuth\TwitterOAuth;
//------チェックボックス追加----------
add_action( 'post_submitbox_misc_actions', 'add_Twitter_post_submitbox_misc_actions' );

function add_Twitter_post_submitbox_misc_actions(){ ?>

	<?php
		if(strstr($_SERVER['PHP_SELF'], 'post-new.php')){
			$Linkvalue = "checked =\"checked\"";
		}
	?>
	<div class = "misc-pub-section">
		<input type ="checkbox" name="checkTwitterLink" <?php echo $Linkvalue ?>> <?php _e('Twitterに連携');?> </input>
	</div>
<?php
}?>
<?php
//-------投稿時の処理-----------
add_action('pre_post_update','TwitterLink');
function TwitterLink(){
	if($_POST['checkTwitterLink'] != null){
		require(dirname(__FILE__).'/twitteroauth/autoload.php');

		// OAuthオブジェクト生成
		$param = twitterparam();
		$connection = new TwitterOAuth($param[consumer_key],$param[consumer_secret],$param[access_token],$param[access_token_secret]);

		$postMessage = $param[title] . $_POST['post_title'] . " ";
		$url =  $postMessage . get_permalink();
		//ツイート
		$res = $connection->post("statuses/update", array("status" => $url));
	}
}

function twitterparam()
{
	$param = array(
					 consumer_key        => "consumer_key"
					,consumer_secret     => "consumer_secret"
					,access_token        => "access_token"
					,access_token_secret => "access_token_secret"
					,title               => "【ブログを更新しました】"
					);
	return $param;
}
?>