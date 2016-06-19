<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Controller;

/**
 * Data Collection class.
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class DataCollection implements \Iterator
{
    /**
     * Length of the collection
     *
     * @var integer $length
     */
    protected $length = 0;

    /**
     * Current position of the cursor in collection.
     *
     * @var integer
     */
    protected $index = 0;

    /**
     * Index of keys
     *
     * @var array $indices
     */
    protected $indices = array();

    /**
     * Collection of data.
     *
     * @var array $collection
     */
    protected $collection = array();

    /**
     * PatternCollection constructor.
     *
     * @return DataCollection
     */
    public function __construct()
    {
        $this->collection = array();
    }

    /**
     * Add data to the collection.
     *
     * @param string $key
     * @param mixed  $value
     * @return DataCollection
     */
    public function add($key, $value)
    {
        $this->collection[$key]     = $value;
        $this->indices[$this->length] = $key;
        $this->length++;

        return $this;
    }

    /**
     * Get length of the collection.
     *
     * @return integer
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * Get current data
     *
     * @return DataCollection
     */
    public function current()
    {
        return $this->collection[$this->indices[$this->index]];
    }

    /**
     * Reset internal cursor.
     *
     * @return DataCollection
     */
    public function reset()
    {
        $this->index = 0;

        return $this;
    }

    /**
     * Get current key.
     *
     * @return string
     */
    public function key()
    {
        return $this->indices[$this->index];
    }

    /**
     * Go to the next data
     *
     * @return DataCollection
     */
    public function next()
    {
        $this->index++;

        return $this;
    }

    /**
     * Go to the previous data.
     *
     * @return DataCollection
     */
    public function rewind()
    {
        $this->index = 0;

        return $this;
    }

    /**
     * Check if have more data in the collection
     *
     * @return boolean
     */
    public function valid()
    {
        return ($this->index < $this->length);
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        foreach ($this as $key => $value) {
            $array[$key] = $value;
        }

        return $array;
    }
}