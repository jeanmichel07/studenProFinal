<?php

namespace App\Controller;

use App\Entity\LineProposition;
use App\Entity\Prestation;
use App\Entity\Proposition;
use App\Form\PrestationType;
use App\Repository\LinePropositionRepository;
use App\Repository\PrestationRepository;
use App\Repository\PublicationStudentRepository;
use App\Repository\SpecialtyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param LinePropositionRepository $linePropositionRepo
     * @param PublicationStudentRepository $publicationRepo
     * @param UserRepository $userRepo
     * @param SpecialtyRepository $specialityPro
     * @return Response
     */
    public function subjetProposerForMe(Request $request, LinePropositionRepository $linePropositionRepo, PublicationStudentRepository $publicationRepo, UserRepository $userRepo, SpecialtyRepository $specialityPro): Response
    {
        $pub = $publicationRepo->findBy(["state" => 0]);
        foreach ($pub as $p) {
            $matiere = $p->getMatiere();
            $publication_stud = $publicationRepo->findOneBy(['id' => $p->getId()]);
            $studentProSpeciality = $specialityPro->findOneBy(['matiere' => $matiere]);
            $user = $studentProSpeciality != null ? $studentProSpeciality->getUser() : null;

            $userRepos = $userRepo->findOneBy(['id' => $user]);

            if ($studentProSpeciality) {
                $proposition = new Proposition();
                $proposition->setPublicationStudent($publication_stud);
                $propro = $this->getDoctrine()->getManager();
                $propro->persist($proposition);
                $publication_student = $publication_stud;
                $lineProposition = new LineProposition();
                $lineProposition->setProposition($proposition);
                $lineProposition->setUser($userRepos);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($lineProposition);

                $publication_student->setState(1);
                $entityManager->persist($publication_student);
                $entityManager->flush();
            }
        }
        $pub = $publicationRepo->findBy(["state" => 4]);
        foreach ($pub as $p) {
            $publication_stud = $publicationRepo->findOneBy(['id' => $p->getId()]);
            $proposition = new Proposition();
            $proposition->setPublicationStudent($publication_stud);
            $propro = $this->getDoctrine()->getManager();
            $propro->persist($proposition);
            $publication_student = $publication_stud;
            $lineProposition = new LineProposition();
            $lineProposition->setProposition($proposition);
            $lineProposition->setUser($this->getUser())->setState(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lineProposition);

            $publication_student->setState(1);
            $entityManager->persist($publication_student);
            $entityManager->flush();
        }
        //  dd($request);
        $user = $this->getUser();
        $iduser = $user->getId();
        $proposition = $linePropositionRepo->findBy(['User' => $iduser]);
        // dd($proposition);
        return $this->render('student_pro/subject_to_be_trated.html.twig', [
            'proposition' => $proposition
        ]);
    }

    /**
     * @Route ("{id}/student/pro/subject-accepter", name="make_proposition")
     * @param Request $request
     * @param PrestationRepository $prestationRepo
     * @param LineProposition $lineProposition
     * @return RedirectResponse|Response
     */
    public function subjectAccepted(Request $request, PrestationRepository $prestationRepo, LineProposition $lineProposition)
    {
        $prestation = new Prestation();

        $form = $this->createForm(PrestationType::class, $prestation, ['idLine' => $lineProposition->getId()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tarif = $prestation->getTarif();

            $prestation->setLineProposition($lineProposition);
            $prestation->setTarif($tarif);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prestation);
            $entityManager->flush();
            return $this->redirectToRoute('subject_prorosed');
        }
        return $this->render('student_pro/prestation.html.twig', [
            'prestationForm' => $form->createView(),
        ]);
    }
}
