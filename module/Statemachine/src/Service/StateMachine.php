<?php
/**
 * Created by PhpStorm.
 * User: kota
 * Date: 16.09.16
 * Time: 12:47
 */
namespace StateMachine\Service;

use StateMachine\Entity as EntityNS;
//use Doctrine\Common\Collections\ArrayCollection;
use Core\EntityManager\EntityManagerTrait;
use Doctrine\ORM\EntityManager as EntityManager;
use StateMachine\Exception as ExceptionNS;
//use StateMachine\Condition\ConditionProviderInterface;
//use StateMachine\Condition as ConditionNS;
use StateMachine\Functor as FunctorNS;
use Zend\Validator\ValidatorInterface;
//use StateMachine\Condition\ConditionPluginManager;
//use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\ValidatorPluginManager;

class StateMachine
{
    use EntityManagerTrait;

    /**
     * имя ентити таблицы переходов
     * @var string
     */
    protected $transitionAClassName = null;
    /**
     * имя ентити таблицы переходов
     * @var string
     */
    protected $transitionBClassName = null;

    /**
     * имя словаря-ентити действий
     * @var string
     */
    protected $actionDictionary = '\StateMachine\Entity\DictAction';

    /**
     * имя атрибута, в котором хранится состояние, для него должны существовать геттер и сеттер
     * @var string
     */
    protected $stateAttribute = null;

    //======================

    /**
     * репозитарий ентити-таблицы_A переходов
     * @var \Doctrine\ORM\EntityRepository
     */
    private $transitionARepository = null;
    /**
     * репозитарий ентити-таблицы_B переходов
     * @var \Doctrine\ORM\EntityRepository
     */
    private $transitionBRepository = null;

    /**
     * @var ValidatorPluginManager
     */
    protected $validatorPM;

    /**
     * @var FunctorNS\FunctorPluginManager
     */
    protected $functorPM;

    //=====================
    public function __construct(
        EntityManager $em,
        ValidatorPluginManager $validatorPM,
        FunctorNS\FunctorPluginManager $functorPM
    ) {
        $this->setEntityManager($em);
        $this->validatorPM = $validatorPM;
        $this->functorPM = $functorPM;
    }

    /**
     * возвращает список доступных действий для заданного состояния
     * @param int $state
     * @return array
     */
    public function getActionsForState($state)
    {
        $ret = [];
        $transitionList = $this->getTransitionsForState($state);
        /** @var EntityNS\TransitionAInterface $transitionE */
        foreach ($transitionList as $transitionE) {
            $actionE = $transitionE->getAction();
            $ret[$actionE->getId()] = $actionE;
        }
        return $ret;
    }

    /**
     * возвращает список доступных действий для данной ентити (проверяются условия доступности действия)
     * @param object $objE
     * @return array
     */
    public function getActions($objE)
    {
        $ret = [];
        $stateE = $this->getObjectState($objE);
        $transitionList = $this->getTransitionsForState($stateE->getId());
        /** @var EntityNS\TransitionAInterface $transitionE */
        foreach($transitionList as $transitionE) {
            $condition = $transitionE->getCondition();
            if ($condition == null || $this->checkActionCondition($condition, $objE)) {
                $actionE = $transitionE->getAction();
                $ret[$actionE->getId()] = $actionE;
            }
        }
        return $ret;
    }

