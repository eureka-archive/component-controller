<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Controller;

use Eureka\Component\Routing\RouteInterface;

/**
 * Controller interface
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
interface ControllerInterface
{
    /**
     * IController constructor.
     *
     * @param RouteInterface $route
     */
    public function __construct(RouteInterface $route);

    /**
     * This method is executed before the main run() method.
     *
     * @return   void
     */
    public function runBefore();

    /**
     * This method is executed after the main run() method.
     *
     * @return   void
     */
    public function runAfter();

    /**
     * Handle exception
     *
     * @param  \Exception $exception
     * @return void
     * @throws \Exception
     */
    public function handleException(\Exception $exception);

}
