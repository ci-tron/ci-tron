<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Project\Security;

use CiTron\Project\Entity\Project;
use CiTron\User\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectVoter extends Voter
{
    const DELETE = 'delete';
    const EDIT   = 'edit';
    const READ   = 'read';
    const WRITE  = 'write';

    protected function supports($attribute, $subject) : bool
    {
        if (!in_array($attribute, [self::DELETE, self::EDIT, self::READ])) {
            return false;
        }

        if (!$subject instanceof Project) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) : bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($subject, $user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::READ:
                return $this->canRead($subject, $user);
        }

        return false;
    }

    /**
     * @param Project $project
     * @param User $user
     * @return bool
     */
    private function canDelete(Project $project, User $user) : bool
    {
        return $user === $project->getUser();
    }

    /**
     * @param Project $project
     * @param User $user
     * @return bool
     */
    private function canEdit(Project $project, User $user) : bool
    {
        return $user === $project->getUser();
    }

    /**
     * @param Project $project
     * @param User $user
     * @return bool
     */
    private function canWrite(Project $project, User $user) : bool
    {
        return $user === $project->getUser();
    }

    /**
     * @param Project $project
     * @param User $user
     * @return bool
     */
    private function canRead(Project $project, User $user) : bool
    {
        $visibility = $project->getVisibility();

        switch ($visibility) {
            case 0:
                return $this->canEdit($project, $user);
            case 1:
                return $user instanceof User;
            case 2:
                return true;
        }
    }
}
