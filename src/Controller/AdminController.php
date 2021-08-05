<?php

namespace App\Controller;

use App\Entity\LineProposition;
use App\Entity\Proposition;
use App\Entity\PublicationStudent;
use App\Entity\User;
use App\Form\OpenCompteProType;
use App\Repository\PublicationStudentRepository;
use App\Repository\SpecialtyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/liste/etudiant-simple", name="list_students")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function listStudents(UserRepository $userRepository): Response
    {
        $students = $userRepository->getUserByRoles('ROLE_STUDENT');
        return $this->render('admin/list_student.html.twig', [
            'students' => $students
        ]);
    }

    /**
     * @Route("/liste/etudiant-spécialisé", name="list_studen_pro")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function listStudentsPro(UserRepository $userRepository): Response
    {
        $students = $userRepository->getUserByRoles('ROLE_STUDENT_PRO');
        return $this->render('admin/list_student_pro.html.twig', [
            'students' => $students
        ]);
    }

    /**
     * @Route("/demandes-entretien", name="list_meeting_request")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function listMeetingRequest(UserRepository $userRepository): Response
    {
        $students = $userRepository->getUserByRolesAndNotActive('ROLE_STUDENT_PRO');
        return $this->render('admin/list_meeting_request.html.twig', [
            'students' => $students
        ]);
    }

    /**
     * @Route("/demandes-aides", name="demande_aides")
     * @param PublicationStudentRepository $userRepository
     * @return Response
     */
    public function demandeAides(PublicationStudentRepository $userRepository): Response
    {
        $pub = $userRepository->findAll();
        //dd($pub);
        return $this->render('admin/demande_aide.html.twig', [
            'pub' => $pub
        ]);
    }

    /**
     * @Route("/{id}/ouvrir-compte-pro", name="open_compte_pro")
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function ouvrirComptePro(Request $request, User $user, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $form = $this->createForm(OpenCompteProType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $passe_non_crypte = $form->get('password')->getData();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setIsVerified(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            $mail_inscrit = $user->getEMail();
            $mail = (new Email())
                ->from('tombokely@gmail.com')
                ->to($mail_inscrit)
                ->subject('Ouverture compte pro')
                ->html($this->renderView('emails/confirmation_compte_pro.html.twig', [
                    'mail' => $mail_inscrit,
                    'password' => $passe_non_crypte,
                ]));
            $mailer->send($mail);

            $this->addFlash('success', 'Ouverture du compte pro réussi. Un email a été envoyer à l\'adresse ' . $mail_inscrit);
            return $this->redirectToRoute('list_meeting_request');
        }
        return $this->render('admin/open_compte_student_pro.html.twig', [
            'openCompteProForm' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/{id}/proposition-envoyer", name="proposition_subject_pro")
     * @param Request $request
     * @param PublicationStudent $publication_student
     * @return Response
     */
    public function proposerAuEtudiantPro(Request $request, PublicationStudent $publication_student): Response
    {
        //dd($userRepo);
        if ($publication_student !== null) {
            $entityManager = $this->getDoctrine()->getManager();
            //Proposition en attente de validation de l'admin state=0, Proposition envoyer vers l'étudiant Pro => state =1,
            $publication_student->setState(4);
            $entityManager->persist($publication_student);
            $entityManager->flush();
            return $this->redirectToRoute('demande_aides');
        }

        return $this->redirectToRoute('demande_aides');
    }
}
