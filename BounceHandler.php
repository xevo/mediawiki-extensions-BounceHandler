<?php
/* BounceHandler Extension to handle email bounces in MediaWiki
*/
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'BounceHandler',
	'author' => array(
			'Tony Thomas',
			'Legoktm',
			'Jeff Green',
		),
	'url' => "https://www.mediawiki.org/wiki/Extension:BounceHandler",
	'descriptionmsg' => 'bouncehandler-desc',
	'version'  => '1.0',
	'license-name' => "GPL V2.0",
);

/* Setup*/
$dir = __DIR__ ;

//Hooks files
$wgAutoloadClasses['BounceHandlerHooks'] =  $dir. '/BounceHandlerHooks.php';

//Register and Load BounceHandler API
$wgAutoloadClasses['ApiBounceHandler'] = $dir. '/ApiBounceHandler.php';
$wgAPIModules['bouncehandler'] = 'ApiBounceHandler';

//Register and Load Jobs
$wgAutoloadClasses['BounceHandlerJob'] = $dir. '/BounceHandlerJob.php';
$wgAutoloadClasses['ProcessBounceEmails'] = $dir. '/ProcessBounceEmails.php';

$wgJobClasses['BounceHandlerJob'] = 'BounceHandlerJob';

//Register Hooks
$wgHooks['UserMailerChangeReturnPath'][] = 'BounceHandlerHooks::onVERPAddressGenerate';

/*Messages Files */
$wgMessagesDirs['BounceHandler'] = $dir. '/i18n';

# Schema updates for update.php
$wgHooks['LoadExtensionSchemaUpdates'][] = 'BounceHandlerHooks::addBounceRecordsTable';

/**
 * VERP Configurations
 * wgEnableVERP - Engales VERP for bounce handling
 * wgVERPalgo - Algorithm to hash the return path address.Possible algorithms are
 * md2. md4, md5, sha1, sha224, sha256, sha384, ripemd128, ripemd160, whirlpool and more.
 * wgVERPsecret - The secret key to hash the return path address
 */
$wgVERPalgorithm = 'md5';
$wgVERPsecret = 'MediawikiVERP';
$wgVERPAcceptTime = 259200; //3 days time
$wgBounceRecordPeriod = 604800; // 60 * 60 * 24 * 7 - 7 days bounce activity are considered before un-subscribing
$wgBounceRecordLimit = 3; // If there are more than 3 bounces in the $wgBounceRecordPeriod, the user is un-subscribed

/* IMAP configs */
$wgIMAPuser = 'user';
$wgIMAPpass = 'pass';
$wgIMAPserver = '{localhost:143/imap/novalidate-cert}INBOX';

/*Allow only internal IP range to do the POST request */
$wgBounceHandlerInternalIPs = array( '127.0.0.1', '::1' );
