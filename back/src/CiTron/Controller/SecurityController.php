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


class SecurityController extends Controller
{
    /**
     * Generates a token for a given intention
     *
     * @param string $name
     * @return array
     */
    public function getCsrfTokenAction($name)
    {
        return ['token' => $this->get('security.csrf.token_manager')->getToken($name)->getValue()];
    }
}
