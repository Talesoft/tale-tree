<?php

namespace Tale\Tree;

class Leaf implements LeafInterface
{

    /**
     * @var NodeInterface
     */
    private $_parent;

    public function __construct(NodeInterface $parent = null)
    {

        $this->_parent = null;

        if ($parent !== null)
            $this->setParent($parent);
    }

    public function __clone()
    {

        $this->_parent = null;
    }

    public function hasParent()
    {

        return $this->_parent !== null;
    }

    public function getParent()
    {

        return $this->_parent;
    }

    public function setParent(NodeInterface $parent = null)
    {

        if ($this->_parent === $parent)
            return $this;

        if (is_null($parent) && $this->_parent && $this->_parent->hasChild($this)) {

            $this->_parent->removeChild($this);
            return $this;
        }

        $this->_parent = $parent;

        if ($parent !== null && !$parent->hasChild($this))
            $parent->appendChild($this);

        return $this;
    }

    public function getIndex()
    {

        if ($this->_parent === null)
            return null;

        return $this->_parent->getChildIndex($this);
    }

    public function getPreviousSibling()
    {

        $idx = $this->getIndex();
        if (!$idx) //Includes "not found" and "0", which means this is the first sibling
            return null;

        return $this->_parent->getChildAt($idx - 1);
    }

    public function getNextSibling()
    {

        $idx = $this->getIndex();
        if ($idx === null || $idx >= count($this->getParent()) - 1)
            return null;

        return $this->getParent()->getChildAt($idx + 1);
    }

    public function append(LeafInterface $child)
    {

        $this->getParent()->insertBefore($this, $child);
        return $this;
    }

    public function prepend(LeafInterface $child)
    {

        $this->getParent()->insertAfter($this, $child);

        return $this;
    }

    public function remove()
    {

        $this->getParent()->removeChild($this);

        return $this;
    }

    public function is($callback)
    {

        if (!is_callable($callback))
            throw new \InvalidArgumentException(
                "Argument 1 passed to Leaf->is is not a valid callback"
            );

        return call_user_func($callback, $this);
    }

    public function isInstanceOf($className)
    {

        return is_a($this, $className);
    }
}