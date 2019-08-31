<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Cart;
use AppBundle\Entity\Shipment;
use AppBundle\Entity\ShipmentItems;
use AppBundle\Service\Jewelleries\JewelleryServiceInterface;
use AppBundle\Service\Shipments\ShipmentServiceInterface;
use AppBundle\Service\Users\UserServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @var ShipmentItemsController
     */
    private $shipmentItemsController;

    /**
     * @var JewelleryServiceInterface
     */
    private $jewelleryService;

    /**
     * ShipmentController constructor.
     * @param ShipmentServiceInterface $shipmentService
     * @param UserServiceInterface $userService
     * @param ShipmentItemsController $shipmentItemsController
     * @param JewelleryServiceInterface $jewelleryService
     */
    public function __construct(ShipmentServiceInterface $shipmentService,
                                UserServiceInterface $userService,
                                ShipmentItemsController $shipmentItemsController,
                                JewelleryServiceInterface $jewelleryService)
    {
        $this->shipmentService = $shipmentService;
        $this->userService = $userService;
        $this->shipmentItemsController = $shipmentItemsController;
        $this->jewelleryService = $jewelleryService;
    }

    /**
     *
     * @param Cart[] $cart
     * @param float $totalPrice
     */
    public function create($cart, float $totalPrice)
    {
        $user = $this->userService->currentUser();
        $shipment = new Shipment();
        $shipment->setTotalPrice($totalPrice);
        $this->shipmentService->create($shipment);

        $shipment = $this->shipmentService->getLastUserShipment($user);

        /** @var Cart $item */
        foreach ($cart as $item) {
            $jewellery = $this->jewelleryService->getOne($item->getJewelleryId());
            $this->shipmentItemsController->add($shipment, $jewellery, $item->getQuantity());
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
        $shipmentItems = $this->shipmentItemsController->loadShipmentItems($shipments);

        return $this->render('shipments/user_history.html.twig', ['shipments' => $shipments]);
    }


}