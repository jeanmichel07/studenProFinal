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
        $tabNomFichier="";
        if ($form->isSubmitted() and $form->isValid()) {
            $file = $_FILES['file'];
            $extensions_autorisees = array('pdf', 'doc', 'docx', 'PDF', 'DOC', 'DOCX');

            //foreach ($file as $files){
            for ($i = 0;$i<count($file['name']);$i++){
                if($_FILES['file']['name'][$i] != null AND $_FILES['file']['error'][$i]==0){
                    $extensionProfil = pathinfo($file['name'][$i], PATHINFO_EXTENSION);
                    if (in_array($extensionProfil, $extensions_autorisees)) {
                        $nameFileProfil = pathinfo($_FILES['file']['name'][$i], PATHINFO_FILENAME);
                        $name = md5($nameFileProfil);
                        move_uploaded_file($_FILES['file']['tmp_name'][$i], 'pub_student/' . $name . '.' . $extensionProfil);
                        $tabNomFichier.=$name . '.' . $extensionProfil .';';

                    }
                }
            }

            $pub->setFile($tabNomFichier);
            $matiere = $this->matiereRepository->findOneBy(['nom' => ucfirst($request->get('matiere'))]);
            if (null != $matiere) {
                $pub->setMatiere($matiere);
            }

            $availability  = $request->get('publication_student_availability');

            if(null != $availability){
                $availability_convert= new \DateTime('@'.strtotime($availability));
                $pub->setAvailability($availability_convert);
            }

            $pub->setState(0);
            $pub->setStudent($this->getUser());
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
    public function proposition(PublicationStudent $publicationStudent, Request $request, PropositionRepository $repository): Response
    {
       // $proposition = $repository->findBy(['PublicationStudent'=> $publicationStudent]);
        $proposition = $repository->propositionWithPrestation();

     //   dd($proposition[0]->getLinePropositions()[0]->getPrestations());
        return $this->render('student/proposition.html.twig',[
            'proposition' => $proposition[0]->getLinePropositions()[0]->getPrestations() != null ? $proposition[0]->getLinePropositions() : []
        ]);
    }
}
