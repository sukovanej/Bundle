<?php
	class InstallTheme {
		public function Install() {
			HConfiguration::Create("bootstrap_theme_config_show_jumbotron", "1");
			HConfiguration::Create("bootstrap_theme_config_show_jumbotron_title", "My website");
			HConfiguration::Create("bootstrap_theme_config_show_jumbotron_text", '<p><img alt="" src="http://icons.iconarchive.com/icons/svengraph/i-love/512/Box-icon.png" style="float:left; height:202px; margin-right:20px; width:202px" />Lorem ipsum dolor sit amet <a href="#">dolor</a>! Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam quis nulla. In dapibus augue <em>non sapien</em>. Mauris metus. Aenean id metus id velit ullamcorper pulvinar. <strong>Phasellus faucibus</strong> molestie <a href="#">ipsum dolor</a>. Etiam bibendum elit eget erat. Vivamus porttitor turpis ac leo. Itaque earum rerum</p>');
		}
	}
