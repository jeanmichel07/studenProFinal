<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\LinePropositionRepository;
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
     * @param LinePropositionRepository $lineProposition
     * @param UserRepository $userRepo
     * @return Response
     */
    public function subjetProposerForMe (Request $request,LinePropositionRepository $lineProposition, UserRepository $userRepo):Response
    {
      //  dd($request);
        $user=$this->getUser();
        $iduser=$user->getId();
        $proposition = $lineProposition->findBy(['User'=>$iduser]);

        return $this->render('student_pro/subject_to_be_trated.html.twig', [
            'proposition' => $proposition
        ]);
    }

    /**
     * @Route ("/student/pro/subject-accepter", name="proposition_accepted")
     */
    public function subjectAccepted()
    {

    }
}
