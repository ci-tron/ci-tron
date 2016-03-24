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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;

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

    /**
     * This action allows the user to delete its own account. It's secured by a csrf token.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("secured/users/me/delete", name="user_deletion")
     * @Method({"DELETE"})
     */
    public function deleteAccountAction(Request $request)
    {
        $csrfManager = $this->get('security.csrf.token_manager');

        if ($csrfManager->isTokenValid(new CsrfToken('delete-me', $request->get('csrf_token')))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($this->getUser());
            $entityManager->flush();

            $this->get('security.token_storage')->setToken(null);
            $this->get('session')->invalidate();

            return $this->successResponse('You was successfully removed from our database.');
        }

        return $this->errorResponse('Your session expired, please try again.', 401);
    }
}
