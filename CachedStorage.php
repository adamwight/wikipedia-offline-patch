<?php
/**
 * Do the dirty work.
 */

class CachedStorage
{
    function fetchIdByTitle($title) {
	global $wgRevisionCacheExpiry, $wgMemc;
	if ($wgRevisionCacheExpiry) { // TODO a better test for caching
	    $titlekey = wfMemcKey( 'textid', 'titlehash', md5($title) );
	    $textid = $wgMemc->get($titlekey);
	    if (is_int($textid) && $textid > 0)
		return $textid;
	}
	return false;
    }

    function set($title, $content) {
	static $textid_seq = 1;
	$textid = $textid_seq++;

	global $wgRevisionCacheExpiry, $wgMemc;
	if (!$wgRevisionCacheExpiry) // caching is not possible
	    return false;

	// cache a lookup from title to fake textid
	$titlekey = wfMemcKey( 'textid', 'titlehash', md5($title) );
	$wgMemc->set( $titlekey, $textid, $wgRevisionCacheExpiry );

	// TODO interfering with the cache is necessary to avoid a
	// second query on Revision::newFromId.  It would be much
	// smarter to directly retrieve article markup, and optionally
	// cache in the usual way.
	$textkey = wfMemcKey( 'revisiontext', 'textid', $textid );
	$wgMemc->delete( $textkey );
	$wgMemc->set( $textkey, $content, $wgRevisionCacheExpiry );
	//wfDebug('Stuffing the cache with '.strlen($content).' bytes, at id='.$textid."\n");

	return $textid;
    }
}
