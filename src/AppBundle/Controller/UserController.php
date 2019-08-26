<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\Users\UserServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @var UserServiceInterface
     */
    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/register", name="user_register", methods={"GET"})
     *
     * @return Response
     */
    public function register()
    {
        return $this->render('users/register.html.twig',
            ["form" => $this->createForm(UserType::class)->createView()]);
    }

    /**
     * @Route("/register", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function registerProcess(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $alreadyRegisteredUser = $this->userService->findOneByEmail($form['email']->getData());
        if ($alreadyRegisteredUser !== null) {
            $this->addFlash('errors', "Email {$alreadyRegisteredUser->getEmail()} already taken");
            return $this->returnRegisterView($user);
        }

        if ($form['password']['first']->getData() !== $form['password']['second']->getData()) {
            $this->addFlash('errors', "Password mismatch!");
            return $this->returnRegisterView($user);
        }

        $this->userService->save($user);

        return $this->redirectToRoute("security_login");

    }

    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile()
    {
        return $this->render('users/profile.html.twig',
            ["user" => $this->userService->currentUser()]);
    }

    /**
     * @Route("/logout", name="security_logout")
     *
     * @throws \Exception
     */
    public function logout()
    {
        throw new \Exception("Logout failed!");
    }

    /**
     * @Route("/profile/edit", name="user_profile_edit", methods={"GET"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return Response
     */
    public function edit()
    {
        return $this->render('users/edit.html.twig',
            [
                'user' => $this->userService->currentUser(),
                'form' => $this->createForm(UserType::class)->createView()
            ]);
    }

    /**
     * @Route("/profile/edit", methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return Response
     */
    public function editProcess(Request $request)
    {
        $currentUser = $this->userService->currentUser();
        $form = $this->createForm(UserType::class, $currentUser);
        $form->remove('password');

        if ($currentUser->getEmail() === $request->request->get('email')) {
            $form->remove('email');
        }
        $form->handleRequest($request);

        $this->uploadFile($form, $currentUser);
        $this->userService->update($currentUser);

        return $this->redirectToRoute('user_profile');
    }

    /**
     * @param FormInterface $form
     * @param User $user
     */
    private function uploadFile(FormInterface $form, User $user)
    {
        /** @var UploadedFile $file */
        $file = $form['image']->getData();
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        if ($file) {
            $file->move(
                $this->getParameter('users_directory'),
                $fileName
            );

            $user->setImage($fileName);
        }

//        $fs = new filesystem();
//        $fileToDestroy = $this->getParameter('users_directory') . '/' . $user->getImage();
//        $fs->remove(array($fileToDestroy));
    }

    /**
     * @param User $user
     * @return Response
     */
    public function returnRegisterView(User $user): Response
    {
        return $this->render('users/register.html.twig',
            [
                'user' => $user,
                'form' => $this->createForm(UserType::class)->createView()
            ]);
    }
}
