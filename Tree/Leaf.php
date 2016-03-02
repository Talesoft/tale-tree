<?php

namespace Tale\Tree;

/**
 * Class Leaf
 *
 * @package Tale\Tree
 */
class Leaf implements LeafInterface
{

    /**
     * @var NodeInterface
     */
    private $parent;

    /**
     * Leaf constructor.
     *
     * @param NodeInterface $parent
     */
    public function __construct(NodeInterface $parent = null)
    {

        $this->parent = null;

        if ($parent !== null)
            $this->setParent($parent);
    }

    /**
     *
     */
    public function __clone()
    {

        $this->parent = null;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {

        return $this->parent !== null;
    }

    /**
     * @return NodeInterface
     */
    public function getParent()
    {

        return $this->parent;
    }

    /**
     * @param NodeInterface $parent
     *
     * @return $this
     */
    public function setParent(NodeInterface $parent = null)
    {

        if ($this->parent === $parent)
            return $this;

        if ($parent !== null && $this->parent && $this->parent->hasChild($this)) {

            $this->parent->removeChild($this);
            return $this;
        }

        $this->parent = $parent;

        if ($parent !== null && !$parent->hasChild($this))
            $parent->appendChild($this);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIndex()
    {

        if ($this->parent === null)
            return null;

        return $this->parent->getChildIndex($this);
    }

    /**
     * @return LeafInterface
     */
    public function getPreviousSibling()
    {

        $idx = $this->getIndex();
        if ($idx === null || $idx === 0) //Includes "not found" and "0", which means this is the first sibling
            return null;

        return $this->parent->getChildAt($idx - 1);
    }

    /**
     * @return LeafInterface
     */
    public function getNextSibling()
    {

        $idx = $this->getIndex();
        if ($idx === null || $idx >= count($this->parent) - 1)
            return null;

        return $this->parent->getChildAt($idx + 1);
    }

    /**
     * @param LeafInterface $child
     *
     * @return $this
     */
    public function append(LeafInterface $child)
    {

        $this->parent->insertAfter($this, $child);
        return $this;
    }

    /**
     * @param LeafInterface $child
     *
     * @return $this
     */
    public function prepend(LeafInterface $child)
    {

        $this->parent->insertBefore($this, $child);

        return $this;
    }

    /**
     * @return $this
     */
    public function remove()
    {

        $this->parent->removeChild($this);

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return bool
     */
    public function is($callback)
    {

        if (!is_callable($callback))
            throw new \InvalidArgumentException(
                "Argument 1 passed to Leaf->is is not a valid callback"
            );

        return call_user_func($callback, $this) === true;
    }

    /**
     * @param $className
     *
     * @return bool
     */
    public function isInstanceOf($className)
    {

        return is_a($this, $className);
    }
}