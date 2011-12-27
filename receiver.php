<?php

ini_set('display_errors', 1);
include 'config.php';
include 'include.php';


$current_user = new CurrentUser(Request::get('viewer_id'));

$server = new Server();

$server->process();

$result = $server->getResult();


@ob_end_clean();
echo json_encode($result);
exit(0);