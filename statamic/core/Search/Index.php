<?php

namespace Statamic\Search;

use Mmanos\Search\Index\Zend;
use Mmanos\Search\Index as MmanosIndex;

/**
 * This class acts as an adapter or middleman between Statamic and the Mmanos/Search
 * index class so we can perform some data manipulation before it goes through.
 */
class Index
{
    /**
     * @var MmanosIndex
     */
    private $index;

    /**
     * @param MmanosIndex $index
     */
    public function __construct(MmanosIndex $index)
    {
        $this->index = $index;
    }

    /**
     * Insert a document into the index
     *
     * @param string $id
     * @param array $fields
     */
    public function insert($id, $fields)
    {
        // Nested arrays aren't supported by Zend so we'll convert them to dot notation.
        // For example, ['foo' => ['bar' => ['baz' => 'qux']]] will be converted to
        // ['foo.bar.baz' => 'qux']. Other drivers will continue to use arrays.
        if ($this->index instanceof Zend) {
            $fields = array_dot($fields);
        }

        $this->index->insert($id, $fields);
    }

    /**
     * Pass along any methods onto the underlying index
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->index, $method), $arguments);
    }
}
