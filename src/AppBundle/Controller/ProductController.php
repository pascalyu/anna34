<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product/{id}", name="product")
     */
    public function index(Product $product)
    {   
        
        return $this->render('product/index.html.twig', [
            'product' => $product,
          
        ]);
    }
}
