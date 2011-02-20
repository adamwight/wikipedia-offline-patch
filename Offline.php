<?php
$wgExtensionCredits['other'][] = array(
       'path' => __FILE__,
       'name' => 'Wikipedia Offline Patch',
       'author' => 'Adam Wight', 
       'status' => 'beta',
       'url' => 'http://code.google.com/p/wikipedia-offline-patch', 
       'version' => '0.4',
       'descriptionmsg' => 'offline_desc'
       );

$dir = dirname(__FILE__);
$wgExtensionMessagesFiles['Offline'] = $dir.'/Offline.i18n.php';
$wgExtensionAliasesFiles['Offline'] = $dir.'/Offline.aliases.php';

$wgExtensionFunctions[] = 'wfOfflineInit';

$wgSpecialPages['Offline'] = 'SpecialOffline';
$wgSpecialPageGroups['Offline'] = 'wiki'; // XXX something broke

/**
 * It's sad but there are no hooks which allow us to set the
 * db to null and provide our own article storage.  So we hijack the
 * whole thing with a faux sql layer which intercepts page and
 * revision fetching.  We depend on the php accel cache to store wml.
 */
$wgLBFactoryConf = array( 'class' => 'LBFactory_No' );

$wgAutoloadClasses['LBFactory_No'] = $dir.'/nulldb/LBFactory_No.php';
$wgAutoloadClasses['SpecialOffline'] = $dir.'/SpecialOffline.php';

function wfOfflineInit() {
    // if (textid_seq == 0) clearOfflineCache();
}
