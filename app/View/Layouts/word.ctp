<?php
// file: app/views/layouts/msword.ctp
header("Content-Type: application/vnd.ms-word");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past - so must always re-read
header("content-disposition: attachment;filename=myfile.doc"); //this will be the name of the file the user downloads
echo $this->fetch('content');
?>
