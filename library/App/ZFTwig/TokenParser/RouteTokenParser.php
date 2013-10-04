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
 * Wrapper for Zend Framework 1.1x url view helper.
 * Syntax : {% route 'my_route' with ['param1': 'value1'] %}
 *
 * @package     App_ZFTwig
 * @subpackage  TokenParser
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class App_ZFTwig_TokenParser_RouteTokenParser extends Twig_TokenParser
{
    /**s
     * Parses a token and returns a node.
     *
     * @param  Twig_Token $token A Twig_Token instance
     * @return Twig_NodeInterface A Twig_NodeInterface instance
     */
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $route = $this->parser->getExpressionParser()->parseExpression();

        $attributes = null;
        if ($stream->test('with')) {
            $stream->next();
            $attributes = $this->parser->getExpressionParser()->parseExpression();
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new App_ZFTwig_Node_RouteNode($route, $attributes, $lineno, $this->getTag());
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @param string The tag name
     */
    public function getTag()
    {
        return 'route';
    }
}
