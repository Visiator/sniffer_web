<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

$auto_ban_spur_nic = "Xvpn";


date_default_timezone_set('UTC');

function a($v)
{
  return str_replace('.','_',$v);
};

$dns_list = array();

function load_dns() 
{
    global $dns_list;
    $fff = file_get_contents('dns_list.txt');
        
    $ff = explode("\n", $fff);
    $i = 0;
    foreach($ff as $f) 
    {
        
        $v = explode("\t", $f);
        
        $dns_list[$i] = array();
        $dns_list[$i][0] = $v[0];
        $dns_list[$i][1] = $v[1];
        
        
        $i++;
    }
    
}

function get_dns($dir, $filename)
{
    $fff = file_get_contents($dir .'/'. $filename);

    $ff = explode("\n", $fff);
    $s = '';
    $c = '';
    foreach($ff as $f)
    {

        $v = explode(":", $f, 2);
        if($v[0] == 'dns_name') {
            $s = $v[1];
        } else {
	    
	}
    };
    //if($c == '') return $s;
    //if($s == '') return $c;
    return $s;

/*
    global $dns_list;
    
    $j = count($dns_list);
    $i = 0;
    while($i < $j) 
    {
        if($dns_list[$i][0] == $iip) {
            return $dns_list[$i][1];
        }
        $i++;
    }
    return '';
*/
}

function get_sni($dir, $filename)
{
    $fff = file_get_contents($dir .'/'. $filename);
    
    $ff = explode("\n", $fff);
    $s = '';
    $c = '';
    foreach($ff as $f) 
    {
        
        $v = explode(":", $f, 2);
        if($v[0] == 'sni') {
            $s = $v[1];
        }
        if($v[0] == 'cert') {
            $c = 'cert='.$v[1];
        }
    };
    //if($c == '') return $s;
    //if($s == '') return $c;
    return $c.' | '.$s;
};

function get_ip($s) {
    $x = explode(":", $s);
    return $x[0];
}

function get_traffic($dir, $filename)
{
    $fff = file_get_contents($dir .'/'. $filename);
    
    $ff = explode("\n", $fff);
    
    foreach($ff as $f) 
    {
        
        $v = explode(":", $f, 2);
        
        if($v[0] == 'tcp' && $v[1] != '00000') return (int)$v[1];
        if($v[0] == 'udp' && $v[1] != '00000') return (int)$v[1];
           
        
    };
    return '';
};

echo(1111);

mkdir("ip3");
chmod("ip3", 0777);
mkdir("sess");
chmod("sess", 0777);
mkdir("spur");
chmod("spur", 0777);

load_dns();

$tt = '<table id=MainTable border=1 cellpadding=5 cellspacing=0>'."\r\n"."<tbody>";
$tt .= '<tr>'.
                '<td>ip</td>'.
                '<td>dns</td>'.
                '<td>sni</td>'.
                '<td>traffic</td>'.
                '<td>geo</td>'.
                '<td>spur</td>'.
                '<td>cpp</td>'.
                '<td>add_mikrotik</td>'.
                '<td>stat</td>'.
                '<td>del_mikrotik</td>'.
                '<td>copy pcap</td>'.
        
                '</tr>'."\r\n";

$i = 0;

$f = scandir('ip3');
foreach ($f as $file)
{
    if($file != '.' && $file != '..')
    {
        $dns = get_dns('ip3', $file);
        
        $sni = get_sni('ip3', $file);
        
        $traffic = get_traffic('ip3', $file);

        $tt .= '<tr>'.
                '<td id=ip_'.$i.'>'.$file.'</td>'.
                '<td id=dns_'.$i.'>'.$dns.'</td>'.
                '<td id=sni_'.$i.'>'.$sni.'</td>'.
                '<td id=traffic_'.$i.'>'.$traffic.'</td>'.
                '<td id=geo_'.$i.'>?</td>'.
                '<td id=spur_'.$i.'>?</td>'.
                '<td id=cpp_'.$i.'>?</td>'.
                '<td><a href=javascript:add_mikrotik('.$i.')>add_mikrotik</a></td>'.
                '<td id=firewall_'.$i.'>?</td>'.
                '<td><a href=javascript:del_mikrotik('.$i.')>del_mikrotik</a></td>'.
                '<td><a href=javascript:copy_pcap('.$i.')>copy pcap</a></td>'.
                '<td><a href=javascript:show_sess_list('.$i.')>W</a></td>'.
                
                '</tr>'."\r\n";
        $i++;
    };

  
}

