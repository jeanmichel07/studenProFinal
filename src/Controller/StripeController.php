<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            'key' => $intent['client_secret']
        ]);
    }
}
