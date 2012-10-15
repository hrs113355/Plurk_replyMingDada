#!/usr/bin/php5
<?php
	date_default_timezone_set("Asia/Taipei");
	require('config.php');
	require('php-plurk-api/plurk_api.php');

	$plurk = new plurk_api();
	$plurk->login($plurk_api_key, $plurk_username, $plurk_password);

	$all_origins = array();
	$default_origins = array();

	while(1)
	{
	    echo "\n\n - getting Dada's plurks - \n";
	   $ret =  $plurk->get_plurks(NULL, 30, NULL, NULL, NULL);

	    foreach ($ret->plurks as $p)
	    {
		if ($p->owner_id != DADAID)
		    continue;

		    print "id      = " . $p->plurk_id . "\n";
		    print "content = " . $p->content_raw . "\n";
		    print "data    = " . ($posted = date('Y-m-d\TH:i:s', strtotime($p->posted)))."\n";
		    print "owner   = " . $p->owner_id . "\n";

		    // you can change replied comment here
		    $plurk->add_response($p->plurk_id, 'http://emos.plurk.com/4c9a44c77333900b00c75d05252dc52c_w30_h17.png 大大', 'says');

		    if (!(isRepeat($p->plurk_id)))
		    {
			logThis($p->plurk_id);
			print " AUTO REPLIED!!\n\n";
		    }
		    else
			print " repeated!!\n\n";
	    }
	    sleep(600); // 10 minutes should be enough
	}

	function isRepeat($plurk_id)
	{
	    $fp = fopen(LOGFILE, "r");
	    while ($data = fgets($fp, 1024))
		if ($plurk_id == $data)
		    return true;
	    return false;
	}

	function logThis($plurk_id)
	{
	    $fp = fopen(LOGFILE, "a");
	    fputs($fp, $plurk_id . "\n", 1024);
	    fclose($fp);
	}
?>