$tt .= "</tbody>".'</table>'."\r\n";

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

let auto_ban_spur_nic = "<? echo $auto_ban_spur_nic; ?>";

function on_load()
{
  setInterval( function() { ttimer(); } , 55);
};

function show_sess_list(v) {
    o = document.getElementById('ip_'+v).innerHTML;
    window.open("show_sess_list.php?id="+o, '_blank');
};

function copy_pcap_result(v) {
    console.log('copy_pcap_result='+v);
};

function copy_pcap(idx) {
    o = document.getElementById('ip_'+idx);
    if(o == null) return;
    //alert(o.innerHTML);
    document.getElementById('frm_tmp2').src = "copy_pcap.php?id=" + o.innerHTML;
};

var mdiv_innerHTML = "";

function clear_all_firewall_color() {
    ii = 0;
    while(1==1) {
        o = document.getElementById('ip_'+ii);
      
        if(o == null) { return; }
        else {
            o.className = "";
        };
        document.getElementById('dns_'+ii).className = "";
        document.getElementById('sni_'+ii).className = "";
        document.getElementById('traffic_'+ii).className = "";
        document.getElementById('geo_'+ii).className = "";
        ii++;
    };    
};

function set_firewall_color_by_ip(mm) {

    console.log('set_firewall_color_by_ip');

    ii = 0;
    while(1==1) {
        o = document.getElementById('ip_'+ii);
        
        if(o == null) { return; }
        else {
            let xx = o.innerHTML.split(":");
            
            if(xx[0] == mm) {
                o.className = "ipFirewall";
                //document.getElementById('firewall_'+ii).innerHTML = rrr[1] + ' / ' + rrr[2];
                document.getElementById('dns_'+ii).className = "ipFirewall";
                document.getElementById('sni_'+ii).className = "ipFirewall";
                document.getElementById('traffic_'+ii).className = "ipFirewall";
                document.getElementById('geo_'+ii).className = "ipFirewall";
            };
        };
        
        
        
        ii++;
    };


}

function set_firewall_color(mm) {
    
    let rrr = mm.split("|");
    //console.log(r0[0] + '+++' + r0[1] + '+++' + r0[2]);
    
    ii = 0;
    while(1==1) {
        o = document.getElementById('ip_'+ii);
        
        if(o == null) { return; }
        else {
            let xx = o.innerHTML.split(":");
            
            if(xx[0] == rrr[0]) {
                o.className = "ipFirewall";
                document.getElementById('firewall_'+ii).innerHTML = rrr[1] + ' / ' + rrr[2];
                document.getElementById('dns_'+ii).className = "ipFirewall";
                document.getElementById('sni_'+ii).className = "ipFirewall";
                document.getElementById('traffic_'+ii).className = "ipFirewall";
                document.getElementById('geo_'+ii).className = "ipFirewall";
            };
        };
        
        
        
        ii++;
    };
    
    
};

function need_refresh_firewall() {
    //document.getElementById('check_firewall').checked = true;
};

function mikrotik_result(r, mode, ip) {
    console.log('r=|'+r+'|');
    console.log(mode);
    console.log(ip);
    
    if(r == "add " || r == "already exists ") {
        console.log('555555555');
        set_firewall_color_by_ip(ip);
    };
    
    if(mode == 'firewall_print') {
        clear_all_firewall_color();
        let rr = r.split("\t");     
        //console.log(rr.length);
        i = 0;
        while( i < rr.length) {
          set_firewall_color(rr[i]);
          i++;
        };
        wait_refresh_firewall = 0;
    } else {
        need_refresh_firewall();  
    };
    
    free_frame_use('mikrotik_result');
}

