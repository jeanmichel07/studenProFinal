<?php

namespace App\Controller;

use App\Entity\LineProposition;
use App\Entity\Prestation;
use App\Entity\Proposition;
use App\Entity\User;
use App\Form\PrestationType;
use App\Repository\LinePropositionRepository;
use App\Repository\PrestationRepository;
use App\Repository\PublicationStudentRepository;
use App\Repository\SpecialtyRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentProController extends AbstractController
{
    /**
     * @Route("/student/pro", name="student_pro")
     */
    public function index(): Response
    {
        return $this->render('student_pro/index.html.twig', [
            'controller_name' => 'StudentProController',
        ]);
    }

    /**
     * @Route ("/student/pro/subject-a-traiter", name="subject_prorosed")
     * @param LinePropositionRepository $linePropositionRepo
     * @param UserRepository $userRepo
     * @param PublicationStudentRepository $publicationRepo
     * @return Response
     */
    public function subjetProposerForMe (Request $request,LinePropositionRepository $linePropositionRepo, PublicationStudentRepository $publicationRepo, UserRepository $userRepo, SpecialtyRepository $specialityPro):Response
    {
        $pub = $publicationRepo->findBy(['state'=>0]);
        foreach ($pub as $p ) {
            $id_matiere = $p->getMatiere();
            $publication_stud =$publicationRepo->findBy(['id' =>$p->getId()]);
            $studentProSpeciality = $specialityPro->findBy(['matiere' => $id_matiere]);
            $user = $studentProSpeciality[0]->getUser();

            $userRepo = $userRepo->findBy(['id' => $user]);
            //dd($userRepo);
            if ($studentProSpeciality) {
               // dd($publication_student[0]);
                $proposition = new Proposition();
                $proposition->setPublicationStudent($publication_stud[0]);
                $propro = $this->getDoctrine()->getManager();
                $propro->persist($proposition);
                $propro->flush();
                $publication_student=$publication_stud[0];
                $lineProposition = new LineProposition();
                $lineProposition->setProposition($proposition);
                $lineProposition->setUser($userRepo[0]);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($lineProposition);
                $entityManager->flush();
                //Proposition en attente de validation de l'admin state=0, Proposition envoyer vers l'étudiant Pro => state =1,

                $publication_student->setState(1);
                $em = $this->getDoctrine()->getManager();
                $entityManager->persist($publication_student);
                $entityManager->flush();
            }
        }
      //  dd($request);
        $user=$this->getUser();
        $iduser=$user->getId();
        $proposition = $linePropositionRepo->findBy(['User'=>$iduser]);

        return $this->render('student_pro/subject_to_be_trated.html.twig', [
            'proposition' => $proposition
        ]);
    }

    /**
     * @Route ("{id}/student/pro/subject-accepter", name="make_proposition")
     * @param PrestationRepository $prestationRepo
     */
    public function subjectAccepted(Request $request,PrestationRepository $prestationRepo, LineProposition $lineProposition)
    {
      //  $idLine= $lineProposition->getId();
        $prestation = new Prestation();

        $form= $this->createForm(PrestationType::class,$prestation, ['idLine' =>  $lineProposition->getId()]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            dd($request);
        }
        return $this->render('student_pro/prestation.html.twig',[
            'prestationForm' => $form->createView(),
          //  'idLine' =>$idLine
        ]);
    }
}
