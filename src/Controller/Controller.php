<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Controller;

use Eureka\Component\Config\Config;
use Eureka\Component\Http\Message\ServerRequest;
use Eureka\Component\Response;
use Eureka\Component\Response\Html\Template as ResponseTemplate;
use Eureka\Component\Routing\RouteCollection;
use Eureka\Component\Routing\RouteInterface;
use Eureka\Component\Template\Template;
use Eureka\Component\Template\TemplateInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Controller class
 *
 * @author Romain Cottard
 */
abstract class Controller implements ControllerInterface
{
    /**
     * @var RouteInterface $route Route object.
     */
    protected $route = null;

    /**
     * @var DataCollection $dataCollection Data collection object.
     */
    protected $dataCollection = null;

    /**
     * @var string $modulePath Module path.
     */
    protected $modulePath = '';

    /**
     * @var TemplateInterface $template Template object.
     */
    protected $template = null;

    /**
     * @var Response\ResponseInterface $response
     */
    protected $response = null;

    /**
     * @var string $themeName Theme name
     */
    protected $themeName = '';

    /**
     * @var string $themeLayout Theme layout path
     */
    protected $themeLayoutPath = '';

    /**
     * @var string $themeLayoutTemplate Theme layout template name
     */
    protected $themeLayoutTemplate = 'Main';

    /**
     * @var ServerRequestInterface $request
     */
    private $request = null;

    /**
     * Class constructor
     *
     * @param  RouteInterface $route
     * @param  ServerRequestInterface $request
     */
    public function __construct(RouteInterface $route, ServerRequestInterface $request = null)
    {
        $this->dataCollection = new DataCollection();
        $this->route          = $route;
        $this->request        = $request;
    }

    /**
     * This method is executed before the main run() method.
     *
     * @return   void
     */
    public function runBefore()
    {
        $this->themeName       = Config::getInstance()->get('Eureka\Global\Theme\php\theme');
        $this->themeLayoutPath = Config::getInstance()->get('Eureka\Global\Theme\php\layout');
    }

    /**
     * This method is executed after the main run() method.
     *
     * @return   void
     */
    public function runAfter()
    {
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getRequest()
    {
        if (!($this->request instanceof ServerRequestInterface)) {
            $this->request = ServerRequest::createFromGlobal();
        }

        return $this->request;
    }

    /**
     * Handle exception
     *
     * @param  \Exception $exception
     * @return void
     * @throws \Exception
     */
    public function handleException(\Exception $exception)
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']);

        if ($isAjax) {

            $sEngine = Response\Factory::ENGINE_API;
            $sFormat = Response\Factory::FORMAT_JSON;
            $content = json_encode($exception->getTraceAsString());
        } else {
            $sEngine = Response\Factory::ENGINE_TEMPLATE;
            $sFormat = Response\Factory::FORMAT_HTML;

            if (EKA_ENV !== 'prod') {
                $contentHtml = '<b>Exception[' . $exception->getCode() . ']: ' . $exception->getMessage() . '</b><pre>' . $exception->getTraceAsString() . '</pre>';
            }

            $layoutPath = Config::getInstance()->get('Eureka\Global\Theme\php\layout');
            $themeName  = Config::getInstance()->get('Eureka\Global\Theme\php\theme');
            $content    = new Template($layoutPath . '/Template/' . $themeName . '/Main');
            $content->setVar('content', $contentHtml);
            $content->setVar('meta', Config::getInstance()->get('meta'));
        }

        $response = Response\Factory::create($sFormat, $sEngine);
        $response->setHttpCode(500)->setContent($content)->send();
    }

    /**
     * Add data to the data collection.
     *
     * @param  string $key
     * @param  mixed $value
     * @return static
     */
    protected function addData($key, $value)
    {
        $this->dataCollection->add($key, $value);

        return $this;
    }

    /**
     * Get data collection.
     *
     * @return DataCollection
     */
    protected function getData()
    {
        return $this->dataCollection;
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

    /**
     * Get Response object
     *
     * @param  string $templateName
     * @return ResponseInterface
     * @deprecated
     */
    protected function getResponse($templateName)
    {
        $this->response = new ResponseTemplate();
        $this->response->setHttpCode(200);
        $this->response->setContent($this->getLayout($this->getTemplate($templateName)));

        return $this->response;
    }

    /**
     * Get Response object
     *
     * @param  string $templateName
     * @return ResponseInterface
     * @deprecated
     */
    protected function getResponseJson($content)
    {
        $this->response = new Response\Json\Api();
        $this->response->setHttpCode(200);
        $this->response->setContent($content);

        return $this->response;
    }

    /**
     * Get Main layout template
     *
     * @param  TemplateInterface $template
     * @return Template
     * @deprecated
     */
    protected function getLayout(TemplateInterface $template)
    {
        $layout = new Template($this->getThemeLayoutPath() . '/Template/'. $this->getThemeName() . '/' . $this->getThemeLayoutTemplate());
        $layout->setVar('content', $template->render());
        $layout->setVar('meta', Config::getInstance()->get('Eureka\Global\Meta'));

        return $layout;
    }

    /**
     * Get template instance
     *
     * @param  string $templateName
     * @return Template
     * @deprecated
     */
    protected function getTemplate($templateName)
    {
        $template = new Template($this->getModulePath() . '/Template/' . $this->getThemeName() . '/' . $templateName);
        $template->setVars($this->dataCollection->toArray());

        return $template;
    }

    /**
     * Get theme layout template name.
     *
     * @return string
     * @deprecated
     */
    protected function getThemeLayoutTemplate()
    {
        return $this->themeLayoutTemplate;
    }

    /**
     * Set theme layout template name.
     *
     * @param  string $themeLayoutTemplate
     * @return $this
     * @deprecated
     */
    protected function setThemeLayoutTemplate($themeLayoutTemplate)
    {
        $this->themeLayoutTemplate = $themeLayoutTemplate;

        return $this;
    }

    /**
     * Get layout path.
     *
     * @return string
     * @deprecated
     */
    protected function getThemeLayoutPath()
    {
        return $this->themeLayoutPath;
    }

    /**
     * Get theme name.
     *
     * @return string
     * @deprecated
     */
    protected function getThemeName()
    {
        return $this->themeName;
    }
}
