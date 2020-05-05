<?php

namespace AppBundle\Services\Security;

use AppBundle\Form\LoginFormType;

use Symfony\Component\Form\FormFactoryInterface;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationService
{
    private $formFactoryInterface;
    private $router;
    /**@var AuthenticationUtils */
    private $authenticationUtils;

    public function __construct(FormFactoryInterface $formFactoryInterface, RouterInterface $router, AuthenticationUtils $authenticationUtils)
    {
        $this->formFactoryInterface = $formFactoryInterface;
        $this->router = $router;
        $this->authenticationUtils = $authenticationUtils;
    }

    public function login()
    {
        $paramForm = [];

        $errorLogin = $this->authenticationUtils->getLastAuthenticationError();
        $lastUserName = $this->authenticationUtils->getLastUsername();
        $form = $this->formFactoryInterface->create(LoginFormType::class, [
            '_name' => $lastUserName,
        ]);
        if (null !== $errorLogin) {
            $errorLogin = "Mauvais identifiants";
        }
        $paramForm['form'] = $form;
        $paramForm['lastUsername'] = $lastUserName;
        $paramForm['errorLogin'] = $errorLogin;
        return $paramForm;
    }
}