function allow_mikrotik() {
    o = document.getElementById('allow_mikrotik');
    if(o != null) {
        if(o.checked == true) {
            console.log('+++');
            return true;
        };
    };
    console.log('---');
    return false;
};

function add_mikrotik(idx) {
    if(allow_mikrotik()==false) return;
    
    o = document.getElementById('ip_'+idx);
    if(o != null) {
        ip = o.innerHTML;
        if(frame_use_ == 0)
        {
            set_frame_use('add_mikrotik');
            //console.log('frame START '+ ip);
            document.getElementById('frm_tmp2').src = "mikrotik.php?ip=" + ip + '&mode=firewall_add';
        }
        else
        {
          console.log('Busy');
        };
    };
};

function del_mikrotik(idx) {
    if(allow_mikrotik()==false) return;
    
    o = document.getElementById('ip_'+idx);
    if(o != null) {
        ip = o.innerHTML;
        if(frame_use_ == 0)
        {
            set_frame_use('del_mikrotik');
            //console.log('frame START '+ ip);
            document.getElementById('frm_tmp').src = "mikrotik.php?ip=" + ip + '&mode=firewall_del';
        }
        else
        {
          console.log('Busy');
        };
    };
};


function s10_s16(v)
{
  x = parseInt(v, 10).toString(16);
  if(x.length == 1) return "0" + x;
  return x;
};

function hh(v)
{
  if(v == '0') return '0';
  if(v == '1') return '1';
  if(v == '2') return '2';
  if(v == '3') return '3';
  if(v == '4') return '4';
  if(v == '5') return '5';
  if(v == '6') return '6';
  if(v == '7') return '7';
  if(v == '8') return '8';
  if(v == '9') return '9';
  if(v == 'a') return 'a';
  if(v == 'b') return 'b';
  if(v == 'c') return 'c';
  if(v == 'd') return 'd';
  if(v == 'e') return 'e';
  if(v == 'f') return 'f';
  if(v == 'A') return 'a';
  if(v == 'B') return 'b';
  if(v == 'C') return 'c';
  if(v == 'D') return 'd';
  if(v == 'E') return 'e';
  if(v == 'F') return 'f';

  return '?';
};

function to_h(v1, v2)
{
  a1 = hh(v1);
  if(a1 == '?') return '?';
  a2 = hh(v2);
  if(a2 == '?') return '?';

  return a1+a2;
};

var frame_use_ = 0;
function set_frame_use(v) {
    if(v != 'update') console.log('SET '+v);
    frame_use_ = 1;
};
function free_frame_use(v) {
    if(v != 'update_result') console.log('FREE '+v);
    frame_use_ = 0;
};


function spur_curl_result(r, iidx)
{
    let rr = r.split("\t");
    

    s = "<font color=silver>[" + rr[0]+"]</font> ";
  
    if(rr[1] == 'Not Anonymous') {
        s += '<font color=silver>' + rr[1] + '</font>';
    } else {
        s += rr[1];
    };
    
    o = document.getElementById('spur_'+iidx).innerHTML = s;


    free_frame_use('spur_curl_result');
};

var ggg = 0;

function allow_spur() {

    return false;
};

function allow_spur() {
    o = document.getElementById('allow_spur');
    if(o != null) {
        if(o.checked == true) {
            return true;
        };
    };
    return false;
};

function spur(idx, ip)
{
  if(frame_use_ == 0)
  {
      if(allow_spur() == false) return;
    set_frame_use('spur');
    //console.log('frame START '+ ip);

    document.getElementById('frm_tmp').src = "spur_curl.php?ip=" + ip + '&idx='+idx;

  }
  else
  {
    //console.log('frame is use');
  };
};

