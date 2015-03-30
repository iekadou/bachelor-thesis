<?php

class Twig_Url_TokenParser extends Twig_TokenParser
{
    public function parse(Twig_Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();

        $name = $stream->expect(Twig_Token::STRING_TYPE)->getValue();
        if (!$stream->test(Twig_Token::BLOCK_END_TYPE)) {
            $value = $parser->getExpressionParser()->parseExpression();
        } else {
            $value = new Twig_Node_Expression_Array(array(), $token->getLine());
        }
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new Twig_Url_Node($name, $value, $token->getLine(), $this->getTag());
    }

    public function getTag()
    {
        return 'url';
    }
}

class Twig_Url_Node extends Twig_Node
{
    public function __construct($name, $value, $line, $tag = null)
    {
        parent::__construct(array('value' => $value), array('name' => $name), $line, $tag);
    }

    public function compile(Twig_Compiler $compiler)
    {
        $compiler
            ->raw("echo ")
            ->raw("UrlsPy::get_url('")
            ->raw($this->getAttribute('name'))
            ->raw("',")
            ->subcompile($this->getNode('value'))
            ->raw(");\n")
        ;
    }
}