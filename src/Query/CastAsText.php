<?php

namespace App\Query;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;

class CastAsText extends FunctionNode
{
    public $string;

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker){
        return 'CAST('.$this->string->dispatch($sqlWalker).' AS text)';
    }

    public function parse(Parser $parser){
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->string = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}