var use_autobanspur = 0;
var wait_refresh_firewall = 0;

function auto_ban_spur(idx, val) {
    //console.log(val);
    if(val.indexOf(auto_ban_spur_nic) >= 0) {
        n = document.getElementById('ip_'+i);
        if(n.className != "ipFirewall") {
            console.log("add mikrotik " + idx);
            add_mikrotik(idx);
        };
    };
};

function scan_spur_cell() {
    console.log('scan_spur_cell');
    
    i = 0;
    while(1==1)
    {
        
        if(allow_mikrotik()==false) return;
        
        n = document.getElementById('spur_'+i);
        if(n == null) {
            return;
        };
        
        //o = document.getElementById('ip_'+i);

        vv = n.innerHTML;

        if(vv.indexOf(auto_ban_spur_nic) >= 0) {
            n = document.getElementById('ip_'+i);
            if(n.className != "ipFirewall") {
                console.log("add mikrotik " + i);
                wait_refresh_firewall = 1;
                add_mikrotik(i);
                return;
            };
        };

        // auto_ban_spur(i, n.innerHTML);

        i++;
    };
 
    
}

function ttimer_autobanspur() {
    if(allow_spur()==false) return;
    use_autobanspur = 1;
    
    scan_spur_cell();
    
    c = document.getElementById('check_spur');
    if(c != null) {
        if(!c.checked) {
            c.checked = true;
        };
    };
    
}

function ttimer()
{
    //console.log('ttimer()');
    
    
    c = document.getElementById('check_auto_ban_spur');
    if(c != null) {
        if(c.checked) {
            if(use_autobanspur == 0) {
                ttimer_autobanspur();
                return;
            }
        };
    };
    
    c = document.getElementById('check_autoadd');
    if(c != null) {
        if(c.checked) {
            
            ttimer_autoadd();
            return;
        };
    };
    
    c = document.getElementById('check_geo');
    if(c != null) {
        if(c.checked) {
            
            ttimer_geo();  
            return;
        };
    };
    c = document.getElementById('check_spur');
    if(c != null) {
        if(c.checked) {
            ttimer_spur();  
            return;
        };
    };
    c = document.getElementById('check_cpp');
    if(c != null) {
        if(c.checked) {
            ttimer_cpp();  
            return;
        };
    };
    c = document.getElementById('check_firewall');
    if(c != null) {
        if(c.checked) {
            c.checked = false;
            ttimer_firewall();  
            return;
        };
    };
    
    if( ggg + 500 < Date.now() ) {
        ggg = Date.now();
        if(wait_refresh_firewall == 0) {
           update();
        }
    };
    
    if( mdiv_innerHTML != "" ) {
        ggg = 991657642985625;
        document.getElementById("mdiv").innerHTML = mdiv_innerHTML;
        mdiv_innerHTML = "";  
    };
    
    use_autobanspur = 0;
}

var aag = -1;

function ttimer_autoadd() {

    console.log("-----------"+aag);

    if(frame_use_ != 0) return;

    if(aag == -1) aag = 0;

    n = document.getElementById('ip_'+aag);
    
    if(n == null) {
        c = document.getElementById('check_autoadd');
           if(c != null) { 
               c.checked = false; 
               console.log('stop');
               aag = -1;
           };

        return;
    };
    add_mikrotik(aag);
    aag++;
    

};

function geoip_result(r, iidx)
{
    o = document.getElementById('geo_'+iidx).innerHTML = r;
   free_frame_use('geoip_result');
};

function ggeo(idx, ip)
{
  if(frame_use_ == 0)
  {
    set_frame_use('ggeo');
    //console.log('frame START '+ ip);

    document.getElementById('frm_tmp').src = "geoip.php?ip=" + ip + '&idx='+idx;

  }
  else
  {
    console.log('frame is use');
  };
};

