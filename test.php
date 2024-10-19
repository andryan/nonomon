<?php
$file=fopen('test.csv','w');
$country=array('Indonesia','Malaysia','Thailand','Vietnam','Russian','Turkey');
$hostTotal=array();
for ($j=0; $j<count($country); $j++) {
  #$ch=curl_init('http://appserv.nonolive.com/user/findAndSort?__user_id=monitor&__guest_id=monitor&__v=0.1.0&fr=web&limit=150');
#  if (($country[$j] == 'Russia') || ($country[$j] == 'Turkey')) {
#    $url='http://fra.appserv.nonolive.com/user/explore?__location=Indonesia&__v=1.0.0&__platform=web&__user_id=monitor&__guest_id=monitor&limit=200&location='.$country[$j];
#  } else {
    $url='http://appserv.nonolive.com/user/explore?__location=Indonesia&__v=1.0.0&__platform=web&__user_id=monitor&__guest_id=monitor&limit=200&location='.$country[$j];
#  }
  $ch=curl_init($url);
#  echo "URL=$url\n";
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $json=curl_exec($ch);
  curl_close($ch);
  $array=json_decode($json, true);
  #print_r($array);
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
      if (@!$host[$hostid]['avatar']) {
        $host[$hostid]['avatar'] = '';
      }
      if (@$array['body']['models'][$i]['anchor_group'][0] == 'official') {
        $host[$hostid]['official']=1;
      } else {
        $host[$hostid]['official']=0;
      }
      #$host[$hostid]['stream_server'] = '';
      $host[$hostid]['country']=$country[$j];
      ++$live;
    }
  }
#  print_r($host);
  $hostTotal += $host;
#  echo "\$host=".count($host)." \$hostTotal=".count($hostTotal)."\n";
}
#print_r($hostTotal);

$mh=curl_multi_init();
#for ($i=0; $i<count($host); $i++) {
foreach ($hostTotal as $x => $y) {
#  if (($hostTotal[$x]['country'] == 'Russia') || ($hostTotal[$x]['country'] == 'Turkey')) {
#    $chcur[$x] = curl_init("http://fra.appserv.nonolive.com/live/getLiveServer?__user_id=monitor&__guest_id=monitor&__v=0.1.0&fr=web&user_id=".$hostTotal[$x]['user_id']);
#  } else {
    $chcur[$x] = curl_init("http://appserv.nonolive.com/live/getLiveServer?__user_id=monitor&__guest_id=monitor&__v=0.1.0&fr=web&user_id=".$hostTotal[$x]['user_id']);
#  }
  curl_setopt($chcur[$x], CURLOPT_RETURNTRANSFER, true);
  curl_multi_add_handle($mh, $chcur[$x]);
}
$running=null;
//execute the handles
do {
    curl_multi_exec($mh, $running);
} while($running > 0);

//close all the handles
#for ($i=0; $i<count($host); $i++) {
#  curl_multi_remove_handle($mh, $chcur[$i]);
#}
curl_multi_close($mh);

foreach ($chcur as $a) {
  $hostid = '';
  $result = curl_multi_getcontent($a);
#  parse_str($result, $arr);
  $arr=json_decode($result, true);
#  print_r($arr);
  if (($arr['code'] == 0) && ($arr['body']['user_model']['anchor_live'] == 11) && ($arr['body']['stream_server'])) { // make sure hosts are still online at this point
    #echo "\$arr['body']['stream_server']=".$arr['body']['stream_server']."\n";
    $hostid=$arr['body']['user_model']['user_id'];
    $hostTotal[$hostid]['stream_server'] = $arr['body']['stream_server'];
    $hostTotal[$hostid]['viewers'] = $arr['body']['user_model']['viewers'];
//    print_r($arr['body']);
  } else {
    // remove from array, if they are not
    #if (($arr['body']['stream_server'] == '') || (!$arr['body']['stream_server'])) {
    #  echo "Removing $hostid $a\n";
    #}
    //print_r($arr);
  }
}

#print_r($host);
foreach ($hostTotal as $key => $value) {
#  echo "DEBUG: $host $key $value\n";
  if (@$hostTotal[$key]['stream_server']) {
    $line = $hostTotal[$key]['user_id'].'|'.str_replace('|','-',$hostTotal[$key]['loginname']).'|'.$hostTotal[$key]['country'].'|'.$hostTotal[$key]['official'].'|'.$hostTotal[$key]['pic'].'|'.$hostTotal[$key]['avatar'].'|'.$hostTotal[$key]['viewers'].'|'.$hostTotal[$key]['stream_server']."\n";
    fwrite($file, $line);
    echo $line;
  }
  #fwrite($file, $host[$key]['user_id']."|".$host[$key]['loginname']."|".$host[$key]['pic']."|".$host[$key]['avatar']."|".$host[$key]['viewers']."\n");
  #echo $host[$key]['user_id']."|".$host[$key]['loginname']."|".$host[$key]['pic']."|".$host[$key]['avatar']."|".$host[$key]['viewers']."\n";
}
fclose($file);
?>
