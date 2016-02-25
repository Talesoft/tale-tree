<?php

namespace Tale\Tree;

/**
 * Interface NodeInterface
 * @package Tale\Tree
 */
interface NodeInterface extends LeafInterface, \IteratorAggregate, \Countable, \ArrayAccess
{

    /**
     * @return bool
     */
    public function hasChildren();

    /**
     * @return int
     */
    public function getChildCount();

    /**
     * @param LeafInterface $child
     *
     * @return int|false
     */
    public function getChildIndex(LeafInterface $child);

    /**
     * @return LeafInterface[]
     */
    public function getChildren();

    /**
     * @param LeafInterface[] $children
     *
     * @return mixed
     */
    public function setChildren(array $children);

    /**
     * @return $this
     */
    public function removeChildren();

    /**
     * @param LeafInterface $child
     *
     * @return boolean
     */
    public function hasChild(LeafInterface $child);

    /**
     * @param $index
     *
     * @return bool
     */
    public function hasChildAt($index);

    /**
     * @param $index
     *
     * @return LeafInterface
     */
    public function getChildAt($index);

    /**
     * @param $index
     *
     * @return $this
     */
    public function removeChildAt($index);

    /**
     * @param LeafInterface $child
     *
     * @return $this
     */
    public function appendChild(LeafInterface $child);

    /**
     * @param LeafInterface $child
     *
     * @return $this
     */
    public function prependChild(LeafInterface $child);

    /**
     * @param LeafInterface $child
     *
     * @return $this
     */
    public function removeChild(LeafInterface $child);

    /**
     * @param LeafInterface $child
     * @param LeafInterface $newChild
     *
     * @return $this
     */
    public function insertBefore(LeafInterface $child, LeafInterface $newChild);

    /**
     * @param LeafInterface $child
     * @param LeafInterface $newChild
     *
     * @return $this
     */
    public function insertAfter(LeafInterface $child, LeafInterface $newChild);

    /**
     * @param callable $callback
     * @param int $depth
     * @param int $level
     *
     * @return \Generator
     */
    public function findChildren($callback, $depth = null, $level = null);

    /**
     * @param callable $callback
     * @param int $depth
     * @param int $level
     *
     * @return LeafInterface[]
     */
    public function findChildrenArray($callback, $depth = null, $level = null);

    /**
     * @param callable $callback
     * @param int $depth
     *
     * @return \Generator
     */
    public function find($callback, $depth = null);

    /**
     * @param callable $callback
     * @param int $depth
     *
     * @return LeafInterface[]
     */
    public function findArray($callback, $depth = null);
}