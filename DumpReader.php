<?php
class DumpReader
{
  # 
  # open the index files and lookup the chunk.  Load article source.
  #
  static function load_article($title)
  {
      $article_wml = null;
      $results = self::index_lookup($title);
      if ($results) {
	  $archive_file = $results[0];
	  $title = $results[1];

	  $article_wml = self::load_all_data($title, $archive_file);
      } else {
	  $article_wml = ""; //TODO or null?
      }
      $article_wml = htmlspecialchars_decode($article_wml);
      return $article_wml;
  }

  static function load_all_data($title, $file_name)
  {
  #error_log("loading chunk [$file_name] to find article [$title]");
      $article_wml = "";
      $matches = array();
      $all_chunk_data = self::load_bz($file_name);
      if (preg_match("/<title>".preg_quote($title, '/')."<\/title>.*?<text[^>]*>(.*)/s",
	      $all_chunk_data, $matches))
      {
	  $all_chunk_data = $matches[1];
	  for (;;) {
	      $end_pos = strpos($all_chunk_data, '</text>');
	      if ($end_pos !== FALSE) {
		  $article_wml .= substr($all_chunk_data, 0, $end_pos);
		  break;
	      }
	      $article_wml .= $all_chunk_data;

  error_log('continuing into next bz2 chunk');
	      $file_name = self::increment_file($file_name);
	      $all_chunk_data = self::load_bz($file_name);
	  }
      }
      return $article_wml;
  }

  #
  # open chosen bz2 split, decompress and return
  # TODO begin bzcat at chunk, let pipe load in the bg process.
  #
  static function load_bz($file_name)
  {
      global $wgOfflineWikiPath;
      $path = "$wgOfflineWikiPath/$file_name";
      if (strlen($file_name) < 1) return null; #strange that bzopen doesn't choke on dir.
      $bz = bzopen($path, "r");
      if (!$bz)  return null;

      $out = "";
      while ($bz && !feof($bz)) {
	  $out .= bzread($bz, 8192);
      }
      bzclose($bz);
      return $out;
  }

  #
  # use the index files 
  #
  static function index_lookup($title)
  {
    $title = strtr($title, '_', ' ');
    $title = strtolower(trim($title));
  #error_log("looking up word [$title]");

    try {
      require_once("xapian.php");
      global $wgOfflineWikiPath;
      $db = new XapianDatabase("$wgOfflineWikiPath/db");
      #$qp = new XapianQueryParser();
      #$qp->set_database($db);
      #$stemmer = new XapianStem("english");
      #$qp->set_stemmer($stemmer);
      #$query = $qp->parse_query($title);
      $query = new XapianQuery($title);
      $enquire = new XapianEnquire($db);
      $enquire->set_query($query);
      $matches = $enquire->get_mset(0, 25);

      if (0 /*SCORING*/) {
	  $scores = array();
	  for ($i = $matches->begin(); !$i->equals($matches->end()); $i->next())
	  {
	      $row = $i->get_document();
	      $str = $i->get_percent()."% [".$row->get_data()."]";
	      $scores[] = $str;
	      if (1/*DEBUG*/) error_log("$str\n");
	  }
      }

      # TODO...
      $result = null;
      if ($matches->size() > 0) {
	  $entry = $matches->begin()->get_document()->get_data();
	  $fsep = strpos($entry, ':');
	  $result = array(substr($entry, 0, $fsep), substr($entry, $fsep + 1));
      }
      # not in Xapian 1.0.X
      #$db->close();

      return $result;

    } catch (Exception $e) {
	error_log($e->getMessage());
	return null;
    }
  }

  static function increment_file($fname)
  {
      // XXX assuming .bz2
      $matches = array();
      if (preg_match('/(.*?)(\d+)(.*?)$/', $fname, $matches)) {
	  $i = $matches[2];
	  preg_replace("/$i/", $i + 1, $fname);
      }
      return $fname;
  }
}
