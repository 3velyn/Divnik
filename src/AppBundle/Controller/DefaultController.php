<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Jewellery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="blog_index")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $jewelleries = $this
            ->getDoctrine()
            ->getRepository(Jewellery::class);

        return $this->render('default/index.html.twig', ['jewellery' => $jewelleries]);
    }
}
