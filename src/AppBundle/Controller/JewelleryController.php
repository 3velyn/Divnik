<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Gem;
use AppBundle\Entity\Jewellery;
use AppBundle\Entity\User;
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
        $jewellery = new Jewellery();
        $form = $this->createForm(JewelleryType::class, $jewellery);
        $this->addGemsToJewellery($jewellery, $request);
        $form->handleRequest($request);
        $this->uploadFile($form, $jewellery);

        $this->jewelleryService->create($jewellery);
        $this->addFlash('info', 'jewellery created successfully');
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

        return $this->render('jewellery/all.html.twig', ['jewelleries' => $jewelleries]);
    }


    /**
     * @Route("/jewellery/edit/{id}", name="jewellery_edit", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Jewellery $jewellery
     * @return Response
     */
    public function edit(Jewellery $jewellery)
    {
        $gems = $this->gemService->getAll();

        if ($jewellery === null) {
            return $this->redirectToRoute('jewellery_all');
        }

        return $this->render('jewellery/edit.html.twig',
            [
                'jewellery' => $jewellery,
                'gems' => $gems,
                'form' => $this->createForm(JewelleryType::class)->createView()
            ]);
    }

    /**
     * @Route("/jewellery/edit/{id}", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Jewellery $jewellery
     * @param Request $request
     * @return RedirectResponse
     */
    public function editProcess(Jewellery $jewellery, Request $request)
    {
        $form = $this->createForm(JewelleryType::class, $jewellery);
        $this->updateGemsInJewellery($jewellery, $request);
        $form->handleRequest($request);
        $this->uploadFile($form, $jewellery);
        $this->jewelleryService->edit($jewellery);

        return $this->redirectToRoute('jewellery_all');
    }

    /**
     * @Route("/jewellery/delete/{id}", name="jewellery_delete" ,methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Jewellery $jewellery
     * @return Response
     */
    public function delete(Jewellery $jewellery)
    {
        if (!$this->isAdmin()){
            return $this->redirectToRoute('blog_index');
        }
        if ($jewellery === null) {
            return $this->redirectToRoute('jewellery_all');
        }

        return $this->render('jewellery/delete.html.twig',
            [
                'form' => $this->createForm(JewelleryType::class)->createView(),
                'jewellery' => $jewellery
            ]);
    }

    /**
     * @Route("/jewellery/delete/{id}", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Jewellery $jewellery
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function deleteProcess(Jewellery $jewellery, Request $request)
    {
        $gems = $jewellery->getGems();

        $form = $this->createForm(JewelleryType::class, $jewellery);
        $form->remove('image');
        $form->handleRequest($request);

        $this->jewelleryService->delete($jewellery);
        return $this->redirectToRoute('jewellery_all');

    }

    /**
     * @Route("/jewellery/view/{id}", name="jewellery_view")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Jewellery $jewellery
     * @return RedirectResponse|Response
     */
    public function view(Jewellery $jewellery)
    {
        if ($jewellery === null) {
            $this->redirectToRoute('jewellery_all');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($jewellery);
        $em->flush();

        $gems = $jewellery->getGems();
//        var_dump($gems);
//        exit();

        return $this->render('jewellery/view.html.twig', ['gems' => $gems, 'jewellery' => $jewellery]);

    }

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

    /**
     * @return bool
     */
    private function isAdmin()
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser->isAdmin()) {
            return false;
        }

        return true;
    }

    /**
     * @param Jewellery $jewellery
     * @param Request $request
     */
    private function addGemsToJewellery(Jewellery $jewellery, Request $request): void
    {
        $data = $request->request->all();
        foreach ($data as $key => $datum) {
            if ($key !== 'jewellery') {
                $jewellery->addGem($this->gemService->getOne($key));
            }
        }
    }

    /**
     * @param Jewellery $jewellery
     * @param Request $request
     */
    private function updateGemsInJewellery(Jewellery $jewellery, Request $request)
    {
        $gems = $this->gemService->getAll();
        foreach ($gems as $gem) {
            $jewellery->removeGem($gem);
        }
        $this->addGemsToJewellery($jewellery, $request);
    }
}