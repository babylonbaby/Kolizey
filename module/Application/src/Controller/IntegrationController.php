<?php
namespace Application\Controller;

use Application\Api\IntrumExternalAPI as Api;
use Application\Entity\Employee;
use Application\Entity\Housing;
use Application\Entity\ObjectImages;
use Application\Entity\RealtyCategories;
use Application\Entity\RealtyObject;
use Application\Entity\RealtyType;
use Application\Entity\ResidentialComplex;
use Application\Entity\Section;

class IntegrationController extends AbstractController
{
//Формат данных
//radio	Выбор Да(1) Нет(0)
//select	Выбор одного
//multiselect	Выбор нескольких
//date	Дата 2014-03-14
//datetime	Дата 2014-03-14 21:34:05
//time	Время 21:34:05
//integer	Целое
//decimal	Вещественное
//text	Текст
//price	Цена
//file	Ссылка на файл
//- Для продуктов
//изображения
///files/crm/product/$filename - оригинал
///files/crm/product/resized200x200/$filename - маленькое
///files/crm/product/resized800x600/$filename - большое
//прочие
///files/crm/product/$filename
//- Остальные файлы
///files/crm/$filename
//point	Гео координата 55.753366 37.620908
//integer_range	Диапазон целых от - до
//decimal_range	Диапазон вещественных от - до
//date_range	Диапазон даты от - до
//time_range	Диапазон времени от - до
//datetime_range	Диапазон дата+время от - до
//Внимание !
//
//Для типов запрашиваемых объектов "Продукты" и "Заявки", поля с типом select и multiselect, зависящие от варианта выбора родителя (nested_selects) по умолчанию не отдаются, их нужно загружать отдельно т.к. количество вариантов может достигать наскольких тысяч.
//
//Загрузка вариантов выбора
//URL:	http://domainname.intrumnet.com:81/sharedapi/utils/variants
//PHP библиотека	метод: getSelectVariants
//params:
//property_id - id поля выбора
//Загрузка вариантов выбора привязанных к конкретному значению
//URL:	http://domainname.intrumnet.com:81/sharedapi/utils/binded
//PHP библиотека	метод: getBindedSelectVariants
//params:
//bind - id варианта выбора

    private $dirPath = '/home/kz/demo.kolizeyrealty.ru/docs/skeleton-application/public/img/';

