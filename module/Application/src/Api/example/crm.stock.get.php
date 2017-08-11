<?php
//описание и документация по API INTRUM http://www.intrumnet.com/api/


	require_once __DIR__ . '/usage.php'; //настройте данный конфигурационный файл
	
	
	
	/*
	 * Вывод списка продуктов c фильтром по цене, первые 100 штук
	 */

	// цена от 2000
	//$value = '>= 2000';
	// цена до 3000
	//$value = '<= 3000';
	// цена между 2000 и 3000
	$value = '2000 & 3000';
	 
	return $api->getStockByFilter(
		array(
			// тип продукта
			'type' => 1,
			// категория
			'category' => 1,
			// фильтр по полю
			'fields' => array(
				array(
					'id' => 339,
					'value' => $value
				)
			),
			// число записей
			'limit' => 100,
			// страница
			'page' => 1
		)
	);
?>