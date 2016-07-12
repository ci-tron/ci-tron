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
use JMS\Serializer\Annotation as JMS;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Type("string")
     */
    protected $language;

    /**
     * @var string
     * @ORM\Column(type="json_array", nullable=true)
     * @JMS\Type("array")
     */
    protected $envVars;

    /**
     * @var string
     * @ORM\Column(type="json_array", nullable=true)
     * @JMS\Type("array")
     */
    protected $preparationScript;

    /**
     * @var string
     * @ORM\Column(type="json_array", nullable=true)
     * @JMS\Type("array")
     */
    protected $launchScript;

    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $host;

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
    
    static public function getSupportedLanguages()
    {
        return [
            self::LANGUAGE_JAVASCRIPT,
            self::LANGUAGE_PHP,
        ];
    }
    
    static public function getSupportedVcs()
    {
        return [
            self::VCS_SVN,
            self::VCS_GIT,
        ];
    }
    
    static public function getSupportedHosts()
    {
        return [
            self::HOST_BITBUCKET,
            self::HOST_GITHUB,
        ];
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
    public function setLanguage($language)
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
     * @param array $envVars
     * @return Configuration
     */
    public function setEnvVars($envVars)
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
    public function setPreparationScript($preparationScript)
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
    public function setLaunchScript($launchScript)
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
    public function setVcs($vcs)
    {
        $this->vcs = $vcs;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return Configuration
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }
}
