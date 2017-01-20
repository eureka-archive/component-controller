<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Controller;

use Eureka\Component\Config\Config;
use Eureka\Component\Response;
use Eureka\Component\Routing\RouteInterface;
use Eureka\Component\Template\Template;
use Eureka\Component\Template\TemplateInterface;

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
     * @var TemplateInterface $template Template object.
     */
    protected $template = null;

    /**
     * @var Response\ResponseInterface $response
     */
    protected $response = null;

    /**
     * Class constructor
     *
     * @param    RouteInterface $route
     */
    public function __construct(RouteInterface $route)
    {
        $this->dataCollection = new DataCollection();
        $this->route          = $route;
    }

    /**
     * This method is executed before the main run() method.
     *
     * @return   void
     */
    public function runBefore()
    {
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

            $contentHtml = '<b>Exception[' . $exception->getCode() . ']: ' . $exception->getMessage() . '</b><pre>' . $exception->getTraceAsString() . '</pre>';

            $layoutPath = Config::getInstance()
                ->get('Eureka\Global\Theme\php\layout');
            $themeName  = Config::getInstance()
                ->get('Eureka\Global\Theme\php\theme');
            $content    = new Template($layoutPath . '/Template/' . $themeName . '/Main');
            $content->setVar('content', $contentHtml);
            $content->setVar('meta', Config::getInstance()
                ->get('meta'));
        }

        $response = Response\Factory::create($sFormat, $sEngine);
        $response->setHttpCode(500)
            ->setContent($content)
            ->send();
    }
}
