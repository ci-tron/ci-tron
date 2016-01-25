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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;

class ProfileController extends Controller
{
    /**
     * This action allows the user to delete its own account. It's secured by a csrf token.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAccountAction(Request $request)
    {
        $csrfManager = $this->get('security.csrf.token_manager');

        if ($csrfManager->isTokenValid(new CsrfToken('delete-me', $request->get('csrf_token')))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($this->getUser());
            $entityManager->flush();

            return $this->successResponse('You was successfully removed from our database.');
        }

        return $this->errorResponse('Your session expired, please try again.', 401);
    }
}