    public function realtyCategoriesAction()
    {
        $api = Api::getInstance();
        $res = $api->getStockCategory();
        if ($res['status'] == 'success') {
            foreach ($res['data'] as $datas) {
                foreach ($datas as $data) {
                    $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => $data['name']]);
                    if (!$category) {
                        $category = new RealtyCategories();
                        $category->setName($data['name']);
                        $this->entityManager->persist($category);
                        $this->entityManager->flush();
                    }
                }
            }
        }
    }

    public function realtyTypesAction()
    {
        $api = Api::getInstance();
        $res = $api->getStockTypes();
        if ($res['status'] == 'success') {
            foreach ($res['data'] as $data) {
                $category = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['name' => $data['name']]);
                if (!$category) {
                    $category = new RealtyType();
                    $category->setName($data['name']);
                    $this->entityManager->persist($category);
                    $this->entityManager->flush();
                }
            }
        }
    }

    public function realtyStockAction()
    {
        //массив типов недвижимости
        $types = [
            'Городская недвижимость' => 1,
            'Загородная недвижимость' => 2,
            'Коммерческая недвижимость' => 3,
            'Другая недвижимость' => 4,
            'Зарубежная недвижимость' => 5,
        ];
        $api = Api::getInstance();
//        dd($api->getStockByFilter(['byid' => '555388'])['data']['list'][0]['fields']);

        $repository = $this->entityManager->getRepository(RealtyObject::class);
        //пройти по всем типам недвижимости
        foreach ($types as $type) {
            $res = $api->getStockByFilter(['type' => $type, 'page' => 1, 'publish' => 1]);
            $this->saveStock($res);
            $pages = ceil($res['data']['count'] / 20);
            for ($i = 2; $i <= $pages; $i++) {
                $res = $api->getStockByFilter(['type' => $type, 'page' => $i, 'publish' => 1]);
                $this->saveStock($res);
            }
        }
        //флаги аренды
        $res = $api->getStockByFilter(['type' => 1, 'category' => 6, 'page' => 1, 'publish' => 1]);
        $pages = ceil($res['data']['count'] / 20);
        foreach ($res['data']['list'] as $data) {
            $stock = $repository->findOneBy(['crmId' => $data['id']]);
            $stock->setRenta(true);
        }
        for ($i = 2; $i <= $pages; $i++) {
            $res = $api->getStockByFilter(['type' => 1, 'category' => 6, 'page' => $i, 'publish' => 1]);
            foreach ($res['data']['list'] as $data) {
                $stock = $repository->findOneBy(['crmId' => $data['id']]);
                $stock->setRenta(true);
            }
        }

        $res = $api->getStockByFilter(['type' => 2, 'category' => 25, 'page' => 1, 'publish' => 1]);
        $pages = ceil($res['data']['count'] / 20);
        foreach ($res['data']['list'] as $data) {
            $stock = $repository->findOneBy(['crmId' => $data['id']]);
            $stock->setRenta(true);
        }
        for ($i = 2; $i <= $pages; $i++) {
            $res = $api->getStockByFilter(['type' => 2, 'category' => 25, 'page' => $i, 'publish' => 1]);
            foreach ($res['data']['list'] as $data) {
                $stock = $repository->findOneBy(['crmId' => $data['id']]);
                $stock->setRenta(true);
            }
        }

        //Снятие публикации старых объектов
        $stocks = $repository->findAll();
        foreach ($stocks as $stock) {
            $datetime1 = $stock->getDateEdit();
            $datetime2 = new \DateTime();
            $interval = $datetime1->diff($datetime2);
            if ($interval->d >= 1) {
                $stock->setPublish(false);
            }
        }
        $this->entityManager->flush();
    }

    private function saveStock(array $res)
    {
        $api = Api::getInstance();
        foreach ($res['data']['list'] as $product_info) {
            $stock = $this->entityManager->getRepository(RealtyObject::class)->findOneBy(['crmId' => $product_info['id']]);
            if (!$stock) {
                $stock = new RealtyObject();
                $stock->setCrmId($product_info['id']);
            }
            $stock->setName($product_info['name']);
            $stock->setPublish($product_info['publish']);
            $addDateTime = new \DateTime($product_info['date_add']);
            $stock->setDateAdd($addDateTime);
            $editDateTime = new \DateTime();
            $stock->setDateEdit($editDateTime);
            $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => $product_info['stock_type']]);
            $stock->setRealtyType($type);


            $images = [];
            $price = 0;
            $location = [];

            if (isset ($product_info['fields'])) {
                foreach ($product_info['fields'] as $field) {
                    //Category
                    // Новостройки
                    if ($field['id'] == '776' && $field['value'] == 'квартира новостройка') {
                        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Новостройка']);
                        if ($category) {
                            $stock->addCategory($category);
                        }
                    }
                    //Вторичка
                    if ($field['id'] == '776' && $field['value'] == 'квартира вторичка') {
                        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Вторичка']);
                        if ($category) {
                            $stock->addCategory($category);
                        }
                    }
                    //Комнаты и доли
                    if ($field['id'] == '776' && $field['value'] == 'комнаты и доли') {
                        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Доли']);
                        if ($category) {
                            $stock->addCategory($category);
                        }
                    }
                    //Спецпредложение
                    if ($field['id'] == '575' && $field['value'] == '1') {
                        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Спецпредложение']);
                        if ($category) {
                            $stock->addCategory($category);
                        }
                    }
                    // Дома
                    if ($field['id'] == '778' && $field['value'] == 'дом') {
                        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Дома']);
                        if ($category) {
                            $stock->addCategory($category);
                        }
                    }
                    // Коттеджи
                    if ($field['id'] == '778' && $field['value'] == 'коттедж') {
                        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Коттедж']);
                        if ($category) {
                            $stock->addCategory($category);
                        }
                    }
                    // Дачи
                    if ($field['id'] == '778' && $field['value'] == 'дача') {
                        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Дача']);
                        if ($category) {
                            $stock->addCategory($category);
                        }
                    }
                    // Участок
                    if ($field['id'] == '778' && $field['value'] == 'участок') {
                        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Участок']);
                        if ($category) {
                            $stock->addCategory($category);
                        }
                    }
                    //Категории коммерческой недвижимости
                    if ($field['id'] == '777') {
                        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => $field['value']]);
                        if ($category) {
                            $stock->addCategory($category);
                        }
                    }

                    //Rooms
                    if ($field['id'] == '446') {
                        $stock->setRooms($field['value']);
                    }

