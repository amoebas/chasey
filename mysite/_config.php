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

FacebookOAuthController::set_app_id(getenv('FACEBOOK_APP_ID'));
FacebookOAuthController::set_api_secret(getenv('FACEBOOK_APP_SECRET'));

global $databaseConfig;
$databaseConfig['type'] = 'MySQLDatabase';

// Support for Environment configuration
if(getenv('MYSQL_DB_HOST')) {
	$databaseConfig['server'] = getenv('MYSQL_DB_HOST');
	$databaseConfig['username'] = getenv('MYSQL_USERNAME');
	$databaseConfig['password'] = getenv('MYSQL_PASSWORD');
	$databaseConfig['database'] = getenv('MYSQL_DB_NAME');
	
}

Object::add_extension('Member', 'ChaseyMember');