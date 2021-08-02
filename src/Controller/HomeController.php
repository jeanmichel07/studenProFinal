<?php

namespace App\Controller;

use App\Manager\AppManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        if($user == null){
            return $this->redirectToRoute('choices');
        }else{
            $role_user= $user->getRoles();
            $firstconnex= $user->getFirstConnexion();
            if($role_user==["ROLE_STUDENT_PRO"] && $firstconnex==true ){

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('new_password');
            }else{
                return $this->render('home/index.html.twig', [
                    'controller_name' => 'HomeController',
                    // 'firstconnex'=>$firstconnex
                ]);
            }
        }

    }

    /**
     * @Route("/nous-contacter", name="contact_us")
     */
    public function contactUs(): Response
    {
        return $this->render('home/contact_us.html.twig');
    }

    /**
     * @Route("/aides", name="help")
     */
    public function help(): Response
    {
        return $this->render('home/help.html.twig');
    }

    /**
     * @Route("/you-are", name="choices")
     * @param Request $request
     * @param AppManager $manager
     * @return RedirectResponse|Response
     */
    public function choices(Request $request, AppManager $manager){
        $user = $this->getUser();
        //dd($user);
        if(!is_null($user) && !($user->getIsVerified())){
            return $this->redirectToRoute('home');
        }
        return $this->render('home/choices.html.twig',[
            'simple' => $manager->encrypt('simple'),
            'pro' => $manager->encrypt('pro')
        ]);
    }
}
