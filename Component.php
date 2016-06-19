<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Controller;


/**
 * Controller class
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
abstract class Component
{
    /**
     * Data collection object.
     *
     * @var DataCollection $dataCollection
     */
    protected $dataCollection = null;

    /**
     * Class constructor
     *
     * @return Component
     */
    public function __construct()
    {
        $this->dataCollection = new DataCollection();
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

}
