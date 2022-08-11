<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

ini_set('display_errors', '1');

date_default_timezone_set('UTC');



function get_dt()
{
  $ss = date("Y-m-d H:i"); 
  return $ss;
};



function my_scan_dir($dir)
{
    $rr = '';
    
    $f = scandir($dir);
    foreach ($f as $file)
    {
      if($file != '.' && $file != '..')
      {
          if(is_dir($dir.'/'.$file)) {
            //$rr .= 'D='.$file.'|';  
              my_scan_dir($dir.'/'.$file);
              rmdir($dir.'/'.$file);
          } else {
            unlink($dir.'/'.$file);
          };
      };
    }    
    
    return $rr;
}



$r = my_scan_dir("/var/www/html/sniffer_web/ip3");
$r = my_scan_dir("/var/www/html/sniffer_web/sess");
unlink('dns_list.txt');

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad=on_load() bgcolor=#ffffff style="margin:0;">

<script language="JavaScript" type="text/javascript">
<!--


function on_load()
{
  parent.clear_all_result();
};

//-->
</script>
clear all
</body>

