<?php

class RemoteSettingsApiPlugin {

	function init() {
		require_once('HasGroups.php');
		require_once('HasSettings.php');
		require_once('Setting.php');
		require_once('SettingGroup.php');
		require_once('SettingPage.php');
		require_once('SettingsSuite.php');
		add_action( 'rest_api_init', [$this, 'rest_api_init']);
	}

	function rest_api_init() {
		register_rest_route( 'remote-settings', '/jetpack', array(
			'methods' => 'GET',
			'callback' => [$this, 'display_jetpack_settings'],
		) );
	}

	function display_jetpack_settings() {
		$suite = new SettingSuite('Jetpack', null, null);
		return $suite
			->add_page($this->build_security_page())
			->add_page($this->build_sharing_page());
	}

	function build_security_page() {
		$securityPage = new SettingPage('Security', 'Your site is protected by Jetpack. You’ll be notified if anything needs attention.');

		$scanning_group = SettingGroup::named('Backups and security scanning')
			->add_setting(new Setting('bool', 'automated_scanning', false));

		$downtime_monitoring_group = SettingGroup::named('Downtime monitoring')
			->add_setting(new Setting('bool', 'downtime_monitoring', false))
			->set_footer_text('Get alerts if your site goes offline. Alerts are sent to your WordPress.com account↗ email address.');

		$firewall_group = SettingGroup::named('Firewall')
		->add_setting(new Setting('bool', 'enable_firewall', false))
		->set_footer_text('Protect your site with Jetpack\'s Web Application Firewall');

		$bruteforce_group = SettingGroup::named('Brute force protection')
		->add_setting(new Setting('bool', 'enable_bruteforce_protection', false))
		->set_footer_text('Prevent bots and hackers from attempting to log in to your website with common username and password combinations.');

		return $securityPage
			->add_group($scanning_group)
			->add_group($downtime_monitoring_group)
			->add_group($firewall_group)
			->add_group($bruteforce_group);
	}

	function build_sharing_page() {
		$sharingPage = new SettingPage('Sharing', 'Share your content to social media, reaching new audiences and increasing engagement.');

		$social_group = SettingGroup::named('Jetpack Social')
			->add_setting(new Setting('bool', 'jetpack_social', false))
			->set_header_text('Enable Jetpack Social and connect your social accounts to automatically share your content with your followers with a single click. When you publish a post, you will be able to share it on all connected accounts.');

		$sharing_group = SettingGroup::named('Sharing Buttons')
			->add_setting(new Setting('bool', 'add_sharing_buttons_to_posts', false))
			->set_header_text('Add sharing buttons so visitors can share your posts and pages on social media with a couple of quick clicks.');

		$liking_group = SettingGroup::named('Like Buttons')
		->add_setting(new Setting('bool', 'add_sharing_buttons_to_posts', false))
		->set_header_text('The Like button is a way for people on WordPress.com to show their appreciation for your content.');

		return $sharingPage
			->add_group($social_group)
			->add_group($sharing_group)
			->add_group($liking_group);
	}
}
