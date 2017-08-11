<?php
//описание и документация по API INTRUM http://www.intrumnet.com/api/
	
	require_once __DIR__ . '/api.intrum.php';
	require_once __DIR__ . '/cache.intrum.php';
	
	IntrumExternalCache::getInstance()->setup(
		array(
			"folder" => __DIR__ . "/cache",
			"expire" => 600
		)
	);
	
	$api = IntrumExternalAPI::getInstance()
	->setup(
		array(
			"host" => "hostname.intrumnet.com", // имя intrum хоста
			"apikey" => "a027466e593316ea8db7d02a9df*****",// выданный api ключ
			"cache" => false // рекомендуется включать кэш (true) в production версии
			//, "port" => 80 
		)
	);

?>