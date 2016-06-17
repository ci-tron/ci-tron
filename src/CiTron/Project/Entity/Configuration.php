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
class Configuration extends ImmutableObject
{
    const LANGUAGE_JAVASCRIPT = 'JAVASCRIPT';
    const LANGUAGE_PHP = 'PHP';

    const HOST_GITHUB = 'GITHUB';
    const HOST_BITBUCKET = 'BITBUCKET';

    const VCS_GIT = 'GIT';
    const VCS_SVN = 'SVN';

    /**
     * @var string
     * @ORM\Column(name="language", type="string", length=255, nullable=true)
     */
    protected $language;

    /**
     * @var string
     * @ORM\Column(name="envVars", type="json_array", nullable=true)
     */
    protected $envVars;

    /**
     * @var string
     * @ORM\Column(name="preparationScript", type="json_array", nullable=true)
     */
    protected $preparationScript;

    /**
     * @var string
     * @ORM\Column(name="launchScript", type="json_array", nullable=true)
     */
    protected $launchScript;

    /**
     * @var string
     * @ORM\Column(name="vcs", type="string", length=255, nullable=true)
     */
    protected $vcs;

    /**
     * @return Configuration
     */
    static public function create(string $language = null, array $preparation = null, array $launch = null) : Configuration
    {
        return (new Configuration())
            ->setLaunchScript($launch)
            ->setPreparationScript($preparation)
            ->setLanguage($language)
        ;
    }

    /**
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return Configuration
     */
    private function setLanguage($language)
    {
        $this->language = $language;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnvVars()
    {
        return $this->envVars;
    }

    /**
     * @param string $envVars
     * @return Configuration
     */
    private function setEnvVars($envVars)
    {
        $this->envVars = $envVars;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPreparationScript()
    {
        return $this->preparationScript;
    }

    /**
     * @param string $preparationScript
     * @return Configuration
     */
    private function setPreparationScript($preparationScript)
    {
        $this->preparationScript = $preparationScript;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLaunchScript()
    {
        return $this->launchScript;
    }

    /**
     * @param string $launchScript
     * @return Configuration
     */
    private function setLaunchScript($launchScript)
    {
        $this->launchScript = $launchScript;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVcs()
    {
        return $this->vcs;
    }

    /**
     * @param string $vcs
     * @return Configuration
     */
    private function setVcs($vcs)
    {
        $this->vcs = $vcs;
        
        return $this;
    }
}
