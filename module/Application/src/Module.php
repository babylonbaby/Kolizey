<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Service\UserManager;
use Application\Exception\PermissionDeniedException as Exception;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Controller\LoginController;
use Application\Service\AuthManager;


class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        // Следующая строка инстанцирует SessionManager и автоматически
        // делает его выбираемым 'по умолчанию'.
        $sessionManager = $serviceManager->get(SessionManager::class);

        // Get event manager.
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method.
        $sharedEventManager->attach(AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }

    /**
     * Event listener method for the 'Dispatch' event. We listen to the Dispatch
     * event to call the access filter. The access filter allows to determine if
     * the current visitor is allowed to see the page or not. If he/she
     * is not authorized and is not allowed to see the page, we redirect the user
     * to the login page.
     */
    public function onDispatch(MvcEvent $event)
    {
        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Convert dash-style action name to camel-case.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        // Get the instance of AuthManager service.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);
        $userManager = $event->getApplication()->getServiceManager()->get(UserManager::class);

        // Execute the access filter on every controller except AuthController
        // (to avoid infinite redirect).
        if ($controllerName!=LoginController::class &&
            !$authManager->filterAccess($controllerName, $actionName)) {

            // Remember the URL of the page the user tried to access. We will
            // redirect the user to that URL after successful login.
            $uri = $event->getApplication()->getRequest()->getUri();
            // Make the URL relative (remove scheme, user info, host name and port)
            // to avoid redirecting to other domain by a malicious user.
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();
            $redirectUrl = base64_encode($redirectUrl);

            // Redirect the user to the "Login" page.
            return $controller->redirect()->toRoute('login', [],
                ['query'=>['redirectUrl'=>$redirectUrl]]);
        }
        $config = $this->getConfig();
        $controllerPermission = null;
        if (isset($config['permissions'][$controllerName])) {
            $controllerPermission = $config['permissions'][$controllerName];
        } else {
            return true;
        }
        $actionPermission = null;
        if (isset($controllerPermission[$actionName])) {
            $actionPermission = $controllerPermission[$actionName];
        }
        if (!isset($controllerPermission['roles']) &&
            !isset($controllerPermission['permissions']) &&
            empty($actionPermission)
        ) {
            return true;
        }
        /* @var $activeUser Entity\User*/
        $activeUser = $userManager->getActiveUser();
        if (empty($activeUser)) {
            throw new Exception('Не достаточно прав', 403);
        }
        if (isset($controllerPermission['roles']) && empty($actionPermission)) {
            foreach ($controllerPermission['roles'] as $role) {
                if ($activeUser->hasRole($role)) {
                    return true;
                }
            }
        }
        if (isset($controllerPermission['permissions']) && empty($actionPermission)) {
            foreach ($controllerPermission['permissions'] as $permission) {
                if ($activeUser->hasPermission($permission)) {
                    return true;
                }
            }
        }
        if (isset($actionPermission['roles'])) {
            foreach ($actionPermission['roles'] as $role) {
                if ($activeUser->hasRole($role)) {
                    return true;
                }
            }
        }
        if (isset($actionPermission['permissions'])) {
            foreach ($actionPermission['permissions'] as $permission) {
                if ($activeUser->hasPermission($permission)) {
                    return true;
                }
            }
        }
        throw new Exception('Не достаточно прав', 403);
    }

}
