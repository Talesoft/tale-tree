<?php

namespace Tale\Tree;

/**
 * Interface LeafInterface
 * @package Tale\Tree
 */
interface LeafInterface
{

    /**
     * @return NodeInterface
     */
    public function hasParent();

    /**
     * @return NodeInterface
     */
    public function getParent();

    /**
     * @param NodeInterface $parent
     *
     * @return $this
     */
    public function setParent(NodeInterface $parent);

    /**
     * @return int|false
     */
    public function getIndex();

    /**
     * @return NodeInterface
     */
    public function getPreviousSibling();

    /**
     * @return NodeInterface
     */
    public function getNextSibling();

    /**
     * @param LeafInterface $child
     *
     * @return $this
     */
    public function append(LeafInterface $child);

    /**
     * @param LeafInterface $child
     *
     * @return $this
     */
    public function prepend(LeafInterface $child);

    /**
     * @return $this
     */
    public function remove();

    /**
     * @param callable $callback
     *
     * @return bool
     */
    public function is($callback);

    /**
     * @param $className
     *
     * @return bool
     */
    public function isInstanceOf($className);
}