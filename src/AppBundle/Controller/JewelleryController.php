<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Jewellery;
use AppBundle\Form\JewelleryType;
use AppBundle\Service\Gems\GemServiceInterface;
use AppBundle\Service\Jewelleries\JewelleryServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JewelleryController extends Controller
{
    /**
     * @var JewelleryServiceInterface
     */
    private $jewelleryService;

    /**
     * @var GemServiceInterface
     */
    private $gemService;

    public function __construct(JewelleryServiceInterface $jewelleryService,
                                GemServiceInterface $gemService)
    {
        $this->jewelleryService = $jewelleryService;
        $this->gemService = $gemService;
    }

    /**
     * @Route("/jewellery/create", name="jewellery_create", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function create()
    {
        $gems = $this->gemService->getAll();
        return $this->render('jewellery/create.html.twig',
            [
                'form' => $this->createForm(JewelleryType::class)->createView(),
                'gems' => $gems
            ]);
    }

    /**
     * @Route("/jewellery/create", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function createProcess(Request $request)
    {
        $data = $request->request->all();

        $jewellery = new Jewellery();
        $form = $this->createForm(JewelleryType::class, $jewellery);
        foreach ($data as $key => $datum) {
            if ($key !== 'jewellery'){
                $jewellery->addGem($this->gemService->getOne($key));
            }
        }

        $form->handleRequest($request);
        $this->uploadFile($form, $jewellery);

        $this->jewelleryService->create($jewellery);
        $this->addFlash('info', 'Article created successfully');
        return $this->redirectToRoute('jewellery_all');
    }

    /**
     * @Route("/jewellery/all", name="jewellery_all" ,methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function showAll()
    {
        $jewelleries = $this->getDoctrine()->getRepository(Jewellery::class)->findAll();

        return $this->render('default/index.html.twig', ['jewelleries' => $jewelleries]);
    }

//    /**
//     * @Route("/gem/delete/{id}", name="gem_delete" ,methods={"GET"})
//     * @Security("is_granted('ROLE_ADMIN')")
//     *
//     * @param Jewellery $jewellery
//     * @return Response
//     */
//    public function delete(Jewellery $jewellery)
//    {
//        if ($jewellery === null) {
//            return $this->redirectToRoute('gems_all');
//        }
//
//        return $this->render('gems/delete.html.twig',
//            [
//                'form' => $this->createForm(GemType::class)->createView(),
//                'gem' => $jewellery
//            ]);
//    }

    /**
     * @param FormInterface $form
     * @param Jewellery $jewellery
     */
    private function uploadFile(FormInterface $form, Jewellery $jewellery)
    {
        /** @var UploadedFile $file */
        $file = $form['image']->getData();
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        if ($file) {
            $file->move(
                $this->getParameter('jewellery_directory'),
                $fileName
            );

            $jewellery->setImage($fileName);
        }
    }
}