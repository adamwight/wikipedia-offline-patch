<?php
$messages = array();
$messages['en'] = array(
    'special_offline_desc' => 'Status and diagnostics for the Offline extension.',
    'special_page_title' => 'Offline configuration',

    'heading_status' => 'Offline Configuration Helper',

    'test_article' => 'Andalusia', // a word likely to be found
    'bad_test_article' => 'Internal error: the test_article "$1" was not found, but the index database seems to be good.',

    'index_test_pass' => 'Dump index was read successfully.',
    'index_test_fail' => 'Dump index cannot be read!',
    'offlinewikipath_not_configured' => 'You have not set the $wgOfflineWikiPath variable in LocalSettings.php',
    'offlinewikipath_not_found' => 'The directory specified by $wgOfflineWikiPath, <em>$1</em>, does not exist',
    'dbdir_not_found' => 'The database directory, <em>$1/db</em>, is missing index data files. These must be downloaded or built.',
    'unknown_index_error' => 'The index to your dump could not be read for an unknown reason. Perhaps the database files are damaged.',

    'bzload_test_pass' => 'Compressed dump files can be opened.',
    'bzload_test_fail' => 'Compressed dump files were not loaded!',
    'bz2_ext_needed' => 'Your PHP installation is missing the Bzip2 library.',
    'bz2_file_gone' => 'The index pointed to a missing dump file: <em>$1</em>',

    'article_test_pass' => 'Article data was found where expected.',
    'article_test_fail' => 'Indexed page has changed. Perhaps your index was made for another dump?',

    'hooks_test_pass' => 'Mediawiki article loader will fetch from dump data.', // from GRAMMAR(a) %1 encyclopedia called %2.
    'hooks_test_fail' => 'Offline hooks are not properly attaching. Maybe this is not MediaWiki 1.16?',
    'live_data_preferred' => 'Matches from the so-called live database will be preferred over dump text.',

    'all_tests_pass' => 'You are good to go.',

    'subdir_status' => 'Index files were found in subdirectory named $1',
    'change_subdir' => 'Use the following directory prefix instead:',

    'change_language' => 'Dumps of the following languages have been detected. Check all dumps you want to make available.',
);
