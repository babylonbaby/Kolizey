<?php
//описание и документация по API INTRUM http://www.intrumnet.com/api/


	require_once __DIR__ . '/usage.php'; //настройте данный конфигурационный файл
	
	
	/*
	 * Пример удаления продукта
	 */
	
	return $api->deleteStock(
		// массив id для удаления
		array(
			280759,
			280729,
			280728
		)
	);
?>