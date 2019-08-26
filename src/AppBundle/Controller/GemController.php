<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Gem;
use AppBundle\Form\GemType;
use AppBundle\Service\Gems\GemServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GemController extends Controller
{
    /**
     * @var GemServiceInterface
     */
    private $gemService;

    /**
     * GemController constructor.
     * @param GemServiceInterface $gemService
     */
    public function __construct(GemServiceInterface $gemService)
    {
        $this->gemService = $gemService;
    }


    /**
     * @Route("/gem/create", name="gem_create", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function create()
    {
        return $this->render('gems/create.html.twig',
            ['form' => $this->createForm(GemType::class)->createView()]);
    }

    /**
     * @Route("/gem/create", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return Response
     */
    public function createProcess(Request $request)
    {
        $gem = new Gem();
        $form = $this->createForm(GemType::class, $gem);
        $form->handleRequest($request);

        $this->gemService->create($gem);

        $this->addFlash('info', 'Gem created successfully');
        return $this->redirectToRoute('gems_all');
    }

    /**
     * @Route("/gem/{id}", name="gem_view")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param  $id
     * @return Response
     */
    public function view( $id)
    {
        $gem = $this->gemService->getOne(intval($id));

        if ($gem === null) {
            return $this->redirectToRoute('gems_all');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($gem);
        $em->flush();

        return $this->render('gems/view.html.twig', ['gem' => $gem]);
    }

    /**
     * @Route("/gem/edit/{id}", name="gem_edit" ,methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id)
    {

        $gem = $this->gemService->getOne($id);

        if ($gem === null) {
            return $this->redirectToRoute('gems_all');
        }

        return $this->render('gems/edit.html.twig',
            [
                'form' => $this->createForm(GemType::class)->createView(),
                'gem' => $gem
            ]);
    }

    /**
     * @Route("/gem/edit/{id}",methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function editProcess(int $id, Request $request)
    {
        $gem = $this->gemService->getOne($id);

        $form = $this->createForm(GemType::class, $gem);
        $form->handleRequest($request);
        $this->gemService->edit($gem);

        return $this->redirectToRoute('gems_all');
    }

    /**
     * @Route("/gem/delete/{id}", name="gem_delete" ,methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Gem $gem
     * @return Response
     */
    public function delete(Gem $gem)
    {
        if ($gem === null) {
            return $this->redirectToRoute('gems_all');
        }

        return $this->render('gems/delete.html.twig',
            [
                'form' => $this->createForm(GemType::class)->createView(),
                'gem' => $gem
            ]);
    }

    /**
     * @Route("/gem/delete/{id}",methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Gem $gem
     * @param Request $request
     * @return Response
     */
    public function deleteProcess(Gem $gem, Request $request)
    {
        $form = $this->createForm(GemType::class, $gem);
        $form->handleRequest($request);

        $this->gemService->delete($gem);
        return $this->redirectToRoute('gems_all');
    }

    /**
     * @Route("/gems/all", name="gems_all" ,methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function showAll()
    {
        $gems = $this->gemService->getAll();

        return $this->render('gems/all.html.twig', ['gems' => $gems]);
    }
}