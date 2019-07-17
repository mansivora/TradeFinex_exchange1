<?php

$image=$_GET['name'];

$remoteImage="http://webftp.alphaex.net/images/".$image;
$imginfo = getimagesize($remoteImage);

header("Content-type: {$imginfo['mime']}");
//echo file_get_contents('ftp://webftpalphaexnet:RF#$rff@EW@webftp.alphaex.net/images/' . $image);

echo file_get_contents($remoteImage);

//$ftp_server = "78.129.229.19";
//$ftp_username = 'webftpalphaexnet';
//$ftp_userpass = 'RF#$rff@EW';
//$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
//
//if (@ftp_login($ftp_conn, $ftp_username, $ftp_userpass)) {
//    ftp_pasv($ftp_conn, true);
//    $path = "./images";
//    $file_list = ftp_nlist($ftp_conn, $path);
//    if (in_array($image,$file_list))
//    {
//        try {
////            echo file_get_contents("ftp://webftpalphaexnet:RF#$rff@EW@webftp.alphaex.net/images/" . $image);
//        echo file_get_contents("ftp://webftp.alphaex.net/images/".$image);
//    }
//    catch(Exception $e)
//    {
//        echo $e->getMessage();
//    }
//}
//}
//ftp_close($ftp_conn);
?>