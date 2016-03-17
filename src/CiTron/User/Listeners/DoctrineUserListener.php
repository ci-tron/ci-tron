<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\User\Listeners;


use CiTron\User\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class DoctrineUserListener
 *
 * Encode the password on the fly when it's modified.
 */
class DoctrineUserListener
{
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Encode the password if it change
     *
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // We will only work on User entities
        if ($entity instanceof User) {

            $em         = $args->getEntityManager();
            $unitOfWork = $em->getUnitOfWork();

            $changeSet  = $unitOfWork->getEntityChangeSet($entity);

            // If the password was changed we have to encode it
            if (array_key_exists('password', $changeSet)) {
                $password = $this->encoderFactory
                    ->getEncoder($entity)
                    ->encodePassword($entity->getPassword(), $entity->getSalt())
                ;
                $entity->setPassword($password);

                // Register the password modification
                $unitOfWork->computeChangeSet($em->getClassMetadata(get_class($entity)), $entity);
            }
        }
    }
}
