<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Project\Entity;

use CiTron\Symfony\Entity\ImmutableObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Commit extends ImmutableObject
{
    static function create(): Commit
    {
        return new Commit;
    }
}
