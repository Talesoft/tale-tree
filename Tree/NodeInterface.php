<?php

namespace Tale\Tree;

interface NodeInterface extends LeafInterface, \IteratorAggregate, \Countable
{

    public function hasChildren();
    public function getChildCount();
    public function getChildIndex(LeafInterface $child);
    public function getChildren();
    public function setChildren(array $children);
    public function removeChildren();

    public function hasChild(LeafInterface $child);
    public function getChildAt($index);
    public function appendChild(LeafInterface $child);
    public function prependChild(LeafInterface $child);
    public function removeChild(LeafInterface $child);

    public function insertBefore(LeafInterface $child, LeafInterface $child);
    public function insertAfter(LeafInterface $child, LeafInterface $child);

    public function findChildren($callback, $depth = null, $level = null);
    public function find($callback, $depth = null);
    public function findArray($callback, $depth = null);
}