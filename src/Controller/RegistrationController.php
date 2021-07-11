<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Specialty;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\RegistrationStudyProType;
use App\Manager\AppManager;
use App\Repository\MatiereRepository;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    private $manager;

    public function __construct(AppManager $manager)
    {
        $this->manager = $manager;
    }

    private function randomPass($length)
    {
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppAuthenticator $authenticator, MailerInterface $mailer, MatiereRepository $matiereRepository): ?Response
    {
        $user = new User();
        $choice = $this->manager->decrypt($request->get('c'));
        $form = $choice == "simple" ? $this->createForm(RegistrationFormType::class, $user) : $this->createForm(RegistrationStudyProType::class, $user);
        $form->handleRequest($request);
        $matieres = $matiereRepository->findBy(['statu' => 1]);

        if ($form->isSubmitted() && $form->isValid() && $request->request->get('cgu')) {
            $choice == "simple" ? $user->setRoles(["ROLE_STUDENT"]) : $user->setRoles(["ROLE_STUDENT_PRO"]);
            $user->setFirstConnexion(0);
            // Seul l'étudiant professionnel upload un CV
            if ($choice == "pro") {
                $pass = $this->randomPass(8);
                $specialities = $request->request->get('specialities');
                $subjects = [];
                $others = [];
                foreach ($specialities as $speciality) {
                    $sub = $matiereRepository->find($speciality);
                    if ($sub)
                        $subjects[] = $matiereRepository->find($speciality);
                    else
                        $others[] = $speciality;
                }
                foreach ($subjects as $subject) {
                    $spec = new Specialty();
                    $spec->setMatiere($subject);
                    $user->addSpecialty($spec);
                }
                foreach ($others as $other) {
                    $spec = new Specialty();
                    $mat = new Matiere();
                    $mat->setStatu(0)
                        ->setNom($other);
                    $spec->setMatiere($mat);
                    $user->addSpecialty($spec);
                }
                $user->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $pass
                ));
                $file_cv = $user->getFileCv();
                $nom_cv = md5(uniqid()) . '.' . $file_cv->guessExtension();
                try {
                    $file_cv->move($this->getParameter('upload_directory'), $nom_cv);
                    $user->setFileCv($nom_cv);
                } catch (FileException $e) {

                }
            }
            // Seul l'étudiant/élève entre le mot de passe pendant l'inscription
            if ($choice !== "pro") {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

            }

            //Traitement du photo de profil
            $profile = $user->getPictureStudent();
            if (!is_null($profile)) {
                $picture_student = md5(uniqid()) . '.' . $profile->guessExtension();
                try {
                    $profile->move($this->getParameter('profil_directory'), $picture_student);
                    $user->setPictureStudent($picture_student);
                } catch (FileException $e) {

                }
            }

            $user->setIsVerified(false);
            //On met true firstconnexion dès l'inscription
            $user->setFirstConnexion(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $role = $user->getRoles();
            $nom_pro = $user->getName();
            $prenom_pro = $user->getFirstname();
            $contact = $user->getNumber();
            $mail_inscrit = $user->getEMail();
            $firsconnexion = $user->getFirstConnexion();
            if ($role[0] == "ROLE_STUDENT") {
                $url = $this->generateUrl('actived_compte', ['em' => $this->manager->encrypt($mail_inscrit), 'role' => $this->manager->encrypt($role[0]), 'firs' => $this->manager->encrypt($firsconnexion)], UrlGeneratorInterface::ABSOLUTE_URL);
                $mail = (new Email())
                    ->from('tombokely@gmail.com')
                    ->to($mail_inscrit)
                    ->subject('Confirmation création compte étudiant/élève')
                    ->html($this->renderView('emails/confirmation_compte_simple.html.twig', [
                        'url' => $url
                    ]));
                $mailer->send($mail);

                $this->addFlash('success', 'Un email a été envoyer à l\'adresse ' . $mail_inscrit . '. Verifier dans spam si le mail n\'apparaisse pas dans votre boîte de reception. Merci');

                return $this->redirectToRoute('choices');
                //return $this->renderView('home/choices.html.twig');
            } else {
                $mail = (new Email())
                    ->from('tombokely@gmail.com')
                    ->to($mail_inscrit)
                    ->subject('Traitement de candidature')
                    ->html($this->renderView('emails/inscription_compte_pro.html.twig', [
                        'name' => $nom_pro,
                        'firstname' => $prenom_pro,
                        'contact' => $contact
                    ]));
                $mailer->send($mail);

                $this->addFlash('success', 'Un email a été envoyer à l\'adresse ' . $mail_inscrit . '. Verifier dans spam si le mail n\'apparaisse pas dans votre boîte de reception. Merci');
                return $this->redirectToRoute('choices');
                //return $this->renderView('home/choices.html.twig');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'choice' => $choice,
            'matieres' => $matieres,

        ]);
    }

    /**
     * @Route("/activation-compte", name="actived_compte")
     */
    public function activateCompte(Request $request, UserRepository $userRepository, GuardAuthenticatorHandler $guardHandler, AppAuthenticator $authenticator)
    {
        $email = $this->manager->decrypt($_GET['em']);
        $role = $this->manager->decrypt($_GET['role']);
        $firstconex = $this->manager->decrypt($_GET['firs']);
        $user = $userRepository->findOneBy(['email' => $email]);
        if (!is_null($user)) {
            if ($role === "ROLE_STUDENT") {
                $user->setIsVerified(true);
                if ($firstconex) {
                    $this->addFlash('success', 'Bienvenue sur StudenPro, votre compte a été bien activé. Merci');
                    $user->setFirstConnexion(false);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main'
                );
            } else {
                $user->setIsVerified(true);
                if ($firstconex === true) {
                    $this->addFlash('success', 'Bienvenue sur StudenPro, votre compte a été bien activé. Merci');
                    $user->setFirstConnexion(false);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main'
                );
            }

        }

        return $this->redirectToRoute('app_logout');

    }
}
