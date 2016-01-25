<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Controller\User;


use CiTron\Controller\Controller;
use CiTron\User\Entity\User;
use JMS\Serializer\SerializationContext;

class ProfileController extends Controller
{
    /**
     * @param User $user
     * @return string
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
