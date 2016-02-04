<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\User\Controller;


use CiTron\Controller\Controller;
use CiTron\User\Entity\User;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ProfileController extends Controller
{
    /**
     * @param User $user
     * @return string
     *
     * @Route("secured/users/profile/{slug}.json", name="user_profile")
     * @Method({"GET"})
     */
    public function getUserProfileAction(User $user)
    {
        return $this->get('jms_serializer')->serialize(
            $user,
            'json',
            SerializationContext::create()->setGroups(['standard'])
        );
    }

    /**
     * @return string
     *
     * @Route("secured/users/profile.json", name="user_current_profile")
     * @Method({"GET"})
     */
    public function getMyProfileAction()
    {
        return $this->get('jms_serializer')->serialize(
            $this->getUser(),
            'json',
            SerializationContext::create()->setGroups(['current'])
        );
    }
}
