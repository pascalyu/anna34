<?php

namespace AppBundle\Services\Security;

use AppBundle\Entity\User;
use AppBundle\Form\LoginFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    /** @var RouterInterface */
    private $router_interface;
    /** @var EntityManagerInterface */
    private $entity_manager;
    /** @var UserPasswordEncoderInterface */
    private $password_encoder;
    /** @var FormFactoryInterface */
    private $form_factory_interface;

    public function __construct(
        RouterInterface $router_interface,
        EntityManagerInterface $entity_manager,
        UserPasswordEncoderInterface $password_encoder,
        FormFactoryInterface $form_factory_interface
    ) {
        $this->router_interface = $router_interface;
        $this->entity_manager = $entity_manager;
        $this->password_encoder = $password_encoder;
        $this->form_factory_interface = $form_factory_interface;
    }

    public function getLoginUrl()
    {

        return $this->router_interface->generate('security_login');
    }

    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');

        if (!$isLoginSubmit) {
            return;
        }
        $form = $this->form_factory_interface->create(LoginFormType::class);
        $form->handleRequest($request);
        $data = $form->getData();
        $request->getSession()->set(Security::LAST_USERNAME, $data['_name']);
        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $name = $credentials['_name'];
        $user = $this->entity_manager->getRepository(User::class)->findOneBy(['name' => $name]);
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];
        if ($this->password_encoder->isPasswordValid($user, $password, $user->getSalt())) {
            return true;
        }
        return false;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

        return new RedirectResponse($this->router_interface->generate('homepage'));
    }
    public function onAuthenticationFailure(\Symfony\Component\HttpFoundation\Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $exception)
    {
        return new RedirectResponse($this->router_interface->generate('security_register'));
    }
}
