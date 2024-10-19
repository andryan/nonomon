<?php
$ch=curl_init('http://appserv.nonolive.com/user/findAndSort?__user_id=monitor&__guest_id=monitor&__v=0.1.0&fr=web&limit=150');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json=curl_exec($ch);
curl_close($ch);
$array=json_decode($json, true);
#print_r($array);
#foreach($array['body']['models'] as $key => $value) {
$live=0;
$serial=time();
#echo "$serial\n";
#fwrite($file,"$serial\n");
$host = array();
for ($i=0; $i<count($array['body']['models']); $i++) {
  $arr=$array['body']['models'][$i];
  if ($arr['anchor_live']==11) {
    $hostid=$array['body']['models'][$i]['user_id'];
    $host[$hostid] = $array['body']['models'][$i];
//    array_push($host, $array['body']['models'][$i]);
    if (!$host[$hostid]['avatar']) {
      $host[$hostid]['avatar'] = '';
    }
    #$host[$hostid]['stream_server'] = '';
    ++$live;
  }
}
#print_r($host);

$mh=curl_multi_init();
#for ($i=0; $i<count($host); $i++) {
foreach ($host as $x => $y) {
  $chcur[$x] = curl_init("http://appserv.nonolive.com/live/getLiveServer?__user_id=monitor&__guest_id=monitor&__v=0.1.0&fr=web&user_id=".$host[$x]['user_id']);
  curl_setopt($chcur[$x], CURLOPT_RETURNTRANSFER, true);
  curl_multi_add_handle($mh,$chcur[$x]);
}
$running=null;
//execute the handles
do {
    curl_multi_exec($mh,$running);
} while($running > 0);

//close all the handles
#for ($i=0; $i<count($host); $i++) {
#  curl_multi_remove_handle($mh, $chcur[$i]);
#}
curl_multi_close($mh);

foreach ($chcur as $a) {
  $hostid = '';
  $result = curl_multi_getcontent($a);
//    parse_str($result, $arr);
  $arr=json_decode($result, true);
//  print_r($arr);
  if (($arr['code'] == 0) && ($arr['body']['user_model']['anchor_live'] == 11) && ($arr['body']['stream_server'])) { // make sure hosts are still online at this point
    #echo "\$arr['body']['stream_server']=".$arr['body']['stream_server']."\n";
    $hostid=$arr['body']['user_model']['user_id'];
    $host[$hostid]['stream_server'] = $arr['body']['stream_server'];
    $host[$hostid]['viewers'] = $arr['body']['user_model']['viewers'];
//    print_r($arr['body']);
  } else {
    // remove from array, if they are not
    #if (($arr['body']['stream_server'] == '') || (!$arr['body']['stream_server'])) {
    #  echo "Removing $hostid $a\n";
    #}
    //print_r($arr);
  }
}

$file=fopen('test.csv','w');
//print_r($host);
foreach ($host as $key => $value) {
//  echo "$host $key $value\n";
  if (@$host[$key]['stream_server']) {
    fwrite($file, $host[$key]['user_id']."|".$host[$key]['loginname']."|".$host[$key]['pic']."|".$host[$key]['avatar']."|".$host[$key]['viewers']."|".$host[$key]['stream_server']."\n");
    echo $host[$key]['user_id']."|".$host[$key]['loginname']."|".$host[$key]['pic']."|".$host[$key]['avatar']."|".$host[$key]['viewers']."|".$host[$key]['stream_server']."\n";
  }
  #fwrite($file, $host[$key]['user_id']."|".$host[$key]['loginname']."|".$host[$key]['pic']."|".$host[$key]['avatar']."|".$host[$key]['viewers']."\n");
  #echo $host[$key]['user_id']."|".$host[$key]['loginname']."|".$host[$key]['pic']."|".$host[$key]['avatar']."|".$host[$key]['viewers']."\n";
}
/*

     $ch2=curl_init("http://appserv.nonolive.com/live/getLiveServer?user_id=$user_id");
     curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
     $json2=curl_exec($ch2);
     curl_close($ch2);
     $array2=json_decode($json2, true);
#     print_r($array2);
     if (isset($array2['body']['stream_server'])) {
       echo $arr['user_id']."|".$arr['loginname']."|".$arr['pic']."|".$arr['avatar']."|".$array2['body']['user_model']['viewers']."|".$array2['body']['stream_server']."/$user_id\n";
       fwrite($file,$arr['user_id']."|".$arr['loginname']."|".$arr['pic']."|".$arr['avatar']."|".$array2['body']['stream_server']."/$user_id\n");
       system("./ffmpegsnap.sh ".$array2['body']['stream_server']." ".$user_id." ".$serial);
     }
   }
#  echo "$key $value\n";
#  if (($key == "anchor_live") && ($value == 11)) {
#    echo "BOO\n";
#  }
}
#echo "Total live hosts: $live\n";
*/
?>
