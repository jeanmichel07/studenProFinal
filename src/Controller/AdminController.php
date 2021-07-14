<?php

namespace App\Controller;

use App\Entity\LineProposition;
use App\Entity\Proposition;
use App\Entity\PublicationStudent;
use App\Repository\PublicationStudentRepository;
use App\Repository\SpecialtyRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\OpenCompteProType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        $pub = $userRepository->findBy(['state'=>2]);
        return $this->render('admin/demande_aide.html.twig', [
            'pub' => $pub
        ]);
    }

    /**
     * @Route("/{id}/ouvrir-compte-pro", name="open_compte_pro")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function ouvrirComptePro(Request $request, User $user, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer):Response
    {
        $form= $this->createForm(OpenCompteProType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $passe_non_crypte=$form->get('password')->getData();
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
     * @param PublicationStudent $publication_student
     * @param UserRepository $studen_Pro
     * @param SpecialtyRepository $specialityPro
     * @return Response
     */
    public function proposerAuEtudiantPro(Request $request, PublicationStudent $publication_student,UserRepository $studen_Pro, SpecialtyRepository $specialityPro): Response
    {
        $id_matiere= $publication_student->getMatiere();
     //   dd($publication_student->getPropositions());
        $studentProSpeciality= $specialityPro->findBy(['matiere'=> $id_matiere]);
        $user=$studentProSpeciality[0]->getUser();

        $userRepo= $studen_Pro->findBy(['id'=>$user]);
        //dd($userRepo);
        if($studentProSpeciality){
            $proposition = new Proposition();
            $proposition->setPublicationStudent($publication_student);
            $propro=$this->getDoctrine()->getManager();
            $propro->persist($proposition);
            $propro->flush();

            $lineProposition= new LineProposition();
            $lineProposition->setProposition($proposition);
            $lineProposition->setUser($userRepo[0]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lineProposition);
            $entityManager->flush();
            //Proposition en attente de validation de l'admin state=0, Proposition envoyer vers l'étudiant Pro => state =1,
            $publication_student->setState(1);
            $em= $this->getDoctrine()->getManager();
            $entityManager->persist($publication_student);
            $entityManager->flush();
            return $this->redirectToRoute('demande_aides');
        }

        return $this->redirectToRoute('demande_aides');
    }
}
