<?php

namespace Tale\Tree;

/**
 * Interface LeafInterface
 * @package Tale\Tree
 */
interface LeafInterface
{

    public function hasParent();

    /**
     * @return NodeInterface
     */
    public function getParent();

    /**
     * @param \Tale\Tree\NodeInterface $parent
     *
     * @return $this
     */
    public function setParent(NodeInterface $parent);

    public function getIndex();

    public function getPreviousSibling();
    public function getNextSibling();

    public function append(LeafInterface $child);
    public function prepend(LeafInterface $child);

    public function remove();

    public function is($callback);
    public function isInstanceOf($className);
}