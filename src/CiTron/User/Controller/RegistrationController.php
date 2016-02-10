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
use CiTron\Symfony\HttpFoundation\JsonResponse;
use CiTron\User\Entity\User;
use CiTron\User\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class RegistrationController extends Controller
{
    /**
     * Allow to create new user
     *
     * @param Request $request
     * @return array|JsonResponse
     *
     * @Route("/registration", name="registration")
     * @Method({"POST"})
     */
    public function createUserAction(Request $request)
    {
        if ($this->getUser() !== null) {
            return $this->errorResponse('Cannot create a new user.', 400);
        }

        $user = new User();

        $form = $this->createNamedForm('', UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->successResponse('Your account was correctly created.');
        }

        return new JsonResponse([
            'errors' => $this->getFormErrors($form),
        ], 400);
    }
}
