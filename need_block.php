<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

$auto_ban_spur_nic = "Xvpn";


date_default_timezone_set('UTC');


function get_ip($s) {
    $x = explode(":", $s);
    return $x[0];
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad=on_load() bgcolor=#ffffff>
<style type="text/css">
body
{
	border: 0px solid #777777;
        margin: 3;
	font-size: 12px;
	font-family : "DejaVu Sans";
}

input 
{
	background-color: #ffffff;
	font-size: 12px;
	font-family : "DejaVu Sans";
	border: 1px solid #7777ff;
}

input.check_box
{
  width: 15px;
  height: 15px;
  display: block;
  clear: left;
  float: left;
  font-size: 12px;
  font-family : "DejaVu Sans";
}

:root {
  --main-a-color: #086FA1;
}

a:visited {font-family : "DejaVu Sans"; font-size: 12px; font-weight: none; color: var(--main-a-color); text-decoration: none}
a:active  {font-family : "DejaVu Sans"; font-size: 12px; font-weight: none; color: var(--main-a-color); text-decoration: none}
a:link    {font-family : "DejaVu Sans"; font-size: 12px; font-weight: none; color: var(--main-a-color); text-decoration: none}
a:hover   {font-family : "DejaVu Sans"; font-size: 12px; font-weight: none; color: #ffaaaa; text-decoration: none}

td
{
	font-size: 12px;
	font-family : "DejaVu Sans";
}

td.ipFirewall
{
	font-size: 12px;
	font-family : "DejaVu Sans";
        background-color: red;
        color: #ffffff;
}


.prokrutka {
height: 78%; /* высота нашего блока */
width: 100%; /* ширина нашего блока */
background: #fff; /* цвет фона, белый */
border: 1px solid #C1C1C1; /* размер и цвет границы блока */

overflow-y: scroll; /* прокрутка по вертикали */
}

</style> 

<script language="JavaScript" type="text/javascript">
<!--

let need_update_arr = 1;
let arr = new Array();

var frame_use_ = 0;
function set_frame_use(v) {
    if(v != 'update1') console.log('SET '+v);
    frame_use_ = 1;
};
function free_frame_use(v) {
    if(v != 'update_result1') console.log('FREE '+v);
    frame_use_ = 0;
};

let auto_ban_spur_nic = "<? echo $auto_ban_spur_nic; ?>";

function on_load()
{
  setInterval( function() { ttimer(); } , 225);
};

var need_block_ip = '';

function need_block_result(r) {
    //console.log(r);
    need_block_ip = r;
    free_frame_use("1");
};

function ttimer_autoblock() {
    
    if(frame_use_ == 0 && need_block_ip == '') {
        set_frame_use("1");
        console.log("ttimer_autoblock   need_block_frm.php");
        document.getElementById('frm_tmp').src = "need_block_frm.php";
    };
};

function find_from_arr(v) {

    i = 0;
    while(i < arr.length) {
        if(arr[i] == v) return 1;
      i++;  
    };

    return 0;
};

function add_to_arr(v) {
    console.log("add_to_arr " + v);
    i = 0;
    while(i < arr.length) {
        if(arr[i] == v) return;
        i++; 
    };
    arr[i] = v;
};

function mikrotik_result(result, mode, idx) {

    if(mode == "firewall_print") {
        console.log("mikrotik_result |" + "result" + "|"+ mode+"|"+ idx+"|");
        
        let rr = result.split("\t");     
        //console.log(rr.length);
        i = 0;
        while( i < rr.length) {
            let vv = rr[i].split("|");
            add_to_arr(vv[0]);
            
          i++;
        };
        document.getElementById('arr_cnt').innerHTML = arr.length;
    
   
        free_frame_use('add_mikrotik');
        return;
    };
    
    if( result == "add" || result == "add " ||
        result == "already exists" || result == "already exists "     
            )
    {
        add_to_arr(idx);
    };
    
    console.log("mikrotik_result |" + result + "|"+ mode+"|"+ idx+"|");

    free_frame_use('add_mikrotik');
};

function delfile_result(v) {
    console.log('delfile_result = ' + v);
    free_frame_use('delfile_result()');
};

function del_file(ip) {
    set_frame_use('del_file');
    console.log('frame START   delfile.php?ip='+ ip);
    document.getElementById('frm_tmp').src = "delfile.php?file=need_block/" + ip+"&new=need_block/already/"+ip;
};


function add_to_mikrotik(ip) {
    console.log("add_to_mikrotik " + ip);
    
    if(find_from_arr(ip) == 1) {
        
      console.log("already find " + ip);
      del_file(ip);
      return;
    } else {
      console.log("not find " + ip);  
    };
   
    
        if(frame_use_ == 0)
        {
            set_frame_use('add_mikrotik');
            console.log('frame START   mikrotik.php?ip='+ ip);
            document.getElementById('frm_tmp').src = "mikrotik.php?ip=" + ip + '&mode=firewall_add&smode=delneedblock';
        }
        else
        {
          console.log('Busy m');
        };
        
};

function update_arr() {
    console.log("update_arr()...");
    need_update_arr = 2;
    document.getElementById('frm_tmp').src = "mikrotik.php?ip=1.1.1.1&mode=firewall_print&smode=delneedblock";
       
};

function ttimer() {

    if(need_update_arr == 1) {
        update_arr();
        return;
    };
    if(need_update_arr == 22) {
        
        return;
    };
    if(need_block_ip != '') {
        add_to_mikrotik(need_block_ip);
        need_block_ip = '';
        return;
    };

    c = document.getElementById('need_block');
    if(c != null) {
        if(c.checked) {
            
                ttimer_autoblock();
                return;
            
        };
    };

};

//-->
</script>
<?php


?>
<table border=1 cellpadding=5 cellspacing=0>
<tr>
    <td><input type="checkbox" id=need_block value="1">auto need block</p></td><td id="arr_cnt">0</td>    
</tr>
</table>

<iframe id=frm_tmp width=300 height=200 style="display:none1;"></iframe>


</body>

