<?php

namespace App\Controller;

use App\Entity\PublicationStudent;
use App\Form\PublicationStudentType;
use App\Repository\MatiereRepository;
use App\Repository\PropositionRepository;
use App\Repository\PublicationStudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    private $manager;
    private $matiereRepository;

    public function __construct(EntityManagerInterface $manager, MatiereRepository $matiereRepository)
    {
        $this->manager = $manager;
        $this->matiereRepository = $matiereRepository;
    }

    /**
     * @Route("/student", name="student")
     */
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Route("/add-publication", name="newpub")
     */
    public function newPublication(Request $request): Response
    {
        $pub = new PublicationStudent();
        $form = $this->createForm(PublicationStudentType::class, $pub);
        $form->handleRequest($request);
        $matieres = $this->matiereRepository->findBy(['statu' => true]);
        if ($form->isSubmitted() and $form->isValid()) {
            if ($_FILES['file'] != null and $_FILES['file']['error'] == 0) {
                $matiere = $this->matiereRepository->findOneBy(['nom' => $request->get('matiere')]);
                if (null != $matiere) {
                    $pub->setMatiere($matiere);
                }
                $file = pathinfo($_FILES['file']['name']);
                $extensionProfil = $file['extension'];
                $extensions_autorisees = array('pdf', 'doc', 'docx', 'PDF', 'DOC', 'DOCX');
                if (in_array($extensionProfil, $extensions_autorisees)) {
                    $nameFileProfil = $file['filename'];

                    $name = md5($nameFileProfil);
                    move_uploaded_file($_FILES['file']['tmp_name'], 'pub_student/' . $name . '.' . $extensionProfil);
                    $pub->setFile($name . '.' . $extensionProfil);
                }

            }
            $orgDate = $request->get('publication_student_availability');
            $datetime = explode(' ', $orgDate);
            $date = explode('/', $datetime[0]);
            $time = $datetime[1];
            $newDatetime = "$date[2]-$date[1]-$date[0] $time";
            $pub->setState(0);
            $pub->setStudent($this->getUser());
            $pub->setAvailability(new \DateTime($newDatetime));
            $this->manager->persist($pub);

            $this->manager->flush();
            $this->addFlash('success', 'Votre demande a été envoyé ! ');
        }
        return $this->render('student/manage_publication.html.twig', [
            'matiere' => $matieres,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sujet/en-cours", name="subject_in_progress")
     * @param Request $request
     * @param PublicationStudentRepository $publicationStudentRepository
     * @return Response
     */
    public function subjectInProgress(Request $request, PublicationStudentRepository $publicationStudentRepository): Response
    {
        $subjects = $publicationStudentRepository->findBy(['student' => $this->getUser()]);
        return $this->render('student/subject_in_progress.html.twig', [
            'subjects' => $subjects
        ]);
    }

    /**
     * @Route("/sujet/traite", name="subject_treated")
     * @param Request $request
     * @param PublicationStudentRepository $publicationStudentRepository
     * @return Response
     */
    public function subjectTreated(Request $request, PublicationStudentRepository $publicationStudentRepository): Response
    {
        $subjects = $publicationStudentRepository->findBy(['student' => $this->getUser(), 'state' => 3]);
        return $this->render('student/subject_treated.html.twig', [
            'subjects' => $subjects
        ]);
    }

    /**
     * @Route("/proposition/{id}", name="proposition")
     * @param PublicationStudent $publicationStudent
     * @param Request $request
     * @param PropositionRepository $repository
     * @return Response
     */
    public function proposition(PublicationStudent $publicationStudent,Request $request, PropositionRepository $repository): Response
    {
       // $proposition = $repository->findBy(['PublicationStudent'=> $publicationStudent]);
        $proposition = $repository->propositionWithPrestation();

     //   dd($proposition[0]->getLinePropositions()[0]->getPrestations());
        return $this->render('student/proposition.html.twig',[
            'proposition' => $proposition[0]->getLinePropositions()[0]->getPrestations() != null ? $proposition[0]->getLinePropositions() : []
        ]);
    }
}
