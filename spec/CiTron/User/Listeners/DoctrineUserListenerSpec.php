<?php

namespace spec\CiTron\User\Listeners;

use CiTron\User\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class DoctrineUserListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CiTron\User\Listeners\DoctrineUserListener');
    }

    function let(EncoderFactoryInterface $encoderFactory, User $user, PasswordEncoderInterface $encoder)
    {
        $this->beConstructedWith($encoderFactory);
    }

    function it_should_encode_password_when_attribute_changed(
        EncoderFactoryInterface $encoderFactory,
        User $user,
        PasswordEncoderInterface $encoder,
        LifecycleEventArgs $args,
        UnitOfWork $unitOfWork,
        EntityManager $entityManager,
        \Doctrine\ORM\Mapping\ClassMetadata $meta
    ) {
        $args->getEntityManager()->willReturn($entityManager);
        $entityManager->getUnitOfWork()->willReturn($unitOfWork);
        $entityManager->getClassMetadata(Argument::any())->willReturn($meta);
        $unitOfWork->computeChangeSet(Argument::cetera())->willReturn();
        $user->getPassword()->willReturn('pass');
        $user->getSalt()->willReturn('salt');

        $args->getEntity()->willReturn($user);
        $encoderFactory->getEncoder($user)->willReturn($encoder);
        $encoder->encodePassword(Argument::any(), Argument::any())->willReturn('encoded_pass');
        $unitOfWork->getEntityChangeSet($user)->willReturn(['password' => 'password']);

        $user->setPassword('encoded_pass')->shouldBeCalled();

        $this->postPersist($args);
    }

    function it_should_not_encode_password_when_attribute_didnt_change(
        EncoderFactoryInterface $encoderFactory,
        User $user,
        PasswordEncoderInterface $encoder,
        LifecycleEventArgs $args,
        UnitOfWork $unitOfWork,
        EntityManager $entityManager
    ) {
        $args->getEntity()->willReturn($user);
        $args->getEntityManager()->willReturn($entityManager);
        $entityManager->getUnitOfWork()->willReturn($unitOfWork);


        $unitOfWork->getEntityChangeSet($user)->willReturn([]);
        $user->setPassword(Argument::any())->shouldNotBeCalled();

        $this->postPersist($args);
    }
}