function ttimer_geo() {
    
    i = 0;
    while(1==1)
    {
       n = document.getElementById('geo_'+i);
       if(n == null) {
           c = document.getElementById('check_geo');
              if(c != null) { c.checked = false; };
         
           return;
       };
       if(n.innerHTML == '?') 
       {
         o = document.getElementById('ip_'+i);

         ggeo(i, o.innerHTML);
         
         return;
       };
       i++;
    };
    
}


function ttimer_firewall() {

    if(allow_mikrotik()==false) return;

    if(frame_use_ == 0) {
        set_frame_use('ttimer_firewall');
        
        c = document.getElementById('check_firewall');
        c.checked = false;
        
        console.log('frame START ');
        document.getElementById('frm_tmp').src = "mikrotik.php?ip=" +  '&mode=firewall_print';
    }
}


function cpp_result(r, iidx)
{
    if(r == '') {
        s = '';
    } else {
        let rr = r.split("\t");
        s = rr[0] + ' <font color=silver>' + rr[1] + '</font>';
    };
    o = document.getElementById('cpp_'+iidx).innerHTML = s;


    free_frame_use('cpp_result');
};

function cppp(idx, ip, sni) {
  if(frame_use_ == 0)
  {
    set_frame_use('cppp');
    console.log('cpp START '+ ip);

    document.getElementById('frm_tmp2').src = "cpp.php?ip=" + ip + '&idx='+idx+'&sni='+sni;

  }
  else
  {
    //console.log('frame is use');
  };

};

function ttimer_cpp() {

    
    i = 0;
    while(1==1)
    {
        n = document.getElementById('cpp_'+i);
        if(n == null) {
            c = document.getElementById('check_cpp');
            if(c != null) { c.checked = false; };
            return;
        };
        if(n.innerHTML == '?') 
        {
            o = document.getElementById('ip_'+i);
            s = document.getElementById('sni_'+i);
            cppp(i, o.innerHTML, s.innerHTML);
            return;
        };
        i++;
    };
    
};

function ttimer_spur()
{
  i = 0;
  while(1==1)
  {
     n = document.getElementById('spur_'+i);
     if(n == null) {
         c = document.getElementById('check_spur');
            if(c != null) { c.checked = false; };
         return;
     };
     if(n.innerHTML == '?') 
     {
       o = document.getElementById('ip_'+i);

       spur(i, o.innerHTML);
       return;
     };
     i++;
  };
 
}

function transform24()
{
    v = document.getElementById('memo1').value;
tt = "<table border=1 cellpadding=5 cellspacing=0>";

  i = 0;
  j = 0;
  k = 0;
  while( i < 256 )
  {
    
                tt += '<tr>'+
                    '<td id=ip_'+k+'>'+v +'.'+i+'</td>'+
                    '<td id=dns_'+k+'></td>'+
                    '<td id=sni_'+k+'></td>'+
                    '<td id=traffic_'+k+'></td>'+
                    '<td id=geo_'+k+'>?</td>'+
                    '<td id=spur_'+k+'>?</td>'+
                    '<td id=cpp_'+k+'>?</td>'+
                    '<td><a href=javascript:add_mikrotik('+k+')>add_mikrotik</a></td>'+
                    '<td id=firewall_'+k+'>?</td>'+
                    '<td><a href=javascript:del_mikrotik('+k+')>del_mikrotik</a></td>'+
                    '</tr>'+"\r\n";
                k++;
    
    i++;
  };
  
  tt += '</table>';
  
  //document.getElementById('memo1').value = ee;
  mdiv_innerHTML = tt;
    
}

