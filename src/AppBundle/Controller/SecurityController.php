<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\LoginFormType;
use AppBundle\Form\RegisterFormType;
use AppBundle\Services\Security\AuthenticationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction()
    {
        $paramForm = $this->get(AuthenticationService::class)->login();



        return $this->render('security/index.html.twig', [
            'title_page' => 'Connexion',
            'error' => $paramForm['errorLogin'],
            'lastUsername' => $paramForm['lastUsername'] ? $paramForm['errorLogin'] : null,
            'form' => $this->createForm(LoginFormType::class)->createView()
        ]);
    }
    /**
     * @Route("/register", name="security_register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }


        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
