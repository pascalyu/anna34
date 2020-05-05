<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends Controller
{
    /**
     * @Route("/checkout/{id}", name="checkout")
     */
    public function checkout(Product $product, Request $request)
    {


        \Stripe\Stripe::setApiKey('sk_test_JnHJc1sqerTrEgtiJgDa7faZ003caa3AxZ');

        $intent = \Stripe\PaymentIntent::create([
            'amount' => $product->getPrice()*100,
            'currency' => 'eur',
            // Verify your integration in this guide by including this parameter
            'metadata' => ['integration_check' => 'accept_a_payment'],
        ]);
        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
        }

        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'intent' => $intent,
            'product' => $product
        ]);
    }
}
