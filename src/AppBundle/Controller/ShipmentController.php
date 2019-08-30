<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Cart;
use AppBundle\Entity\Shipment;
use AppBundle\Service\Jewelleries\JewelleryServiceInterface;
use AppBundle\Service\Shipments\ShipmentServiceInterface;
use AppBundle\Service\Users\UserServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShipmentController extends Controller
{

    /**
     * @var ShipmentServiceInterface
     */
    private $shipmentService;

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @var JewelleryServiceInterface
     */
    private $jewelleryService;

    /**
     * ShipmentController constructor.
     * @param ShipmentServiceInterface $shipmentService
     * @param UserServiceInterface $userService
     * @param JewelleryServiceInterface $jewelleryService
     */
    public function __construct(ShipmentServiceInterface $shipmentService,
                                UserServiceInterface $userService,
                                JewelleryServiceInterface $jewelleryService)
    {
        $this->shipmentService = $shipmentService;
        $this->userService = $userService;
        $this->jewelleryService = $jewelleryService;
    }

    /**
     *
     * @param Cart[] $cart
     * @param float $totalPrice
     */
    public function create($cart, float $totalPrice)
    {
        /** @var Cart $item */
        foreach ($cart as $item) {
            $shipment = new Shipment();

            $shipment->setName($item->getName());
            $shipment->setQuantity($item->getQuantity());
            $shipment->setJewelleryId($item->getJewelleryId());
            $shipment->setTotalPrice($totalPrice);

            if (!in_array($shipment, $this->shipmentService->getAll())) {
                $this->shipmentService->create($shipment);
            }
        }
    }

    /**
     * @Route("/history", name="orders_history")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function showUserShipmentHistory()
    {
        $user = $this->userService->currentUser();
        $shipments = $this->shipmentService->getAllByUser($user);

        return $this->render('shipments/user_history.html.twig', ['shipments' => $shipments]);
    }
}