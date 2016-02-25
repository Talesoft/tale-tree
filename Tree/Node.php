<?php

namespace Tale\Tree;

use Tale\TreeException;
use Traversable;

/**
 * Class Node
 * @package Tale\Tree
 */
class Node extends Leaf implements NodeInterface
{

    /**
     * @var LeafInterface[]
     */
    private $_children;

    /**
     * Node constructor.
     *
     * @param NodeInterface $parent
     * @param array $children
     */
    public function __construct(NodeInterface $parent = null, array $children = null)
    {
        parent::__construct($parent);

        $this->_children = [];

        if (!is_null($children))
            $this->setChildren($children);
    }

    /**
     *
     */
    public function __clone()
    {
        parent::__clone();

        foreach ($this->_children as $child)
            //clone $child will remove the parent and this clear the children
            //We re-append them directly
            $this->appendChild(clone $child);
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {

        return !empty($this->_children);
    }

    /**
     * @return int
     */
    public function getChildCount()
    {

        return count($this->_children);
    }

    /**
     * @param LeafInterface $child
     *
     * @return LeafInterface[]
     */
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

    /**
     * @param array $children
     *
     * @return $this
     */
    public function setChildren(array $children)
    {

        $this->removeChildren();
        foreach ($children as $child)
            $this->appendChild($child);

        return $this;
    }

    /**
     * @return $this
     */
    public function removeChildren()
    {

        foreach ($this->_children as $child)
            $child->setParent(null);

        return $this;
    }

    /**
     * @param LeafInterface $child
     *
     * @return bool
     */
    public function hasChild(LeafInterface $child)
    {

        return in_array($child, $this->_children, true);
    }

    /**
     * @param $index
     *
     * @return bool
     */
    public function hasChildAt($index)
    {

        return isset($this->_children[$index]);
    }

    /**
     * @param $index
     *
     * @return LeafInterface
     */
    public function getChildAt($index)
    {

        return $this->_children[$index];
    }

    /**
     * @param $index
     *
     * @return $this
     * @throws TreeException
     */
    public function removeChildAt($index)
    {

        if (!$this->hasChildAt($index))
            throw new TreeException(
                "Failed to remove child: No child found at $index"
            );

        return $this->removeChild($this->getChildAt($index));
    }

    /**
     * @param LeafInterface $child
     */
    private function _prepareChild(LeafInterface $child)
    {

        if ($this->hasChild($child))
            $this->removeChild($child);
    }

    /**
     * @param LeafInterface $child
     */
    private function _finishChild(LeafInterface $child)
    {

        if ($child->getParent() !== $this)
            $child->setParent($this);
    }

    /**
     * @param LeafInterface $child
     *
     * @return $this
     */
    public function appendChild(LeafInterface $child)
    {

        $this->_prepareChild($child);
        $this->_children[] = $child;
        $this->_finishChild($child);

        return $this;
    }

    /**
     * @param LeafInterface $child
     *
     * @return $this
     */
    public function prependChild(LeafInterface $child)
    {

        $this->_prepareChild($child);
        array_unshift($this->_children, $child);
        $this->_finishChild($child);

        return $this;
    }

    /**
     * @param LeafInterface $child
     *
     * @return $this
     */
    public function removeChild(LeafInterface $child)
    {

        $idx = array_search($child, $this->_children, true);

        if ($idx !== false) {

            unset($this->_children[$idx]);
            $child->setParent(null);
        }

        return $this;
    }

    /**
     * @param LeafInterface $child
     * @param LeafInterface $newChild
     *
     * @return $this
     * @throws TreeException
     */
    public function insertBefore(LeafInterface $child, LeafInterface $newChild)
    {

        if (!$this->hasChild($child))
            throw new TreeException(
                "Failed to insert before: Passed child is not a child of element to insert in"
            );

        $this->_prepareChild($newChild);
        array_splice($this->_children, $child->getIndex(), 0, [$newChild]);
        $this->_finishChild($newChild);

        return $this;
    }

    /**
     * @param LeafInterface $child
     * @param LeafInterface $newChild
     *
     * @return $this
     * @throws TreeException
     */
    public function insertAfter(LeafInterface $child, LeafInterface $newChild)
    {

        if (!$this->hasChild($child))
            throw new TreeException(
                "Failed to insert after: Passed child is not a child of element to insert in"
            );

        $this->_prepareChild($newChild);
        array_splice($this->_children, $child->getIndex() + 1, 0, [$newChild]);
        $this->_finishChild($newChild);

        return $this;
    }

    /**
     * @param callable $callback
     * @param int $depth
     * @param int $level
     *
     * @return \Generator
     */
    public function findChildren($callback, $depth = null, $level = null)
    {

        $level = $level ?: 0;

        foreach ($this->_children as $child) {

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

    /**
     * @param callable $callback
     * @param int $depth
     * @param int $level
     *
     * @return LeafInterface[]
     */
    public function findChildrenArray($callback, $depth = null, $level = null)
    {

        return iterator_to_array($this->findChildren($callback, $depth, $level));
    }

    /**
     * @param callable $callback
     * @param int $depth
     *
     * @return \Generator
     */
    public function find($callback, $depth = null)
    {

        if ($this->is($callback))
            yield $this;

        foreach ($this->findChildren($callback, $depth) as $child)
            yield $child;
    }

    /**
     * @param callable $callback
     * @param int $depth
     *
     * @return LeafInterface[]
     */
    public function findArray($callback, $depth = null)
    {

        return iterator_to_array($this->find($callback, $depth));
    }

    /**
     * @return Traversable
     */
    public function getIterator()
    {

        return new \ArrayIterator($this->_children);
    }

    /**
     * @return int
     */
    public function count()
    {

        return $this->getChildCount();
    }

    /**
     * @param int $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {

        return $this->hasChildAt($offset);
    }

    /**
     * @param int $offset
     *
     * @return LeafInterface
     */
    public function offsetGet($offset)
    {

        return $this->getChildAt($offset);
    }

    /**
     * @param int $offset
     * @param LeafInterface $value
     */
    public function offsetSet($offset, $value)
    {

        if (!($value instanceof LeafInterface))
            throw new \InvalidArgumentException(
                "Argument 2 passed to Node->offsetSet needs to be instance ".
                "of ".LeafInterface::class
            );

        if ($offset >= count($this)) {

            $this->appendChild($value);
            return;
        }

        $old = $this->getChildAt($offset);
        $old->append($value);
        $old->remove();
    }

    /**
     * @param int $offset
     *
     * @throws TreeException
     */
    public function offsetUnset($offset)
    {

        $this->removeChildAt($offset);
    }
}
