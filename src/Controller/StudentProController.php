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
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            $studentProSpeciality = $specialityPro->findOneBy(['matiere' => $id_matiere]);
            $user = $studentProSpeciality != null ? $studentProSpeciality->getUser() : null;


            $userRepos = $userRepo->findOneBy(['id' => $user]);

            if ($studentProSpeciality) {

                $proposition = new Proposition();
                $proposition->setPublicationStudent($publication_stud[0]);
                $propro = $this->getDoctrine()->getManager();
                $propro->persist($proposition);
                $propro->flush();
                $publication_student=$publication_stud[0];
                $lineProposition = new LineProposition();
                $lineProposition->setProposition($proposition);
                $lineProposition->setUser($userRepos);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($lineProposition);
                $entityManager->flush();

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
        //dd($proposition);
        return $this->render('student_pro/subject_to_be_trated.html.twig', [
            'proposition' => $proposition
        ]);
    }

    /**
     * @Route ("{id}/student/pro/subject-accepter", name="make_proposition")
     * @param PrestationRepository $prestationRepo
     * @return RedirectResponse|Response
     */
    public function subjectAccepted(Request $request,PrestationRepository $prestationRepo, LineProposition $lineProposition)
    {
        $idLine= $lineProposition->getId();
        $prestation = new Prestation();

        $form= $this->createForm(PrestationType::class,$prestation, ['idLine' =>  $lineProposition->getId()]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           $tarif=$prestation->getTarif();

            $prestation->setLineProposition($lineProposition);
            $prestation->setTarif($tarif);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prestation);
            $entityManager->flush();
            return $this->redirectToRoute('subject_prorosed');
        }
        return $this->render('student_pro/prestation.html.twig',[
            'prestationForm' => $form->createView(),
        ]);
    }
}
