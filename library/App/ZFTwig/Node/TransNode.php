<?php
/**
 * This file is part of the App_ZFTwig package
 * 
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 *
 * @copyright  Copyright (c) 2010-2011 Benjamin Dulau <benjamin.dulau@gmail.com>
 * @license    New BSD License
 */

/**
 * Compiles ZF translate view helper to PHP.
 * @see App_ZFTwig_Extension_Helper
 *
 * @package     App_ZFTwig
 * @subpackage  Node
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class App_ZFTwig_Node_TransNode extends Twig_Node
{

    public function __construct(Twig_NodeInterface $body, $lineno, $tag = null)
    {
        parent::__construct(array('body' => $body), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
                 ->write('echo $this->env->getView()->translate(')
                 ->subcompile($this->getNode('body'))
                 ->raw(');');

        //$compiler->string($this->getNode('body'));

    }
}