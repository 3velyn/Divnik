<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Jewellery;
use AppBundle\Entity\User;
use AppBundle\Service\Cart\CartService;
use AppBundle\Service\Jewelleries\JewelleryServiceInterface;
use AppBundle\Service\Users\UserServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends Controller
{
    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var ShipmentController
     */
    private $shipmentController;

    /**
     * @var JewelleryServiceInterface
     */
    private $jewelleryService;

    public function __construct(UserServiceInterface $userService,
                                CartService $cartService,
                                ShipmentController $shipmentController,
                                JewelleryServiceInterface $jewelleryService)
    {
        $this->userService = $userService;
        $this->cartService = $cartService;
        $this->shipmentController = $shipmentController;
        $this->jewelleryService = $jewelleryService;
    }

    /**
     * @Route("/cart", name="cart_view")
     *
     * @return Response
     */
    public function indexAction()
    {
        $user = $this->userService->currentUser();
        list($cart, $totalCartPrice) = $this->getCartInfo($user);

        return $this->render('cart/view.html.twig', ['cart' => $cart, 'totalCartPrice' => $totalCartPrice]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Jewellery $jewellery
     * @return Response
     */
    public function addToCart(Jewellery $jewellery)
    {
        $user = $this->userService->currentUser();
        $currentCartItem = $this->cartService->findOneByUserIdAndItemId($user->getId(), $jewellery->getId());

        if ($currentCartItem === null) {
            $this->addNewItem($jewellery, $user);
        } else {
            $currentCartItem->setQuantity($currentCartItem->getQuantity() + 1);
            $this->cartService->edit($currentCartItem);
        }

        $this->addFlash('info', "Successfully added {$jewellery->getName()} to your cart.");
        return $this->createJewelleryView($jewellery);
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Jewellery $jewellery
     * @return Response
     */
    public function removeFromCart(Jewellery $jewellery)
    {
        $user = $this->userService->currentUser();
        $currentCartItem = $this->cartService->findOneByUserIdAndItemId($user->getId(), $jewellery->getId());

        if ($currentCartItem === null) {
            $this->addFlash('errors', 'There is no such item in your cart.');
            return $this->indexAction();
        }

        if ($currentCartItem->getQuantity() > 1) {
            $currentCartItem->setQuantity($currentCartItem->getQuantity() - 1);
            $this->cartService->edit($currentCartItem);
        } else {
            $this->cartService->delete($currentCartItem);
        }

        $this->addFlash('info', "Successfully removed {$jewellery->getName()} from your cart.");
        list($cart, $totalCartPrice) = $this->getCartInfo($user);
        return $this->render('cart/view.html.twig', ['cart' => $cart, 'totalCartPrice' => $totalCartPrice]);
    }

    /**
     * @param Jewellery $jewellery
     * @return Response
     */
    private function createJewelleryView(Jewellery $jewellery)
    {
        $gems = $jewellery->getGems();
        return $this->render('jewellery/view.html.twig', ['gems' => $gems, 'jewellery' => $jewellery]);
    }

    /**
     * @param Jewellery $jewellery
     * @param User $user
     */
    private function addNewItem(Jewellery $jewellery, User $user)
    {
        $cart = new Cart();
        $cart->setUserId($user->getId());
        $cart->setJewelleryId($jewellery->getId());
        $cart->setName($jewellery->getName());
        $cart->setImage($jewellery->getImage());
        $cart->setQuantity(1);
        $cart->setPrice($jewellery->getPrice());

        $this->cartService->create($cart);
    }

    /**
     * @param User|null $user
     * @return array
     */
    private function getCartInfo(?User $user): array
    {
        $cart = $this->cartService->getAllByUser($user->getId());

        $totalCartPrice = 0;
        foreach ($cart as $item) {
            $totalCartPrice += $item->getTotalItemPrice();
        }
        return array($cart, $totalCartPrice);
    }

    /**
     * @Route("/checkout", name="checkout")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function checkout()
    {
        $user = $this->userService->currentUser();

        if ($user->getAddress() === '' || $user->getPhone() === '') {
            $this->addFlash('errors', 'Please fill missing info');
            return $this->redirectToRoute('user_profile_edit', ['user' => $user]);
        }
        list($cart, $totalCartPrice) = $this->getCartInfo($user);
        $this->shipmentController->create($cart, $totalCartPrice);
        $this->emptyCart($user);

        $this->addFlash('info', 'Your order was sent');
        return $this->redirectToRoute('blog_index', ['jewelleries' => $this->jewelleryService->getAll()]);
    }

    /**
     * @param User|null $user
     */
    private function emptyCart(?User $user)
    {
        $currentCart = $this->cartService->getAllByUser($user->getId());
        foreach ($currentCart as $item) {
            $this->cartService->delete($item);
        }
    }
}
