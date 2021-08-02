<?php

namespace App\Controller;

use App\Entity\LineProposition;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class StripeController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function payment(Request $request): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_51HOzIcFxeqqwVcLLzdF2B5CYZDmwvqUQ8X1M8qnUT2BRW1Z40fkN48GtXCrw6igZOUtLxNCi3HwrKMq9WllAjU3v00RHrs3ZcN');
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $request->get('tarif')*100, // Le prix doit être transmis en centimes
            'currency' => 'eur',
        ]);
        return $this->render('stripe/form-stripe.html.twig',[
            'key' => $intent['client_secret'],
            'user' => $request->get('user'),
            'id' => $request->get('id'),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/change-state-line-proposition/{id}")
     * @return RedirectResponse
     */
    public function resultPayment(Request $request, LineProposition $proposition, UserRepository $userRepo,MailerInterface $mailer){
        $em = $this->getDoctrine()->getManager();
        $proposition->setState(true);
        $idUs = $proposition->getUser();
        $mail_pro= $userRepo->findBy(['id'=>$idUs->getId()]);
        
        $em->persist($proposition);
        $em->flush();
        $mail = (new Email())
                    ->from('tombokely@gmail.com')
                    ->to($mail_pro[0]->getEmail())
                    ->subject('Paiement effectuer')
                    ->html($this->renderView('emails/payement_proposition.html.twig'));
        $mailer->send($mail);

                $this->addFlash('success', 'Un email a été envoyer à l\'adresse ' . $mail_inscrit . '. Verifier dans spam si le mail n\'apparaisse pas dans votre boîte de reception. Merci');
        return  $this->redirectToRoute('subject_in_progress');
    }
}
