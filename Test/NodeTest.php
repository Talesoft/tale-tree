<?php

namespace Tale\Test;



use Tale\Tree\Node;

class NodeTest extends \PHPUnit_Framework_TestCase
{

    public function testAppendChild()
    {

        $node = new Node();

        $node->appendChild($a = new Node());
        $b = new Node($node);
        $node->prependChild($c = new Node());
        $node->appendChild($d = new Node());

        $this->assertEquals(1, $a->getIndex());
        $this->assertEquals(2, $b->getIndex());
        $this->assertEquals(0, $c->getIndex());
        $this->assertEquals(3, $d->getIndex());
    }
}