//                    //Renta
//                    if ($field['id'] == '813') {
//                        $stock->setRenta($field['value']);
//                    }

                    //DateExp
                    if ($field['id'] == '1112') {
                        if (!empty($field['value'])) {
                            $date = new \DateTime($field['value']);
                        } else {
                            $date = null;
                        }
                        $stock->setDateExp($date);
                    }

                    // Images
                    if ($field['type'] == 'file') {
                        //Поищем картинку в базе
                        $image = $this->entityManager->getRepository(ObjectImages::class)->findOneBy(
                            [
                                'name' => $field['value'],
                                'object' => $stock,
                            ]
                        );
                        //Если такой нет, то добавим ее
                        if (!$image) {
                            $image = new ObjectImages();
                            $image->setName($field['value']);
                            $image->setObject($stock);
                            $this->entityManager->persist($image);
                        }
                    }

                    // Price
                    if ($field['type'] == 'price' AND in_array($field['id'], [491, 470, 528, 810])) {
                        $price = str_replace(' ', '', $field['value']);
                    }

                    // Description
                    if (in_array($field['id'], [625, 624, 626, 809])) {
                        $description = nl2br($field['value']);
                        $stock->setDescription($description);
                    }

                    // Metro
                    if (in_array($field['id'], [485])) {
                        $metro = $field['value'];
                        $stock->setMetro($metro);
                    }

                    // level
                    if (in_array($field['id'], [448])) {
                        $stock->setLevel($field['value']);
                    }

                    if (in_array($field['id'], [467])) {
                        $stock->setLevels($field['value']);
                    }

                    // Location
                    if (in_array($field['id'], [512, 481, 553, 802])) {
                        $location[] = $field['value'];
                    }

                    if (in_array($field['id'], [513, 482, 554, 803])) {
                        $location[] = $field['value'];
                    }

                    if (in_array($field['id'], [555, 804])) {
                        $location[] = $field['value'];
                    }

                    // Square
                    if (in_array($field['id'], [488, 447, 526, 808])) {
                        $square = $field['value'];
                        $stock->setSquare($square);
                    }

                    // Point
                    if ($field['type'] == 'point') {
                        $point = $field['value'];
                        $point = $point['x'] . ',' . $point['y'];
                        $stock->setPoint($point);
                    }

                    //Расстояние от МКАД
                    if (in_array($field['id'], [1168])) {
                        $destination = $field['value'];
                        $stock->setDestination($destination);
                    }

                    //Улица
                    if (in_array($field['id'], [667])) {
                        $street = $field['value'];
                        $stock->setStreet($street);
                    }

                    //Номер дома
                    if (in_array($field['id'], [484])) {
                        $home = $field['value'];
                        $stock->setHome($home);
                    }

                    //Название комплекса
                    if (in_array($field['id'], [1188])) {
                        $complexName = $field['value'];
                        $stock->setComplexName($complexName);
                    }

                    //Номер квартиры
                    if (in_array($field['id'], [636])) {
                        $number = $field['value'];
                        $stock->setNumber($number);
                    }

                }
            }

            $location = implode(', ', $location);
            $stock->setLocation($location);

