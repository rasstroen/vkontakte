
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
		<script src="http://vkontakte.ru/js/api/xd_connection.js?2" type="text/javascript"></script>
		<script>
			var variables = [];
<?php
$exec_url = '/server.php';
$cache_breaker = time();
foreach ($_GET as $f => $v) {
	echo 'variables["' . $f . '"]="' . $v . "\";\n";
}
?>
	var exec_url = "<?= $exec_url ?>";
		</script>
		<script src="/static/js/jquery.min.js?<?= $cache_breaker; ?>" type="text/javascript"></script>
		<script src="/static/js/application.js?<?= $cache_breaker; ?>" type="text/javascript"></script>
		<link rel="stylesheet" href="/static/css/main.css?<?= $cache_breaker; ?>" />
		<script src="http://api-maps.yandex.ru/1.1/index.xml?key=AHwh804BAAAAj79yDQMA14DxOOEl3a_yKCAwzzotflnsy_4AAAAAAAAAAABb8qjG4pn1W1gyosgMGdQUhd1ezA==" type="text/javascript"></script>
	</head>
	<body id="container">
		<div id="preloader"></div>
		<div id="YMapsID"></div>
	</body>
</html>