    /**
     * проверяет доступность действия для объекта
     * @param object $objE
     * @param string $action
     * @return bool
     */
    public function hasAction($objE, $action)
    {
        if (($actionE = $this->getActionEntity($action)) == null) {
            return false;
        }
        $list = $this->getActions($objE);
        /** @var object $actE */
        foreach($list as $actE) {
            if ($actE->getId() == $actionE->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Выполняет действие стейтмашины над ентити
     * @param object $objE
     * @param string $action
     * @param array $data
     * @return array
     */
    public function doAction($objE, $action, array $data = [])
    {
        $stateE = $this->getObjectState($objE);
        if (($actionE = $this->getActionEntity($action)) == null) {
            throw new ExceptionNS\ActionNotAllowed('Действие '.$action.' не существует');
        }
        /** @var EntityNS\TransitionAInterface $transitionE */
        $transitionE = $this->getTransition($stateE->getId(), $actionE->getId());
        if ($transitionE == null) {
            throw new ExceptionNS\InvalidStateAction('Действие '.$action.' недоступно или неопределено для '
                .get_class($objE) . '(id='.$objE->getId(). ')'
            );
        }
        if ($this->checkActionCondition($transitionE->getCondition(), $objE) == false) {
            throw new ExceptionNS\ActionNotAllowed('Действие '.$action.' не разрешено для '
                .get_class($objE) . '(id='.$objE->getId(). ')'
            );
        }

        if (($transitionBE = $this->getTransitionB($transitionE, $objE, $data)) == null) {
            //пустое действие не предполагающее перехода и функторов.
            return $data;
        }

        $data['_PREFUNCTOR_'] = $this->doFunctor($transitionBE->getPreFunctor(), $objE, $data);
        $this->setObjectState($objE, $transitionBE->getDst());
        $data['_POSTFUNCTOR_'] = $this->doFunctor($transitionBE->getPostFunctor(), $objE, $data);

        $em = $this->getEntityManager();
        $em->persist($objE);
        return $data;
    }

    /**
     * возвращает ентити действия по ее уникальному имени в пределах стейтмашины
     * действия лучше иметь в одной таблице с дискриминатором, если нет - переопределить при наследовании
     * @param $action
     * @return object
     */
    protected function getActionEntity($action)
    {
        $repo = $this->getEntityManager()->getRepository($this->actionDictionary);
        return $repo->findOneBy(['code' => $action]);
    }

    /**
     * достает В-часть перехода, условия проверяются
     * @param EntityNS\TransitionAInterface $transitionE
     * @param object $objE
     * @param array $data
     * @return EntityNS\TransitionBInterface | null
     */
    protected function getTransitionB(EntityNS\TransitionAInterface $transitionE, $objE, $data=[])
    {
        $repo = $this->getTransitionBRepository();
        $list = $repo->findBy(
            ['transitionA' => $transitionE->getId()],
            ['weight' => 'DESC']
        );
        if (count($list) == 0) {
            return null;
        }
        /** @var EntityNS\TransitionBInterface $transitionBE */
        foreach ($list as $transitionBE) {
            $condition = $transitionBE->getCondition();
            if($this->checkActionCondition($condition, $objE, $data)) {
                return $transitionBE;
            }
        }

        throw new ExceptionNS\InvalidStateAction('Ошибка конфигурации перехода class='
            . get_class($transitionE)
            . ' id=' . $transitionE->getId() . ' не имеет перехода по умолчанию, объект= '
                .get_class($objE) . '(id='.$objE->getId(). ')');
    }

    /**
     * возвращает список доступных переходов для заданного состояния
     * @param int $state
     * @return array
     */
    protected function getTransitionsForState($state)
    {
        $repo = $this->getTransitionARepository();
        $res = $repo->findBy(['src' => $state]);
        return $res;
    }

    /**
     * @param string $state
     * @param string $actionId идентификатор ентити-действия
     * @return EntityNS\TransitionAInterface
     */
    protected function getTransition($state, $actionId)
    {
        $repo = $this->getTransitionARepository();
        $res = $repo->findOneBy(['src' => $state, 'action' => $actionId]);
        return $res;
    }

    /**
     * Возвращает state ентити у объекта
     * @param object $objE
     * @return object
     */
    protected function getObjectState($objE)
    {
        $getStateMethod = 'get' . ucfirst($this->stateAttribute);
        if (method_exists($objE, $getStateMethod) == false) {
            throw new ExceptionNS\InvalidStateGetter('отсутсвует метод '
                . $getStateMethod . ' у класса ' . get_class($objE));
        }
        $stateE = $objE->$getStateMethod();
        return $stateE;
    }

    /**
     * устанавливает состояние у объекта
     * @param object $objE
     * @param object $stateE
     * @return object
     */
    protected function setObjectState($objE, $stateE)
    {
        $setStateMethod = 'set' . ucfirst($this->stateAttribute);
        if (method_exists($objE, $setStateMethod) == false) {
            throw new ExceptionNS\InvalidStateGetter('отсутсвует метод '
                . $setStateMethod . ' у класса ' . get_class($objE));
        }
        $objE->$setStateMethod($stateE);
        return $objE;
    }

    /**
     * проверяет выполнения условия для действия над данным объектом
     * @param string $condition
     * @param object $objE
     * @param array $data
     * @return bool
     */
    protected function checkActionCondition($condition, $objE, $data=[])
    {
        if ($condition == '') {
            return true;
        }
        /** @var ValidatorInterface $validator */
        $validator = $this->validatorPM->get($condition);
        $ret = $validator->isValid($objE, $data);
        return $ret;
    }

    /**
     * возвращает репозитарий таблицы переходов
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getTransitionARepository()
    {
        if ($this->transitionARepository == null) {
            $em = $this->getEntityManager();
            $this->transitionARepository = $em->getRepository($this->transitionAClassName);
        }
        return $this->transitionARepository;
    }

    /**
     * возвращает репозитарий таблицы переходов
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getTransitionBRepository()
    {
        if ($this->transitionBRepository == null) {
            $em = $this->getEntityManager();
            $this->transitionBRepository = $em->getRepository($this->transitionBClassName);
        }
        return $this->transitionBRepository;
    }

    /**
     * Выполняет функтор с заданным именем, функтор может менять данные $data
     * @param $functorName
     * @param object $objE
     * @param array &$data
     * @return null|mixed
     */
    protected function doFunctor($functorName, $objE, array &$data)
    {
        if ($functorName == null) {
            return null;
        }
        /** @var FunctorNS\FunctorInterface $functor */
        $functor = $this->functorPM->get($functorName);
        return $functor($objE, $data);
    }
} 