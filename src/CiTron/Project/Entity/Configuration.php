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
    const LANGUAGES = [
        'php',
        'javascript',
    ];

    const VCS = [
        'github',
        'bitbucket',
    ];

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
    static public function create() : Configuration
    {
        return new Configuration();
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
     */
    public function setLanguage($language)
    {
        $this->language = $language;
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
     */
    public function setEnvVars($envVars)
    {
        $this->envVars = $envVars;
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
     */
    public function setPreparationScript($preparationScript)
    {
        $this->preparationScript = $preparationScript;
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
     */
    public function setLaunchScript($launchScript)
    {
        $this->launchScript = $launchScript;
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
     */
    public function setVcs($vcs)
    {
        $this->vcs = $vcs;
    }
}
