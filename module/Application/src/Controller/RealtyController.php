<?php
namespace Application\Controller;

use Application\Entity\RealtyCategories;
use Application\Entity\RealtyObject;
use Application\Entity\RealtyType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\Realty as FormNS;
use Zend\View\Model\JsonModel;
use Core\InputFilter\GroupsInputFilter;
use Grid\GridAdapterPluginManager as GridAdapterPMNS;
use Application\Grid\Realty as GridNS;
use Zend\Http\Request;
use Application\Entity as EntityNS;
use Core\Exception\NotValidException;
use Application\Entity\ResidentialComplex;
use Application\Entity\Housing;


class RealtyController extends AbstractController
{
    public function indexAction()
    {

    }

    public function complexGridAction()
    {
        $form = $this->fm->get(FormNS\Complex\Grid\Form::class, 'complex');

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function dataComplexAction()
    {
        $form = $this->fm->get(FormNS\Complex\Grid\Form::class, 'complex');

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $gridInputData = $request->getQuery()->getArrayCopy();

        if ($request->getQuery('_search') == true
            && ($filters = json_decode($request->getQuery('filters'), true)) != false
        ) {
            $input = new GroupsInputFilter();
            $input->setData($filters);
            if (($res = $input->isValid()) != false) {
                $tree = $input->getValues();
                $gridInputData['where'] = $tree;
            } else {
                throw new \Exception(print_r($input->getMessages(), true));
            }
        }

        /** @var GridAdapterPMNS\GridAdapterPluginManager $gridPM */
        $gridPM = $this->sm->get(GridAdapterPMNS\GridAdapterPluginManagerInterface::class);

        /** @var  \Application\Grid\Realty\Complex\Adapter $gridAdapter */
        $gridAdapter = $gridPM->build(GridNS\Complex\Adapter::class, ['form' => $form]);
        $res = $gridAdapter->getData($gridInputData);
//        dd(new JsonModel($res));
        return new JsonModel($res);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $this->layout("layout/ajax");
        }
        $errMsg = null;
        $form = null;

        try {
            /** @var EntityNS\ResidentialComplex $objE */
            $objE =  new EntityNS\ResidentialComplex;
            $form = $this->fm->get(FormNS\Complex\Edit\Form::class, ['name' => 'AddComplex']);
            $form->bind($objE);
            if ($request->isPost()) {
                $this->entityManager->beginTransaction();
                try {
                    $data = $request->getPost()->toArray();
                    $form->setData($data);
                    $form->validate();
                    $this->entityManager->persist($objE);
                    $this->entityManager->flush();
                    $this->entityManager->commit();
                    //редирект. если форма на отдельной странице
                    if (!$request->isXmlHttpRequest()) {
                        return $this->redirect()->toUrl('/nedvizhimost/complex-grid');
                    }

                    /** @var \Zend\Http\PhpEnvironment\Response $response */
                    $response = $this->getResponse();
                    $response->setStatusCode(\Zend\Http\PhpEnvironment\Response::STATUS_CODE_202);
                } catch(NotValidException $e) {
                    $this->entityManager->rollback();
                    d($e->getMessages());
//                    $form->setFormMessage('Обнаружены ошибки при проверке данных', 'error');
                } catch (\Exception $e) {
                    $this->entityManager->rollback();
                    d($e->getMessage());
//                    $form->setFormMessage($e->getMessage(), 'error');
                }
            }
        } catch(\Exception $e) {
            d($e->getMessage());
            $errMsg = $e->getMessage();
        }
        return new ViewModel([
            'errMsg' => $errMsg,
            'form' => $form,
        ]);
    }

