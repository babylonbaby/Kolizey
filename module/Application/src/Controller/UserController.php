<?php
namespace Application\Controller;

use Application\Entity\User;
use Application\Form\Login as FormNS;
use Application\Form\Login\UserForm;
use Application\Form\Login\PasswordChangeForm;
use Application\Form\Login\PasswordResetForm;
use Application\Grid\Login as GridNS;
use Application\Hydrator\UserHydrator;
use PHPUnit\Framework\Exception;
use Core\EntityManager\EntityManagerTrait;
use Core\InputFilter\GroupsInputFilter;
use Grid\GridAdapterPluginManager\GridAdapterPluginManager;
use Grid\GridAdapterPluginManager\GridAdapterPluginManagerInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Http\Request;
use Application\Entity as EntityNS;
use Core\Exception\NotValidException;
use Zend\Crypt\Password\Bcrypt;


/**
 * This controller is responsible for user management (adding, editing,
 * viewing users and changing user's password).
 */
class UserController extends AbstractController
{
    /**
     * This is the default "index" action of the controller. It displays the
     * list of users.
     */
    public function indexAction()
    {
        $form = $this->fm->get(FormNS\Grid\Form::class, 'users');

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function dataAction()
    {
        $form = $this->fm->get(FormNS\Grid\Form::class, 'users');

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

        /** @var GridAdapterPluginManager $gridPM */
        $gridPM = $this->sm->get(GridAdapterPluginManagerInterface::class);

        /** @var  $gridAdapter */
        $gridAdapter = $gridPM->build(GridNS\Adapter::class, ['form' => $form]);
        $res = $gridAdapter->getData($gridInputData);

        return new JsonModel($res);
    }

    /**
     * Переключатель признака удаления.
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function toggleRemoveAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $this->layout("layout/ajax");
        }
        try {
            if (($id = $this->params('id')) == null) {
                throw new \RuntimeException('Не передан идентификатор пользователя');
            }
            if (($del = $this->params('del')) == null) {
                throw new \RuntimeException('Не передан признак удаления пользователя');
            }
            $em = $this->entityManager;
            /** @var EntityNS\User $objE */
            if (($objE = $em->find(EntityNS\User::class, $id)) == null) {
                throw new \RuntimeException('Пользователь не существует id=' . $id);
            }
            $em->beginTransaction();
            try {
                $objE->setActive(!$del);
                $em->flush();
                $em->commit();
                return new JsonModel([
                    'status' => 0,
                    'msg' => 'Успешно изменено',
                    'data' => !$objE->getActive()
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


    /**
     * This action displays a page allowing to add a new user.
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
            /** @var EntityNS\User $objE */
            $objE =  new EntityNS\User;
            $form = $this->fm->get(FormNS\UserForm::class, ['name' => 'AddUser']);
            $form->bind($objE);
            if ($request->isPost()) {
                $this->entityManager->beginTransaction();
                try {
                    $data = $request->getPost()->toArray();
                    $form->setData($data);
                    $form->validate();
                    $bcrypt = new Bcrypt();
                    $passwordHash = $bcrypt->create($objE->getPassword());
                    $objE->setPassword($passwordHash);
                    $objE->setFIO($objE->getLastName() . ' ' . $objE->getFirstName() . ' ' . $objE->getMiddleName());
                    $this->entityManager->persist($objE);
                    $this->entityManager->flush();
                    $this->entityManager->commit();

                    /** @var \Zend\Http\PhpEnvironment\Response $response */
                    $response = $this->getResponse();
                    $response->setStatusCode(\Zend\Http\PhpEnvironment\Response::STATUS_CODE_202);
                } catch(NotValidException $e) {
                    $this->entityManager->rollback();
//                    $form->setFormMessage('Обнаружены ошибки при проверке данных', 'error');
                } catch (\Exception $e) {
                    $this->entityManager->rollback();
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
     * The "view" action displays a page allowing to view user's details.
     */
    public function viewAction()
    {
        $prepareData = $this->prepareForm();
        $form = $prepareData['form'];
        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * The "edit" action displays a page allowing to edit user.
     */
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
                throw new \Exception('Не передан идентификатор пользователя');
            }
            $em = $this->entityManager;
            /** @var EntityNS\User $objE */
            if (($objE = $em->find(EntityNS\User::class, $id)) == null) {
                throw new \Exception('Объект не существует id='.$id);
            }
            $form = $this->fm->get(FormNS\UserForm::class, ['name' => 'EditUser']);
            $form->get('base')->remove('password');
            $form->bind($objE);
            if ($request->isPost()) {
                $em->beginTransaction();
                try {
                    $data = $request->getPost()->toArray();
                    $form->setData($data);
                    $form->validate();
                    $objE->setFIO($objE->getLastName() . ' ' . $objE->getFirstName() . ' ' . $objE->getMiddleName());
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
     * Подготовка формы пользователя
     * @return array
     * @throws \PHPUnit\Framework\Exception
     */
    private function prepareForm()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            throw new Exception('Не передан обязательный параметр');
        }
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        if (empty($user)) {
            throw new Exception('Пользователь с таким ID не найден');
        }

        $hydrator = $this->sm->get(UserHydrator::class);

        $form = $this->fm->get(FormNS\UserForm::class, 'viewUser');
        $form->getBaseFieldset()->remove('password')->add(
            [
                'name' => 'password',
                'type' => 'hidden',
            ]
        );

        $form->setHydrator($hydrator);
        $form->bind($user);
        return [
            'form' => $form,
            'user' => $user,
        ];
    }

    /**
     * Удаление пользователя
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            throw new Exception('Не передан обязательный параметр');
        }
        /* @var user User*/
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        if (empty($user)) {
            throw new Exception('Пользователь с таким ID не найден');
        }
        if ($user->getActive()->getTitle() == User::STATUS_BLOCK) {
            throw new Exception('Пользователь уже удален');
        }
        $data['active'] = 0;
        $this->userManager->updateUser($user, $data);

    }


//    /**
//     * This action displays a page allowing to change user's password.
//     */
//    /*TODO is it necessary?*/
//    public function changePasswordAction()
//    {
//        $id = (int)$this->params()->fromRoute('id', -1);
//        if ($id < 1) {
//            $this->getResponse()->setStatusCode(404);
//            return;
//        }
//
//        $user = $this->entityManager->getRepository(User::class)
//            ->find($id);
//
//        if ($user == null) {
//            $this->getResponse()->setStatusCode(404);
//            return;
//        }
//
//        // Create "change password" form
//        $form = new PasswordChangeForm('change');
//
//        // Check if user has submitted the form
//        if ($this->getRequest()->isPost()) {
//
//            // Fill in the form with POST data
//            $data = $this->params()->fromPost();
//
//            $form->setData($data);
//
//            // Validate form
//            if ($form->isValid()) {
//
//                // Get filtered and validated data
//                $data = $form->getData();
//
//                // Try to change password.
//                if (!$this->userManager->changePassword($user, $data)) {
//                    $this->flashMessenger()->addErrorMessage(
//                        'Sorry, the old password is incorrect. Could not set the new password.'
//                    );
//                } else {
//                    $this->flashMessenger()->addSuccessMessage(
//                        'Changed the password successfully.'
//                    );
//                }
//
//                // Redirect to "view" page
//                return $this->redirect()->toRoute(
//                    'users',
//                    ['action' => 'view', 'id' => $user->getId()]
//                );
//            }
//        }
//
//        return new ViewModel([
//            'user' => $user,
//            'form' => $form
//        ]);
//    }

    /**
     * This action displays the "Reset Password" page.
     */
    public function resetPasswordAction()
    {
        // Create form
        $form = $this->fm->get(FormNS\PasswordResetForm::class, 'passwordReset');
        $message = '';

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                /*TODO что делать при сбросе?*/
                // Look for the user with such email.
                $user = $this->entityManager->getRepository(User::class)
                    ->findOneByUserName($data['base']['userName']);
                if ($user != null) {
                    $this->userManager->resetPassword($user);
                    $message = 'Пароль успешно сброшен.';
                }
            }
        }
        return new ViewModel([
            'form' => $form,
            'message' => $message,
        ]);
    }

    /**
     * This action displays an informational message page.
     * For example "Your password has been resetted" and so on.
     */
//    public function messageAction()
//    {
//        // Get message ID from route.
//        $id = (string)$this->params()->fromRoute('id');
//
//        // Validate input argument.
//        if($id!='invalid-email' && $id!='sent' && $id!='set' && $id!='failed') {
//            throw new \Exception('Invalid message ID specified');
//        }
//
//        return new ViewModel([
//            'id' => $id
//        ]);
//    }

    /**
     * This action displays the "Reset Password" page.
     */
    public function setPasswordAction()
    {
        // Create form
        $form = $this->fm->get(FormNS\SetPasswordForm::class, 'setPassword');

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {
                $data = $data['base'];
                // Set new password for the user.
                if ($this->userManager->setPassword($data)) {
                    // Redirect to "message" page
                    return $this->redirect()->toRoute('login',
                        ['action'=>'login']);
                }
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }
}