function transform()
{
    v = document.getElementById('memo1').value;
  let arr = v.split("\n");
  let ee = "";

tt = "<table border=1 cellpadding=5 cellspacing=0>";

  i = 0;
  j = 0;
  k = 0;
  while( i < arr.length )
  {
    a = arr[i].trim();
    if(a != "") {
        aa = a.split(".");
        if(aa.length >= 4) 
        {
            var s1, s2, s3, s4;
            s1 = '';
            j = aa[0].length;
            while(j > 0) {
                if(aa[0][j-1] < '0' || aa[0][j-1] > '9') break;
                s1 = aa[0][j-1] + s1;  
                j--;
            };
            s2 = aa[1];
            s3 = aa[2];
            s4 = '';
            j = 0;
            while( j < aa[3].length) {
                if(aa[3][j] < '0' || aa[3][j] > '9') break;
                s4 += aa[3][j];
                j++;  
            };
            iipp = s1+"."+s2+"."+s3+"."+s4;
            
            if( tt.indexOf('>'+iipp+'<') >= 0 ) {
                console.log('уже есть ' + iipp);
            } else {

                tt += '<tr>'+
                    '<td id=ip_'+k+'>'+iipp+'</td>'+
                    '<td id=dns_'+k+'></td>'+
                    '<td id=sni_'+k+'></td>'+
                    '<td id=traffic_'+k+'></td>'+
                    '<td id=geo_'+k+'>?</td>'+
                    '<td id=spur_'+k+'>?</td>'+
                    '<td id=cpp_'+k+'>?</td>'+
                    '<td><a href=javascript:add_mikrotik('+k+')>add_mikrotik</a></td>'+
                    '<td id=firewall_'+k+'>?</td>'+
                    '<td><a href=javascript:del_mikrotik('+k+')>del_mikrotik</a></td>'+
                    '</tr>'+"\r\n";
                k++;
            };
        };
    };
    i++;
  };
  
  tt += '</table>';
  
  //document.getElementById('memo1').value = ee;
  mdiv_innerHTML = tt;
};

function clear_all_result() {
    console.log('clear_all_result()');
};

function clear_list() {
    document.getElementById('frm_tmp').src = "clear_all.php";
};

function aaa() {
    alert('loadScript()');
};

function parse_t(v) {
    let rr = v.split(":");
       
    if(rr.length == 3) {
        return rr[0]+"&nbsp;"+ parseInt(rr[1], 10)+":"+ parseInt(rr[2], 10)
    } else {
        return "("+v+")";
    };
};

function update_line(ip, traffic, dns_name) {

if(ip == '') return;

ii = 0;
while(1==1)
{
    
    n = document.getElementById('ip_'+ii);
    
    if(n == null) {
        console.log('need add ' + ip);
        add_line_to_maintable(ii, ip);
        if(allow_spur())  document.getElementById('check_spur').checked = true;
        //document.getElementById('check_geo').checked = true;
        if(allow_mikrotik()) document.getElementById('check_firewall').checked = true;
        return;
    };
    
    if(n.innerHTML == ip) 
    {
        o = document.getElementById('traffic_'+ii);

        o.innerHTML = parse_t(traffic);

        o = document.getElementById('dns_'+ii);

        o.innerHTML = dns_name;

        return;
    };
    ii++;
};
 
};

var u_tik = 1;

function update_result(r) {
    //console.log('update_result');
    document.getElementById("update_id").innerHTML = u_tik++;
    //o.innerHTML = "10000";
    
    let rr = r.split("\n");
    
    i = 0;
    while( i < rr.length) {
        let rrr = rr[i].split("\t");
        update_line(rrr[0], rrr[1], rrr[2]);
        i++;
    };
    //console.log('endd');
    
    
    free_frame_use('update_result');
};

function update() {
    if(frame_use_ == 0) {
        set_frame_use('update');
        document.getElementById('frm_tmp').src = "update.php";
    } else {
        console.log('busy u');
    };
};

