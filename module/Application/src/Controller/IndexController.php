<?php
namespace Application\Controller;

use Application\Entity\Feedback;
use Application\Entity\RealtyCategories;
use Application\Entity\RealtyObject;
use Application\Entity\RealtyType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractController
{
    /**
     * Главная страница
     */
    public function indexAction()
    {
        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 1]);
        /** @var RealtyCategories $category */
        $category = $this->entityManager->getRepository(RealtyCategories::class)->findOneBy(['name' => 'Спецпредложение']);
        $specialItems = $this->entityManager->getRepository(RealtyObject::class)->findBy(['realtyType' => $type, 'publish' => true]);
        $counter = 0;
        foreach ($specialItems as $key => $item) {
            if (!$item->hasCategory($category) || $counter >= 9) {
                unset($specialItems[$key]);
            } else {
                $counter++;
            }
        }

        /** @var RealtyType $type */
        $type = $this->entityManager->getRepository(RealtyType::class)->findOneBy(['id' => 2]);
        $items = $this->entityManager->getRepository(RealtyObject::class)->findBy(['realtyType' => $type, 'publish' => true]);
        $counter = 0;
        foreach ($items as $key => $item) {
            if ($counter >= 9) {
                unset($items[$key]);
            } else {
                $counter++;
            }
        }

        return new ViewModel([
                'category' => $category,
                'special' => $specialItems,
                'commerce' => $items

            ]
        );
    }

    /**
     * О компании
     */
    public function aboutAction()
    {

    }

    /**
     * Ипотека
     */
    public function mortgageAction()
    {

    }

    /**
     * Отзывы
     */
    public function feedbackAction()
    {
//        $date = new \DateTime();
//        $time = substr(microtime(true), -4);
//        echo $date->format('dmY-H:i:s.') . $time;


        $request = $this->getRequest();
        $page = $this->params('page');
        if (empty($page)) {
            $page = 1;
        }
        $items = $this->entityManager->getRepository(Feedback::class)->findBy(['publish' => true]);
//        dd('!');
        $count = count($items);
        $collection = new ArrayCollection();
        foreach ($items as $item) {
            $collection->add($item);
        }
        $criteria = Criteria::create()
            ->setFirstResult(($page - 1) * 5);

        $items = $collection->matching($criteria);
        $lastPage = false;
        if (count($items) <= 5) {
            $lastPage = true;
        }
        $criteria->orderBy(array("id" => Criteria::DESC));
        $criteria->setMaxResults(5);
        $items = $collection->matching($criteria);

        if ($request->isPost()) {
            $data = json_decode($request->getPost()['data']);
            $feedback = new Feedback();
            $feedback->setName($data[0]);
            $feedback->setEmail($data[1]);
            $feedback->setCity($data[2]);
            $feedback->setText($data[3]);
            $this->entityManager->persist($feedback);
            $this->entityManager->flush();
            /** @var \Zend\Http\PhpEnvironment\Response $response */
            $response = $this->getResponse();
            $response->setStatusCode(\Zend\Http\PhpEnvironment\Response::STATUS_CODE_202);
            return new JsonModel([
                'status' => 0
            ]);
        }
        return new ViewModel([
            'items' => $items,
            'page' => $page,
            'count' => $count,
            'lastPage' => $lastPage,
        ]);

    }
    /**
     * Заявки
     */
    public function orderAction()
    {
//        $date = new \DateTime();
//        $time = substr(microtime(true), -4);
//        echo $date->format('dmY-H:i:s.') . $time;


        $request = $this->getRequest();
//        $page = $this->params('page');
//        if (empty($page)) {
//            $page = 1;
//        }
//        $items = $this->entityManager->getRepository(Feedback::class)->findBy(['publish' => true]);
////        dd('!');
//        $count = count($items);
//        $collection = new ArrayCollection();
//        foreach ($items as $item) {
//            $collection->add($item);
//        }
//        $criteria = Criteria::create()
//            ->setFirstResult(($page - 1) * 5);
//
//        $items = $collection->matching($criteria);
//        $lastPage = false;
//        if (count($items) <= 5) {
//            $lastPage = true;
//        }
//        $criteria->orderBy(array("id" => Criteria::DESC));
//        $criteria->setMaxResults(5);
//        $items = $collection->matching($criteria);
//
        if ($request->isPost()) {
            $data = json_decode($request->getPost()['data']);
//            $feedback = new Feedback();
//            $feedback->setName($data[0]);
//            $feedback->setEmail($data[1]);
//            $feedback->setCity($data[2]);
//            $feedback->setText($data[3]);
//            $this->entityManager->persist($feedback);
//            $this->entityManager->flush();
            /** @var \Zend\Http\PhpEnvironment\Response $response */
            $response = $this->getResponse();
            $response->setStatusCode(\Zend\Http\PhpEnvironment\Response::STATUS_CODE_202);
            return new JsonModel([
                'status' => 0
            ]);
        }
    }
}