//            $price = number_format($price, '0', '', ' ');
            $stock->setPrice($price);

            $employee = $this->entityManager->getRepository(Employee::class)
                ->findOneBy(
                    [
                        'crmId' => $product_info['author'],
                    ]
                );
            if (!$employee) {
                $employer_info = $api->filterEmployee(
                    [
                        'id' => $product_info['author'],
                    ]
                );

                $employer_info = $employer_info['data'][$product_info['author']];

                $employee = new Employee();
                $employee->setCrmId($product_info['author']);
                $employee->setPost($employer_info['post']);
                $employee->setName($employer_info['name']);
                $employee->setSecondname($employer_info['secondname']);
                $employee->setSurname($employer_info['surname']);

                $email = $employer_info['internalemail'];
                if ($email) {
                    $email = reset($email);
                    $email = $email['email'];
                    $employee->setEmail($email);
                }


                $phone = $employer_info['mobilephone'];
                if ($phone) {
                    $phone = reset($phone);
                    $phone = $phone['phone'];
                    $employee->setPhone($phone);
                }

            }

            $stock->setEmployee($employee);

            $this->entityManager->persist($employee);
            $this->entityManager->persist($stock);
            $this->entityManager->flush();
        }
    }

    /**
     * Скачивание картинок, что важно не тратит количество запросов к Api.
     */
    /* TODO Подумать над удалением ненужных картинок*/
    public function downloadImagesAction()
    {
        $images = $this->entityManager->getRepository(ObjectImages::class)->findAll();
        foreach ($images as $image) {
            if (!file_exists($this->dirPath . $image->getName())) {
//                echo $image->getName();
                $ch = curl_init('https://intrum11-12.intrumnet.com/files/crm//product/resized800x600/' . $image->getName() . '?' . time());
                $fp = fopen($this->dirPath . $image->getName(), 'wb');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);
//                echo ' download success </br>';
            }
        }
    }


    public function createComplexAction()
    {
        //Для начала получим список всех активных квартир-новостроек
        //Тип городской недвижимости
        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 1]);
        $objects = $this->entityManager->getRepository(RealtyObject::class)->findBy(
            [
                'realtyType' => $type,
                'publish' => true
            ]
        );
        /** @var RealtyCategories $category */
        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Новостройка']);
        foreach ($objects as $key => $item) {
            if (!$item->hasCategory($category)) {
                unset($objects[$key]);
            }
        }
        //После чего пройдем по ним циклом и поищем  на предмет принадлежности к ЖК
        /** @var RealtyObject $object */
        foreach ($objects as $object) {
            $complexName = $object->getComplexName();
            $complexAddress = $object->getStreet() . ' ' . $object->getHome();
            if (!empty($complexName)) {
                //Сначало проверим не создан ли он раньше
                $complex1 = $this->entityManager
                    ->getRepository(ResidentialComplex::class)
                    ->findOneBy(['name' => $complexName, 'del' => false,]);
                $complex2 = $this->entityManager
                    ->getRepository(ResidentialComplex::class)
                    ->findOneBy(['address' => $complexAddress, 'del' => false,]);
                //если нет то создадим его
                if (empty($complex1) && empty((int)$object->getComplexName()) && empty($complex2)) {
                    $complex = new ResidentialComplex();
                    $complex->setName($complexName);
                    $complex->setDistance($object->getDestination());
                    $complex->setAddress($complexAddress);
                    /** @var \DateTime $commissioning */
                    $commissioning = $object->getDateExp();
                    $complex->setCommissioning($commissioning->format('m.Y'));
                    $complex->setLongDescription($object->getDescription());
                    $complex->setLocation($object->getPoint());
                    $complex->setDel(false);
                    $this->entityManager->persist($complex);

                    //создаем сущность корпуса.
                    $housing = new Housing();
                    $housing->setComplex($complex);
                    $housing->setLevels($object->getLevels());
                    $this->entityManager->persist($housing);

                    //создаем сущность секции
                    $section = new Section();
                    $section->setHousing($housing);
                    $this->entityManager->persist($section);

                    $this->entityManager->flush();
                }
            }
//            //Если в названии квартиры присутсвует ЖК "бла-бла" - то это наш вариант
//            if (preg_match('/ЖК/', $name)){
//                if (preg_match('~"([^"]*)"~u' , $name , $complexName)) {
//                }
//            }
        }
        //После того как созданы новые ЖК выберем их в массив
        $complexes = $this->entityManager->getRepository(ResidentialComplex::class)->findBy(['del' => false,]);
//        dd($complexes);
        //Теперь добавим квартиры к комплексу
        /** @var RealtyObject $object */
        foreach ($objects as $object) {
            /** @var ResidentialComplex $complex */
            foreach ($complexes as $complex) {
                if ($complex->getName() == $object->getComplexName() ||
                    $complex->getAddress() == $object->getStreet() . ' ' . $object->getHome()
                ) {

                    /** @var Housing $housing */
                    $housings = $complex->getHousing();
                    $housing = $housings[0];
                    //проставим цену на квартиры в комплексе
                    switch ($object->getRooms()) {
                        case 1:
                            $cost = $housing->getOneRoomCost();
                            $price = $object->getPrice();
                            if ($price < $cost || empty($cost)) {
                                $housing->setOneRoomCost($price);
                            }
                            break;
                        case 2:
                            $cost = $housing->getTwoRoomCost();
                            $price = $object->getPrice();
                            if ($price < $cost || empty($cost)) {
                                $housing->setTwoRoomCost($price);
                            }
                            break;
                        case 3:
                            $cost = $housing->getThreeRoomCost();
                            $price = $object->getPrice();
                            if ($price < $cost || empty($cost)) {
                                $housing->setThreeRoomCost($price);
                            }
                            break;
                        case 4:
                            $cost = $housing->getFourRoomCost();
                            $price = $object->getPrice();
                            if ($price < $cost || empty($cost)) {
                                $housing->setFourRoomCost($price);
                            }
                            break;
                    }
                    /** @var Section $section */
                    $sections = $housing->getSections();
                    $section = $sections[0];

                    $section->getObjects()->add($object);
                    $object->setSection($section);
                }
            }
        }
        $this->entityManager->flush();
    }
}