    /**
     * Переключатель признака удаления.
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function complexToggleRemoveAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $this->layout("layout/ajax");
        }
        try {
            if (($id = $this->params('id')) == null) {
                throw new \RuntimeException('Не передан идентификатор');
            }
            if (($del = $this->params('del')) == null) {
                throw new \RuntimeException('Не передан признак удаления');
            }
            $em = $this->entityManager;
            /** @var EntityNS\ResidentialComplex $objE */
            if (($objE = $em->find(EntityNS\ResidentialComplex::class, $id)) == null) {
                throw new \RuntimeException('Объект не существует id=' . $id);
            }
            $em->beginTransaction();
            try {
                $objE->setDel($del);
                $em->flush();
                $em->commit();
                return new JsonModel([
                    'status' => 0,
                    'msg' => 'Успешно изменено',
                    'data' => $objE->getDel()
                ]);
            } catch (\Throwable $e) {
                $em->rollback();
                throw $e;
            }
        } catch (\Throwable $e) {
            return new JsonModel([
                'status' => 1,
                'msg' => $e->getMessage(),
                'data' => null
            ]);
        }
    }

    public function editAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $this->layout("layout/ajax");
        }
        $errMsg = null;
        $form = null;

        try {
            if (($id = $this->params('id')) == null) {
                throw new \Exception('Не передан идентификатор транспортного средства');
            }
            $em = $this->entityManager;
            /** @var EntityNS\Car $objE */
            if (($objE = $em->find(EntityNS\Car::class, $id)) == null) {
                throw new \Exception('Объект не существует id='.$id);
            }
            $form = $this->fm->get(FormNS\Edit\Form::class, ['name' => 'EditCar']);
            $form->bind($objE);
            if ($request->isPost()) {
                $em->beginTransaction();
                try {
                    $data = $request->getPost()->toArray();
                    $form->setData($data);
                    $form->validate();
                    $date = new \DateTime();
                    $objE->setEditDate($date);
                    $em->flush();
                    $em->commit();

                    /** @var \Zend\Http\PhpEnvironment\Response $response */
                    $response = $this->getResponse();
                    $response->setStatusCode(\Zend\Http\PhpEnvironment\Response::STATUS_CODE_202);
                } catch(NotValidException $e) {
                    $em->rollback();
//                    $form->setFormMessage('Обнаружены ошибки при проверке данных', 'error');
                } catch (\Exception $e) {
                    $em->rollback();
//                    $form->setFormMessage($e->getMessage(), 'error');
                }
            }
        } catch(\Exception $e) {
            $errMsg = $e->getMessage();
        }
        return new ViewModel([
            'errMsg' => $errMsg,
            'form' => $form,
        ]);
    }
    /**
     * Покупка квартиры
     */
    public function apartmentAction()
    {

    }

    /**
     * Новостройки
     */
    public function newAction()
    {

    }

    /**
     * Городская недвижимость
     */
    public function cityAction()
    {
        $city = $this->params('city');
        $page = $this->params('page');
        if (empty($page)) {
            $page = 1;
        }
        $sort = $this->params('sort');
        $url = '/nedvizhimost/city/';
        $active = '';
        $lastPage = false;
        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 1]);
        /** @var RealtyCategories $category */
        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Спецпредложение']);
        $specialItems = [];
        $items = $this->entityManager->getRepository(RealtyObject::class)->findBy(['realtyType' => $type, 'publish' => true]);
        if ($sort == 'profit') {
            $url = '/nedvizhimost/city/sort/profit/';
            $active = 'profit';
            foreach ($items as $key=>$item) {
                if (!$item->hasCategory($category)) {
                    unset($items[$key]);
                }
            }
        }
        $count = count($items);
        $collection = new ArrayCollection();
        foreach ($items as $item) {
            $collection->add($item);
        }
        $criteria = Criteria::create()
            ->setFirstResult(($page-1)*20);

        $items = $collection->matching($criteria);
        if (count($items) <= 20) {
            $lastPage = true;
        }

        $criteria->setMaxResults(20);
        switch ($sort) {
            case 'new':
                $criteria->orderBy(array("dateAdd" => Criteria::DESC));
                $url = '/nedvizhimost/city/sort/new/';
                $active = 'new';
                break;
            case 'price';
                $criteria->orderBy(['price' =>Criteria::ASC]);
                $url = '/nedvizhimost/city/sort/price/';
                $active = 'price';
                break;
            case 'date':
                $qb = $this->entityManager->createQueryBuilder();
                $criteria->orderBy(['dateExp' => Criteria::ASC]);
                $url = '/nedvizhimost/city/sort/date/';
                $active = 'date';
                break;
//            case 'profit':
//                $criteria->where(['category' => $category]);
//                break;
            default:
                $criteria->orderBy(['dateEdit' => Criteria::DESC]);
        }


        $items = $collection->matching($criteria);
        /** @var RealtyObject $item */
        foreach ($items as $key=>$item) {
            if ($item->hasCategory($category)) {
                $specialItems[] = $item;
                unset($items[$key]);
            }
        }

        return new ViewModel([
            'special' => $specialItems,
            'items' => $items,
            'page' => $page,
            'count' => $count,
            'url' => $url,
            'lastPage' => $lastPage,
            'active' => $active,
        ]);
    }

    /**
     * Вторичный рынок
     */
    public function afterMarketAction()
    {
        $city = $this->params('city');
        $page = $this->params('page');
        if (empty($page)) {
            $page = 1;
        }
        $sort = $this->params('sort');
        $url = '/nedvizhimost/after-market/';
        $active = '';
        $lastPage = false;
        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 1]);
        /** @var RealtyCategories $category */
        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Спецпредложение']);
        $specialItems = [];
        $items = $this->entityManager->getRepository(RealtyObject::class)->findBy(['realtyType' => $type, 'publish' => true]);
        $filterCategory = $this->entityManager->getRepository(RealtyCategories::class)->find('18');
        foreach ($items as $key=>$item) {
            if (!$item->hasCategory($filterCategory)) {
                unset($items[$key]);
            }
        }

        if ($sort == 'profit') {
            $url = '/nedvizhimost/after-market/sort/profit/';
            $active = 'profit';
            foreach ($items as $key=>$item) {
                if (!$item->hasCategory($category)) {
                    unset($items[$key]);
                }
            }
        }
        $count = count($items);
        $collection = new ArrayCollection();
        foreach ($items as $item) {
            $collection->add($item);
        }
        $criteria = Criteria::create()
            ->setFirstResult(($page-1)*20);

        $items = $collection->matching($criteria);
        if (count($items) <= 20) {
            $lastPage = true;
        }

        $criteria->setMaxResults(20);
        switch ($sort) {
            case 'new':
                $criteria->orderBy(array("dateAdd" => Criteria::DESC));
                $url = '/nedvizhimost/after-market/sort/new/';
                $active = 'new';
                break;
            case 'price';
                $criteria->orderBy(['price' =>Criteria::ASC]);
                $url = '/nedvizhimost/after-market/sort/price/';
                $active = 'price';
                break;
            case 'date':
                $qb = $this->entityManager->createQueryBuilder();
                $criteria->orderBy(['dateExp' => Criteria::ASC]);
                $url = '/nedvizhimost/after-market/sort/date/';
                $active = 'date';
                break;
//            case 'profit':
//                $criteria->where(['category' => $category]);
//                break;
            default:
                $criteria->orderBy(['dateEdit' => Criteria::DESC]);
        }


        $items = $collection->matching($criteria);
        /** @var RealtyObject $item */
        foreach ($items as $key=>$item) {
            if ($item->hasCategory($category)) {
                $specialItems[] = $item;
                unset($items[$key]);
            }
        }

        return new ViewModel([
            'special' => $specialItems,
            'items' => $items,
            'page' => $page,
            'count' => $count,
            'url' => $url,
            'lastPage' => $lastPage,
            'active' => $active,
        ]);
    }

    /**
     * Аренда
     */
    public function rentaAction()
    {
        $city = $this->params('city');
        $page = $this->params('page');
        if (empty($page)) {
            $page = 1;
        }
        $sort = $this->params('sort');
        $url = '/nedvizhimost/renta/';
        $active = '';
        $lastPage = false;
        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 1]);
        /** @var RealtyCategories $category */
        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Спецпредложение']);
        $specialItems = [];
        $items = $this->entityManager->getRepository(RealtyObject::class)->findBy(['realtyType' => $type, 'renta' => true, 'publish' => true]);
        if ($sort == 'profit') {
            $url = '/nedvizhimost/renta/sort/profit/';
            $active = 'profit';
            foreach ($items as $key=>$item) {
                if (!$item->hasCategory($category)) {
                    unset($items[$key]);
                }
            }
        }
        $count = count($items);
        $collection = new ArrayCollection();
        foreach ($items as $item) {
            $collection->add($item);
        }
        $criteria = Criteria::create()
            ->setFirstResult(($page-1)*20);

        $items = $collection->matching($criteria);
        if (count($items) <= 20) {
            $lastPage = true;
        }

        $criteria->setMaxResults(20);
        switch ($sort) {
            case 'new':
                $criteria->orderBy(array("dateAdd" => Criteria::DESC));
                $url = '/nedvizhimost/renta/sort/new/';
                $active = 'new';
                break;
            case 'price';
                $criteria->orderBy(['price' =>Criteria::ASC]);
                $url = '/nedvizhimost/renta/sort/price/';
                $active = 'price';
                break;
            case 'date':
                $qb = $this->entityManager->createQueryBuilder();
                $criteria->orderBy(['dateExp' => Criteria::ASC]);
                $url = '/nedvizhimost/renta/sort/date/';
                $active = 'date';
                break;
//            case 'profit':
//                $criteria->where(['category' => $category]);
//                break;
            default:
                $criteria->orderBy(['dateEdit' => Criteria::DESC]);
        }


        $items = $collection->matching($criteria);
        /** @var RealtyObject $item */
        foreach ($items as $key=>$item) {
            if ($item->hasCategory($category)) {
                $specialItems[] = $item;
                unset($items[$key]);
            }
        }

        return new ViewModel([
            'special' => $specialItems,
            'items' => $items,
            'page' => $page,
            'count' => $count,
            'url' => $url,
            'lastPage' => $lastPage,
            'active' => $active,
        ]);
    }

    /**
     * Загородная недвижимость
     */
    public function countryAction()
    {
        $city = $this->params('city');
        $page = $this->params('page');
        if (empty($page)) {
            $page = 1;
        }
        $sort = $this->params('sort');
        $url = '/nedvizhimost/country/';
        $active = '';
        $lastPage = false;
        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 3]);
        /** @var RealtyCategories $category */
        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Спецпредложение']);
        $specialItems = [];
        $items = $this->entityManager->getRepository(RealtyObject::class)->findBy(['realtyType' => $type, 'publish' => true]);
        if ($sort == 'profit') {
            $url = '/nedvizhimost/country/sort/profit/';
            $active = 'profit';
            foreach ($items as $key=>$item) {
                if (!$item->hasCategory($category)) {
                    unset($items[$key]);
                }
            }
        }
        $count = count($items);
        $collection = new ArrayCollection();
        foreach ($items as $item) {
            $collection->add($item);
        }
        $criteria = Criteria::create()
            ->setFirstResult(($page-1)*20);

        $items = $collection->matching($criteria);
        if (count($items) <= 20) {
            $lastPage = true;
        }

        $criteria->setMaxResults(20);
        switch ($sort) {
            case 'new':
                $criteria->orderBy(array("dateAdd" => Criteria::DESC));
                $url = '/nedvizhimost/country/sort/new/';
                $active = 'new';
                break;
            case 'price';
                $criteria->orderBy(['price' =>Criteria::ASC]);
                $url = '/nedvizhimost/country/sort/price/';
                $active = 'price';
                break;
            case 'date':
                $qb = $this->entityManager->createQueryBuilder();
                $criteria->orderBy(['dateExp' => Criteria::ASC]);
                $url = '/nedvizhimost/country/sort/date/';
                $active = 'date';
                break;
//            case 'profit':
//                $criteria->where(['category' => $category]);
//                break;
            default:
                $criteria->orderBy(['dateEdit' => Criteria::DESC]);
        }


        $items = $collection->matching($criteria);
        /** @var RealtyObject $item */
        foreach ($items as $key=>$item) {
            if ($item->hasCategory($category)) {
                $specialItems[] = $item;
                unset($items[$key]);
            }
        }

        return new ViewModel([
            'special' => $specialItems,
            'items' => $items,
            'page' => $page,
            'count' => $count,
            'url' => $url,
            'lastPage' => $lastPage,
            'active' => $active,
        ]);
    }

    /**
     * Коммерческая недвижимость
     */
    public function commerceAction()
    {
        $city = $this->params('city');
        $page = $this->params('page');
        if (empty($page)) {
            $page = 1;
        }
        $sort = $this->params('sort');
        $url = '/nedvizhimost/commerce/';
        $active = '';
        $lastPage = false;
        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 2]);
        /** @var RealtyCategories $category */
        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Спецпредложение']);
        $specialItems = [];
        $items = $this->entityManager->getRepository(RealtyObject::class)->findBy(['realtyType' => $type, 'publish' => true]);
        if ($sort == 'profit') {
            $url = '/nedvizhimost/commerce/sort/profit/';
            $active = 'profit';
            foreach ($items as $key=>$item) {
                if (!$item->hasCategory($category)) {
                    unset($items[$key]);
                }
            }
        }
        $count = count($items);
        $collection = new ArrayCollection();
        foreach ($items as $item) {
            $collection->add($item);
        }
        $criteria = Criteria::create()
            ->setFirstResult(($page-1)*20);

        $items = $collection->matching($criteria);
        if (count($items) <= 20) {
            $lastPage = true;
        }

        $criteria->setMaxResults(20);
        switch ($sort) {
            case 'new':
                $criteria->orderBy(array("dateAdd" => Criteria::DESC));
                $url = '/nedvizhimost/commerce/sort/new/';
                $active = 'new';
                break;
            case 'price';
                $criteria->orderBy(['price' =>Criteria::ASC]);
                $url = '/nedvizhimost/commerce/sort/price/';
                $active = 'price';
                break;
            case 'date':
                $qb = $this->entityManager->createQueryBuilder();
                $criteria->orderBy(['dateExp' => Criteria::ASC]);
                $url = '/nedvizhimost/commerce/sort/date/';
                $active = 'date';
                break;
//            case 'profit':
//                $criteria->where(['category' => $category]);
//                break;
            default:
                $criteria->orderBy(['dateEdit' => Criteria::DESC]);
        }


        $items = $collection->matching($criteria);
        /** @var RealtyObject $item */
        foreach ($items as $key=>$item) {
            if ($item->hasCategory($category)) {
                $specialItems[] = $item;
                unset($items[$key]);
            }
        }
        return new ViewModel([
            'special' => $specialItems,
            'items' => $items,
            'page' => $page,
            'count' => $count,
            'url' => $url,
            'lastPage' => $lastPage,
            'active' => $active,
        ]);
    }

    /**
     * Аренда
     */
    public function commerceRentaAction()
    {
        $city = $this->params('city');
        $page = $this->params('page');
        if (empty($page)) {
            $page = 1;
        }
        $sort = $this->params('sort');
        $url = '/nedvizhimost/commerce-renta/';
        $active = '';
        $lastPage = false;
        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 2]);
        /** @var RealtyCategories $category */
        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Спецпредложение']);
        $specialItems = [];
        $items = $this->entityManager->getRepository(RealtyObject::class)->findBy(['realtyType' => $type, 'renta' => true, 'publish' => true]);
        if ($sort == 'profit') {
            $url = '/nedvizhimost/commerce-renta/sort/profit/';
            $active = 'profit';
            foreach ($items as $key=>$item) {
                if (!$item->hasCategory($category)) {
                    unset($items[$key]);
                }
            }
        }
        $count = count($items);
        $collection = new ArrayCollection();
        foreach ($items as $item) {
            $collection->add($item);
        }
        $criteria = Criteria::create()
            ->setFirstResult(($page-1)*20);

        $items = $collection->matching($criteria);
        if (count($items) <= 20) {
            $lastPage = true;
        }

        $criteria->setMaxResults(20);
        switch ($sort) {
            case 'new':
                $criteria->orderBy(array("dateAdd" => Criteria::DESC));
                $url = '/nedvizhimost/commerce-renta/sort/new/';
                $active = 'new';
                break;
            case 'price';
                $criteria->orderBy(['price' =>Criteria::ASC]);
                $url = '/nedvizhimost/commerce-renta/sort/price/';
                $active = 'price';
                break;
            case 'date':
                $qb = $this->entityManager->createQueryBuilder();
                $criteria->orderBy(['dateExp' => Criteria::ASC]);
                $url = '/nedvizhimost/commerce-renta/sort/date/';
                $active = 'date';
                break;
//            case 'profit':
//                $criteria->where(['category' => $category]);
//                break;
            default:
                $criteria->orderBy(['dateEdit' => Criteria::DESC]);
        }


        $items = $collection->matching($criteria);

        /** @var RealtyObject $item */
        foreach ($items as $key=>$item) {
            if ($item->hasCategory($category)) {
                $specialItems[] = $item;
                unset($items[$key]);
            }
        }

        return new ViewModel([
            'special' => $specialItems,
            'items' => $items,
            'page' => $page,
            'count' => $count,
            'url' => $url,
            'lastPage' => $lastPage,
            'active' => $active,
        ]);
    }

    /**
     * Продажа
     */
    public function commerceSaleAction()
    {
        $city = $this->params('city');
        $page = $this->params('page');
        if (empty($page)) {
            $page = 1;
        }
        $sort = $this->params('sort');
        $url = '/nedvizhimost/commerce-sale/';
        $active = '';
        $lastPage = false;
        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 2]);
        /** @var RealtyCategories $category */
        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Спецпредложение']);
        $specialItems = [];
        $items = $this->entityManager->getRepository(RealtyObject::class)->findBy(['realtyType' => $type, 'renta' => false , 'publish' => true]);

        if ($sort == 'profit') {
            $url = '/nedvizhimost/commerce-sale/sort/profit/';
            $active = 'profit';
            foreach ($items as $key=>$item) {
                if (!$item->hasCategory($category)) {
                    unset($items[$key]);
                }
            }
        }
        $count = count($items);
        $collection = new ArrayCollection();
        foreach ($items as $item) {
            $collection->add($item);
        }
        $criteria = Criteria::create()
            ->setFirstResult(($page-1)*20);

        $items = $collection->matching($criteria);
        if (count($items) <= 20) {
            $lastPage = true;
        }

        $criteria->setMaxResults(20);
        switch ($sort) {
            case 'new':
                $criteria->orderBy(array("dateAdd" => Criteria::DESC));
                $url = '/nedvizhimost/commerce-sale/sort/new/';
                $active = 'new';
                break;
            case 'price';
                $criteria->orderBy(['price' =>Criteria::ASC]);
                $url = '/nedvizhimost/commerce-sale/sort/price/';
                $active = 'price';
                break;
            case 'date':
                $qb = $this->entityManager->createQueryBuilder();
                $criteria->orderBy(['dateExp' => Criteria::ASC]);
                $url = '/nedvizhimost/commerce-sale/sort/date/';
                $active = 'date';
                break;
//            case 'profit':
//                $criteria->where(['category' => $category]);
//                break;
            default:
                $criteria->orderBy(['dateEdit' => Criteria::DESC]);
        }


        $items = $collection->matching($criteria);

        /** @var RealtyObject $item */
        foreach ($items as $key=>$item) {
            if ($item->hasCategory($category)) {
                $specialItems[] = $item;
                unset($items[$key]);
            }
        }

        return new ViewModel([
            'special' => $specialItems,
            'items' => $items,
            'page' => $page,
            'count' => $count,
            'url' => $url,
            'lastPage' => $lastPage,
            'active' => $active,
        ]);
    }

    /**
     * Зарубежная
     */
    public function foreignAction()
    {
        $city = $this->params('city');
        $page = $this->params('page');
        if (empty($page)) {
            $page = 1;
        }
        $sort = $this->params('sort');
        $url = '/nedvizhimost/foreign/';
        $active = '';
        $lastPage = false;
        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 5]);
        /** @var RealtyCategories $category */
        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Спецпредложение']);
        $specialItems = [];
        $items = $this->entityManager->getRepository(RealtyObject::class)->findBy(['realtyType' => $type]);
        if ($sort == 'profit') {
            $url = '/nedvizhimost/foreign/sort/profit/';
            $active = 'profit';
            foreach ($items as $key=>$item) {
                if (!$item->hasCategory($category)) {
                    unset($items[$key]);
                }
            }
        }
        $count = count($items);
        $collection = new ArrayCollection();
        foreach ($items as $item) {
            $collection->add($item);
        }
        $criteria = Criteria::create()
            ->setFirstResult(($page-1)*20);

        $items = $collection->matching($criteria);
        if (count($items) <= 20) {
            $lastPage = true;
        }

        $criteria->setMaxResults(20);
        switch ($sort) {
            case 'new':
                $criteria->orderBy(array("dateAdd" => Criteria::DESC));
                $url = '/nedvizhimost/foreign/sort/new/';
                $active = 'new';
                break;
            case 'price';
                $criteria->orderBy(['price' =>Criteria::ASC]);
                $url = '/nedvizhimost/foreign/sort/price/';
                $active = 'price';
                break;
            case 'date':
                $qb = $this->entityManager->createQueryBuilder();
                $criteria->orderBy(['dateExp' => Criteria::ASC]);
                $url = '/nedvizhimost/foreign/sort/date/';
                $active = 'date';
                break;
//            case 'profit':
//                $criteria->where(['category' => $category]);
//                break;
            default:
                $criteria->orderBy(['dateEdit' => Criteria::DESC]);
        }


        $items = $collection->matching($criteria);

        /** @var RealtyObject $item */
        foreach ($items as $key=>$item) {
            if ($item->hasCategory($category)) {
                $specialItems[] = $item;
                unset($items[$key]);
            }
        }

        return new ViewModel([
            'special' => $specialItems,
            'items' => $items,
            'page' => $page,
            'count' => $count,
            'url' => $url,
            'lastPage' => $lastPage,
            'active' => $active,
        ]);
    }

    /**
     * ЖК
     */
    public function complexAction()
    {
        //TODO номер корпуса в сущность добавить
        $id = $this->params('id');
        $housingNumber = $this->params('housing');
        if (empty($housingNumber)) {
            $housingNumber = 1;
        }
        /** @var ResidentialComplex $complex */
        $complex = $this->entityManager->getRepository(ResidentialComplex::class)->findOneBy(['id' => $id, 'del' => false]);
        if (empty($complex)){
            $this->getResponse()->setStatusCode(404);
            throw new \Exception('Страница не найдена');
        }
        $apartments = $complex->getObjects();
        /** @var RealtyObject $object */
        $object = $apartments[0];
        $images = $object->getImages();
        $image = $images[0];

        $other = $this->entityManager->getRepository(ResidentialComplex::class)->findBy(['del' => false]);
        foreach ($other as $key=>$item) {
            if ($item->getId() == $id) {
                unset($other[$key]);
            }
        }

        //TODO после добавления номреа корпуса к фильтру добавить номер корпуса
        /** @var Housing $housing */
        $housing = $this->entityManager->getRepository(Housing::class)->findOneBy(['complex' => $complex]);

        $count = 0;
        for($i = 1; $i <= $housing->getLevels(); $i++){
            $arr = [];
            /** @var RealtyObject $item */
            foreach ($complex->getObjects() as $item) {
                if ($item->getSection()->getHousing()->getId() == $housing->getId()) {
                    if ($item->getLevel() == $i) {
                        $arr[] = $item;
                    }
                }
            }
            if (count($arr) > $count) {
                $count = count($arr);
            }
        }

        return new ViewModel([
            'complex' => $complex,
            'image' => $image,
            'other' => $other,
            'housing' => $housing,
            'countApartments' => $count,
        ]);
    }

    //TODO Реализовать метод подгрузки изображений для слайдеров
}
