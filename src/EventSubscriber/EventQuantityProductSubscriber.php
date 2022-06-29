<?php

namespace App\EventSubscriber;


use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\ProductQuantityRepository;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class EventQuantityProductSubscriber implements EventSubscriberInterface
{

    public function __construct(private OrderRepository $orderRepository, private ProductQuantityRepository $productQuantityRepository)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            //Events::postUpdate,
        ];
    }


    public function postPersist(LifecycleEventArgs $args)
    {
         $entity = $args->getObject();

        if (!$entity instanceof Order) {
            return;
        }
        $entityManager = $args->getObjectManager();
        $entity->setBillNumber('B'.$entity->getId());

        $entityManager->flush();
    }


    /*
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }


    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof Order) {
            $entityManager = $args->getObjectManager();

            dump($entity);
            dump($entity->getProductQuantities());
            dump($this->orderRepository->getProducts($entity)); die;
           //$this->orderRepository->getProducts($entity);
            foreach ($entity->getProductQuantities()  as $productQuantity) {

                $product =  $productQuantity->getProduct();
                $product->setStock($product->getStock() - $productQuantity->getQuantity());

            }
            $entityManager->flush();
        }

    }
    */
}
