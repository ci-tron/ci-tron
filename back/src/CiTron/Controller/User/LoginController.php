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
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * Getting the status of authentication.
     * Notice: if the client get this page, the session will be refresh for a new 3600s.
     *
     * @return array
     */
    public function statusAction()
    {
        if ($this->getUser() !== null) {
            return $this->successResponse('Successfully logged.');
        }

        return $this->errorResponse('Not logged.', 401);
    }

    /**
     * Chceks if the user is successfully logged out.
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function logoutSuccessAction()
    {
        if (null === $this->getUser()) {
            return $this->successResponse('Successfully logged out.');
        }

        return $this->errorResponse('You are still logged in.', 417);
    }
}