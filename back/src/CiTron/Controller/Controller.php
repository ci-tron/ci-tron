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
     * @return array
     */
    protected function successResponse($message)
    {
        return ['message' => $message];
    }

    /**
     * @param string $message
     * @param integer $code
     * @return JsonResponse
     */
    protected function errorResponse($message, $code)
    {
        return new JsonResponse(['message' => $message], $code);
    }

    /**
     * Get all errors of the form recursively
     *
     * @param FormInterface $form
     * @return array An of strings representing the errors of the form.
     */
    protected function getFormErrors(FormInterface $form)
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
