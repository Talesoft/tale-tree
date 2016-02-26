<?php

namespace Tale\Test\Tree;

use Tale\Tree\LeafInterface;
use Tale\Tree\Node;

class A extends Node {}
class B extends Node {}
class C extends Node {}
class D extends Node {}

class NodeTest extends \PHPUnit_Framework_TestCase
{

    public function testAppendChild()
    {

        $node = new Node();

        $node->appendChild($a = new A());
        $b = new B($node);
        $node->prependChild($c = new C());
        $node->appendChild($d = new D());

        $this->assertEquals(1, $a->getIndex());
        $this->assertEquals(2, $b->getIndex());
        $this->assertEquals(0, $c->getIndex());
        $this->assertEquals(3, $d->getIndex());
        $this->assertInstanceOf(A::class, $node->getChildAt(1));
        $this->assertInstanceOf(B::class, $node->getChildAt(2));
        $this->assertInstanceOf(C::class, $node->getChildAt(0));
        $this->assertInstanceOf(D::class, $node->getChildAt(3));
    }

    public function testFindChildren()
    {

        $node = new Node();

        $node->appendChild(new A())
             ->appendChild((new B())->appendChild(new B()))
             ->appendChild(new B())
             ->appendChild(new D())
             ->appendChild((new B())->appendChild((new B())->appendChild(new B())))
             ->appendChild(new C())
             ->appendChild(new D())
             ->appendChild(new C())
             ->appendChild(new D())
             ->appendChild(new C())
             ->appendChild(new D());

        $aChildren = $node->findChildrenArray(function(LeafInterface $leaf) {

            return $leaf->isInstanceOf(A::class);
        });

        $bDeepChildren = $node->findChildrenArray(function(LeafInterface $leaf) {

            return $leaf->isInstanceOf(B::class);
        });

        $bFirstChildren = $node->findChildrenArray(function(LeafInterface $leaf) {

            return $leaf->isInstanceOf(B::class);
        }, 0);

        $bSecondChildren = $node->findChildrenArray(function(LeafInterface $leaf) {

            return $leaf->isInstanceOf(B::class);
        }, 1);

        $cChildren = $node->findChildrenArray(function(LeafInterface $leaf) {

            return $leaf->isInstanceOf(C::class);
        });

        $dChildren = $node->findChildrenArray(function(LeafInterface $leaf) {

            return $leaf->isInstanceOf(D::class);
        });

        $this->assertCount(1, $aChildren, 'A children');
        $this->assertCount(6, $bDeepChildren, 'B deep children');
        $this->assertCount(3, $bFirstChildren, 'B first level');
        $this->assertCount(5, $bSecondChildren, 'B 2 levels');
        $this->assertCount(3, $cChildren, 'C children');
        $this->assertCount(4, $dChildren, 'D children');
    }
}