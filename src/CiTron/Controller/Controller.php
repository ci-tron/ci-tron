<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Controller
 *
 * Contains some helpful methods.
 */
class Controller extends BaseController
{
    /**
     * @param string                                        $name
     * @param string|\Symfony\Component\Form\AbstractType   $type
     * @param mixed                                         $data
     * @param array                                         $options
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createNamedForm($name, $type, $data = null, array $options = [])
    {
        return $this->get('form.factory')->createNamed($name, $type, $data, $options);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function successResponse(string $message) : JsonResponse
    {
        return new JsonResponse(['message' => $message]);
    }

    /**
     * @param string $message
     * @param int    $code
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code) : JsonResponse
    {
        return new JsonResponse(['message' => $message], $code);
    }

    /**
     * Get all errors of the form recursively
     *
     * @param FormInterface $form
     * @return array An of strings representing the errors of the form.
     */
    protected function getFormErrors(FormInterface $form) : array
    {
        $stringErrors = [];

        foreach ($form as $subForm) {
            $errors = $subForm->getErrors();

            foreach($errors as $error) {
                if (!isset($stringErrors[$subForm->getName()])) {
                    $stringErrors[$subForm->getName()] = [];
                }
                $stringErrors[$subForm->getName()][] = $error->getMessage();
            }

            $stringErrors = array_merge($stringErrors, $this->getFormErrors($subForm));
        }


        return $stringErrors;
    }
}
