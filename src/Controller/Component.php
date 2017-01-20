<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Controller;

use Eureka\Component\Config\Config;
use Eureka\Component\Template\Template;

/**
 * Controller class
 *
 * @author Romain Cottard
 */
abstract class Component
{
    /**
     * @var DataCollection $dataCollection Data collection object.
     */
    protected $dataCollection = null;

    /**
     * @var string $themeName Theme name
     */
    protected $themeName = '';

    /**
     * @var TemplateInterface $template Template object.
     */
    protected $template = null;

    /**
     * @var string $modulePath Module path.
     */
    protected $modulePath = '';

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->dataCollection = new DataCollection();

        $this->themeName = Config::getInstance()->get('Eureka\Global\Theme\php\theme');
    }

    /**
     * This method is executed before the main run() method.
     *
     * @return  void
     */
    public function runBefore()
    {
    }

    /**
     * This method is executed after the main run() method.
     *
     * @return  void
     */
    public function runAfter()
    {
    }

    /**
     * Get theme name.
     *
     * @return string
     */
    protected function getThemeName()
    {
        return $this->themeName;
    }

    /**
     * Render template
     *
     * @param  string $templateName
     * @return string
     */
    protected function render($templateName)
    {
        $template = new Template($this->getModulePath() . '/Template/' . $this->getThemeName() . '/' . $templateName);
        $template->setVars($this->dataCollection->toArray());

        return $template->render();
    }

    /**
     * @param  string $key
     * @param  mixed $value
     * @return self
     */
    protected function addData($key, $value)
    {
        $this->dataCollection->add($key, $value);

        return $this;
    }

    /**
     * Get module path.
     *
     * @return string
     */
    protected function getModulePath()
    {
        return $this->modulePath;
    }

    /**
     * Set module path.
     *
     * @param  string $modulePath
     * @return $this
     */
    protected function setModulePath($modulePath)
    {
        $this->modulePath = $modulePath;

        return $this;
    }
}
