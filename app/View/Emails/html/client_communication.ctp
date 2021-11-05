 <html>
  <head>
  </head>
  <body>
	<?php echo $this->Element($emailBodyElementPath, 
            array(
            'merchantDBA'=> isset($merchantDBA)?$merchantDBA:null,
            'appPdfUrl' => isset($appPdfUrl)?$appPdfUrl:null,
            'appAccesslink' => isset($appAccesslink)?$appAccesslink:null,
            'ownerName' => isset($ownerName)?$ownerName:null,
            'logoImageFileName' => isset($logoImageFileName)?$logoImageFileName:null,
        )); ?>
  </body>
</html>
