<?php
$wgExtensionCredits['other'][] = array(
       'path' => __FILE__,
       'name' => 'Wikipedia Offline Patch',
       'author' => 'Adam Wight', 
       'status' => 'beta',
       'url' => 'http://code.google.com/p/wikipedia-offline-patch', 
       'version' => '0.6',
       'descriptionmsg' => 'offline_desc'
       );

$dir = dirname(__FILE__);
$wgExtensionMessagesFiles['Offline'] = $dir.'/Offline.i18n.php';
$wgExtensionAliasesFiles['Offline'] = $dir.'/Offline.aliases.php';

$wgExtensionFunctions[] = 'wfOfflineInit';

$wgSpecialPages['Offline'] = 'SpecialOffline';
$wgSpecialPageGroups['Offline'] = 'wiki'; // XXX is not the key?


$wgAutoloadClasses['DatabaseBz2'] = $dir.'/DatabaseBz2.php';
$wgAutoloadClasses['SpecialOffline'] = $dir.'/SpecialOffline.php';


function wfOfflineInit() {
    // Our dump fetch is installed as the fallback to existing dbs.
    // Dump reader will be called through a very single-minded sql api.
    $wgDBservers[] = array(
	'dbname' => $wgOfflineWikiPath,
	'type' => 'bz2',
	'load' => 1,
    );
wfDebug('XXX '.count($wgDBservers));
}
