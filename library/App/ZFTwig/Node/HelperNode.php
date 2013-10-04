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
 * Compiles any ZF view helper to PHP.
 * @see App_ZFTwig_Extension_Helper
 *
 * @package     App_ZFTwig
 * @subpackage  Node
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class App_ZFTwig_Node_HelperNode extends Twig_Node
{
    protected $helper;

    public function __construct($helper, Twig_Node_Expression $attributes = null, $lineno, $tag = null)
    {
        $this->helper = $helper;
        parent::__construct(array(), array('helper_attributes' => $attributes), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler)
    {
        $args = $this->getAttribute('helper_attributes');
        $compiler
            ->addDebugInfo($this)
            ->write('echo $this->env->getView()->')
            ->raw($this->helper)
            ->raw('(');


        if ($args) {
            $i = 0;
            $argCount = count($args);
            foreach($args as $arg) {
                $i++;
                $compiler->subcompile($arg);
                if ($i < $argCount) {
                    $compiler->raw(', ');
                }
            }
        }

        $compiler->raw(');');
    }
}