function add_line_to_maintable(idx, ip) {

    //
    
    //var idx = 10000;
    
    var tbody = document.getElementById("MainTable").getElementsByTagName("tbody")[0];
    var row = document.createElement("tr");
    
    var td1 = document.createElement("td");
    td1.id = "ip_" + idx;
    td1.appendChild(document.createTextNode(ip));
    
    var td2 = document.createElement("td");
    td2.id = "dns_" + idx;
    td2.appendChild (document.createTextNode(""));

    var td3 = document.createElement("td");
    td3.id = "sni_" + idx;
    td3.appendChild (document.createTextNode(""));
    
    var td4 = document.createElement("td");
    td4.id = "traffic_" + idx;
    td4.appendChild (document.createTextNode(""));

    var td5 = document.createElement("td");
    td5.id = "geo_" + idx;
    td5.appendChild (document.createTextNode("?"));

    var td6 = document.createElement("td");
    td6.id = "spur_" + idx;
    td6.appendChild (document.createTextNode("?"));

    var td7 = document.createElement("td");
    td7.id = "cpp_" + idx;
    td7.appendChild (document.createTextNode("?"));

    var td8 = document.createElement("td");
    td8.id = "add_m_" + idx;
    td8.innerHTML = "<a href=javascript:add_mikrotik('"+idx+"')>add_mikrotik</a>";

    var td9 = document.createElement("td");
    td9.id = "firewall_" + idx;
    td9.appendChild (document.createTextNode("?"));

    var td10 = document.createElement("td");
    td10.id = "del_m_" + idx;
    td10.innerHTML = "<a href=javascript:del_mikrotik('"+idx+"')>del_mikrotik</a>";

    /*var link10=document.createElement("a");
    link10.appendChild(document.createTextNode("del"));
    link10.href = '#';
    link10.onclick = loadScript();

    td10.appendChild(link10);*/

    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    row.appendChild(td4);
    row.appendChild(td5);
    row.appendChild(td6);
    row.appendChild(td7);
    row.appendChild(td8);
    row.appendChild(td9);
    row.appendChild(td10);
    
    tbody.appendChild(row);
    
    //update_result("ip_10000");
    
    need_refresh_firewall();             
    
};


//-->
</script>
<?php

function test_curl()
{
  echo('222222222222222');

require('routeros_api.class.php');

$API = new RouterosAPI();

$API->debug = true;

if ($API->connect('10.212.65.249', 'admin', '111')) {

   $API->write('/interface/getall');

   $READ = $API->read(false);
   $ARRAY = $API->parseResponse($READ);

$i = count($ARRAY);
$j = 0;
while($j < $i)
{
  echo 'id='.$ARRAY[$j]['.id'].' | '.
       'name='.$ARRAY[$j]['name'].

       '<br>';
  $j++;
};

   print_r($ARRAY);

   $API->disconnect();

}


};

//test_curl();

?>
<table border=1 cellpadding=5 cellspacing=0>
<tr>
    <td><input type="checkbox" id=check_geo value="1">auto geo</p></td>
    <td><input type="checkbox" id=check_spur value="1">auto spur</p></td>
    <td><input type="checkbox" id=check_cpp value="1">auto cpp</p></td>
    <td><input type="checkbox" id=check_firewall value="1" checked="false">refresh firewall</p></td>
    <td><a href="javascript:clear_list()">clear list</a></td>
    <td id="update_id">update</td>
    <td><input type="checkbox" id=check_autoadd value="1">auto add microtik</p></td>
    <td><input type="checkbox" id=allow_mikrotik value="1">allow mikrotik</p></td>
    <td><input type="checkbox" id=allow_spur value="1">allow spur</p></td>
</tr>
</table>
<div class="prokrutka" id="mdiv">
<table border=1 cellpadding=5 cellspacing=0>
    <tr><td id_auto_ban_spur_nic>nic for auto ban spur = <b><? echo $auto_ban_spur_nic; ?></b></td><td><input type="checkbox" id=check_auto_ban_spur value="1">auto ban spur</p></td></tr>
    </table>
    <? echo $tt; ?>
</div>
<iframe id=frm_tmp width=300 height=100 style="display:none1;"></iframe>
<iframe id=frm_tmp2 width=300 height=100 style="display:none1;"></iframe>
<textarea cols=60 rows=10 wrap=hard id=memo1></textarea>
<a href=javascript:transform()>transform</a>&nbsp;
<a href=javascript:transform24()>24</a>
</body>

