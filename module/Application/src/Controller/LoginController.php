<?php

namespace Application\Controller;

use Application\Form\Login\LoginForm;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use Zend\Uri\Uri;

/**
 * This controller is responsible for letting the user to log in and log out.
 */
class LoginController extends AbstractController
{
    /**
     * Authenticates user given userName address and password credentials.
     */
    public function loginAction()
    {
        if ($this->authService->getIdentity() != null) {
            throw new \Exception('Вы уже авторизованы!');
        }

        // Retrieve the redirect URL (if passed). We will redirect the user to this
        // URL after successfull login.
        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        // Check if we do not have users in database at all. If so, create
        // the 'Admin' user.
        $this->userManager->createAdminUserIfNotExists();

        // Create login form
        $form = $this->fm->get(
            LoginForm::class,
            [
                'name' => 'Login',
            ]
        );
        $form->getBaseFieldset()->get('redirect_url')->setValue($redirectUrl);

        // Store login status.
        $isLoginError = false;

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $form->setData(['base' => $data]);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();
                $data = $data['base'];

                // Perform login attempt.
                $result = $this->authManager->login(
                    $data['userName'],
                    $data['password'],
                    $data['remember_me']
                );

                // Check result.
                if ($result->getCode() == Result::SUCCESS) {

                    // Get redirect URL.
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');
                    $redirectUrl = base64_decode($redirectUrl);

                    if (!empty($redirectUrl)) {
                        // The below check is to prevent possible redirect attack 
                        // (if someone tries to redirect user to another domain).
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() != null) {
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                        }
                    }

                    // If redirect URL is provided, redirect the user to that URL;
                    // otherwise redirect to Home page.
                    if (empty($redirectUrl)) {
                        // @todo: Редирект зависит от роли пользователя.
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);
    }

    /**
     * The "logout" action performs logout operation.
     */
    public function logoutAction()
    {
        // Allow to log out only when user is logged in.
        if ($this->authService->getIdentity() == null) {
            throw new \Exception('Вы не авторизованы.');
        }

        $this->authManager->logout();

        return $this->redirect()->toRoute('login');
    }
}
