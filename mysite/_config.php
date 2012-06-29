<?php

global $project;
$project = 'mysite';

global $database;
$database = 'SS_chasey';

require_once('conf/ConfigureFromEnv.php');

MySQLDatabase::set_connection_charset('utf8');

// Set the current theme. More themes can be downloaded from
// http://www.silverstripe.org/themes/
SSViewer::set_theme('simple');

// Set the site locale
i18n::set_locale('en_US');

// Enable nested URLs for this site (e.g. page/sub-page/)
if (class_exists('SiteTree')) SiteTree::enable_nested_urls();

FacebookConnect::set_app_id(getenv('FACEBOOK_APP_ID'));
FacebookConnect::set_api_secret(getenv('FACEBOOK_APP_SECRET'));
FacebookConnect::set_lang('en_US');

global $databaseConfig;
// Support for Environment configuration
if(getenv('MYSQL_DB_HOST')) {
	$databaseConfig['type'] = 'MySQLDatabase';
	$databaseConfig['server'] = getenv('MYSQL_DB_HOST');
	$databaseConfig['username'] = getenv('MYSQL_USERNAME');
	$databaseConfig['password'] = getenv('MYSQL_PASSWORD');
	$databaseConfig['database'] = getenv('MYSQL_DB_NAME');
	
}