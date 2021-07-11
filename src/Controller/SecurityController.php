<?php

namespace App\Controller;

use App\Form\PasswordsType;
use App\Manager\AppManager;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $manager;
    public function __construct(AppManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @param Request $request
     * @Route("/password-forgot", name="passworg_forgot")
     * @return Response
     */
    public function passwordForgot(Request $request, UserRepository  $userRepository, MailerInterface $mailer): Response
    {
        if(isset($_POST['email'])){
            $user = $userRepository->findOneBy(['email'=>$_POST['email']]);
            $url = $this->generateUrl('reset_password', ['em' => $this->manager->encrypt($_POST['email'])], UrlGeneratorInterface::ABSOLUTE_URL);
            if($user != null){
                $email = (new Email())
                    ->from('tombokely@gmail.com')
                    ->to($_POST['email'])
                    ->subject('Réinitialisation mot de passe')
                    ->html($this->renderView('emails\passoublie.html.twig',[
                        'url' => $url,
                        'user' => $user
                    ]));
                $mailer->send($email);
                $this->addFlash('success','Un email est envoyé à l\' adresse '.$_POST['email']);

            }else{
                $this->addFlash('error','L\'adresse '.$_POST['email'].' n\'existe pas.');
            }
        }
        return $this->render('security/password_forgot.html.twig');
    }

    /**
     * @Route("/reset-password", name="reset_password")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     */
    public function resetPassword(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder){
        $email = $this->manager->decrypt($_GET['em']);
        $user = $userRepository->findOneBy(['email'=> $email]);
        $form = $this->createForm(PasswordsType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $user->setPassword($encoder->encodePassword($user, $request->get('passwords')['password']['first']));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/reset_password.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
