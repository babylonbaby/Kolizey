<?php
namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Application\Service\UserManager;
use Application\Service\AuthManager;
use Core\Factory\LazyControllerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class AbstractController extends AbstractActionController implements LazyControllerInterface
{
    /**
     * Auth service.
     * @var \Zend\Authentication\AuthenticationService
     */
    protected $authService;

    /**
     * @var ContainerInterface
     */
    protected $sm;

    /**
     * User manager.
     * @var \Application\Service\UserManager
     */
    protected $userManager;

    /**
     * @param MvcEvent $e
     * @return mixed|void
     * @throws \Exception
     */

    /**
     * Entity manager.
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Entity manager.
     * @var \Application\Service\AuthManager
     */
    protected $authManager;

    /**
     * формэлемент менеджер
     * @var   \Zend\ServiceManager\PluginManagerInterface
     */
    protected $fm;

    public function __construct(
        ContainerInterface $container,
        UserManager $userManager,
        AuthManager $authManager,
        AuthenticationService $authService,
        EntityManager $entityManager
    ) {
        $this->authManager = $authManager;
        $this->authService = $authService;
        $this->userManager = $userManager;
        $this->sm = $container;
        $this->entityManager = $entityManager;
        $this->fm = $this->sm->get('FormElementManager');
    }
}
 