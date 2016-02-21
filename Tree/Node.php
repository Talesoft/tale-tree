<?php

namespace Tale\Tree;

use Exception;

class Node extends Leaf implements NodeInterface
{

    /**
     * @var LeafInterface[]
     */
    private $_children;

    public function __construct(NodeInterface $parent = null, array $children = null)
    {
        parent::__construct($parent);

        $this->_children = [];

        if (!is_null($children))
            $this->setChildren($children);
    }

    public function hasChildren()
    {

        return !empty($this->_children);
    }

    public function getChildCount()
    {

        return count($this->_children);
    }

    public function getChildIndex(LeafInterface $child)
    {

        return array_search($child, $this->_children, true);
    }

    /**
     * @return LeafInterface[]
     */
    public function getChildren()
    {

        return $this->_children;
    }

    public function setChildren(array $children)
    {

        $this->removeChildren();
        foreach ($children as $child)
            $this->appendChild($child);

        return $this;
    }

    public function removeChildren()
    {

        foreach ($this->_children as $child)
            $child->setParent(null);

        return $this;
    }

    public function hasChild(LeafInterface $child)
    {

        return in_array($child, $this->_children, true);
    }

    public function getChildAt($index)
    {

        return $this->_children[$index];
    }

    private function _prepareChild(LeafInterface $child)
    {

        if ($this->hasChild($child))
            $this->removeChild($child);
    }

    private function _finishChild(LeafInterface $child)
    {

        if ($child->getParent() !== $this)
            $child->setParent($this);
    }

    public function appendChild(LeafInterface $child)
    {

        $this->_prepareChild($child);
        $this->_children[] = $child;
        $this->_finishChild($child);

        return $this;
    }

    public function prependChild(LeafInterface $child)
    {

        $this->_prepareChild($child);
        array_unshift($this->_children, $child);
        $this->_finishChild($child);

        return $this;
    }

    public function removeChild(LeafInterface $child)
    {

        $idx = array_search($child, $this->_children, true);

        if ($idx !== false) {

            unset($this->_children[$idx]);
            $child->setParent(null);
        }

        return $this;
    }

    public function insertBefore(LeafInterface $child, LeafInterface $newChild)
    {

        if (!$this->hasChild($child))
            throw new Exception(
                "Failed to insert before: Passed child is not a child of element to insert in"
            );

        $this->_prepareChild($newChild);
        array_splice($this->_children, $child->getIndex(), 0, [$newChild]);
        $this->_finishChild($newChild);

        return $this;
    }

    public function insertAfter(LeafInterface $child, LeafInterface $newChild)
    {

        if (!$this->hasChild($child))
            throw new Exception(
                "Failed to insert after: Passed child is not a child of element to insert in"
            );

        $this->_prepareChild($newChild);
        array_splice($this->_children, $child->getIndex() + 1, 0, [$newChild]);
        $this->_finishChild($newChild);

        return $this;
    }

    public function findChildren($callback, $depth = null, $level = null)
    {

        $level = $level ?: 0;

        foreach ($this->getChildren() as $child) {

            /** @var NodeInterface $child */
            if ($child->is($callback))
                yield $child;

            if ($depth === null || $level < $depth) {

                if ($child instanceof NodeInterface)
                    foreach ($child->findChildren($callback, $depth, $level + 1) as $subChild)
                        yield $subChild;
            }
        }
    }

    public function find($callback, $depth = null)
    {

        if ($this->is($callback))
            yield $this;

        foreach ($this->findChildren($callback, $depth) as $child)
            yield $child;
    }

    public function findArray($callback, $depth = null)
    {

        return iterator_to_array($this->find($callback, $depth));
    }

    public function getIterator()
    {

        return new \ArrayIterator($this->_children);
    }

    public function count()
    {

        return count($this->_children);
    }

    public function __clone()
    {
        parent::__clone();

        foreach ($this->_children as $child)
            //clone $child will remove the parent and this clear the children
            //We re-append them directly
            $this->appendChild(clone $child);
    }
}
