<?php

require dirname(dirname(__FILE__))."/sample.config.php";
require CORE_ROOT."/Loader.php";

$github_client = new ApiClient_Github();
$commits = $github_client->get("/repos/russpos/baron/commits");

print_r($commits);

