<?php
require("vendor/autoload.php");
$openapi = \OpenApi\scan(ROOT.DS.APP_DIR);
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
