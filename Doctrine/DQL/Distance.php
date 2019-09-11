<?php

namespace SymfonyLab\DoctrineOrmExtensionsBundle\Doctrine\DQL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class Distance extends FunctionNode
{
    /**
     * @var string
     */
    protected $functionName = 'ST_Distance';

    /**
     * @var array
     */
    protected $platforms = ['mysql'];

    /**
     * @var Node[]
     */
    protected $geomExpr = [];
    /**
     * @var int
     */
    protected $minGeomExpr = 2;
    /**
     * @var int
     */
    protected $maxGeomExpr = 2;

    public function parse(Parser $parser)
    {
        $lexer = $parser->getLexer();
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->geomExpr[] = $parser->ArithmeticPrimary();
        while (\count($this->geomExpr) < $this->minGeomExpr || ((null === $this->maxGeomExpr || \count($this->geomExpr) < $this->maxGeomExpr) && Lexer::T_CLOSE_PARENTHESIS != $lexer->lookahead['type'])) {
            $parser->match(Lexer::T_COMMA);
            $this->geomExpr[] = $parser->ArithmeticPrimary();
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        $this->validatePlatform($sqlWalker->getConnection()->getDatabasePlatform());
        $arguments = [];
        foreach ($this->geomExpr as $expression) {
            $arguments[] = $expression->dispatch($sqlWalker);
        }

        return sprintf('%s(%s)', $this->functionName, implode(', ', $arguments));
    }

    /**
     * @throws \Exception
     */
    protected function validatePlatform(AbstractPlatform $platform)
    {
        $platformName = $platform->getName();
        if (isset($this->platforms) && !\in_array($platformName, $this->platforms)) {
            throw new \Exception(
                sprintf('DBAL platform "%s" is not currently supported.', $platformName)
            );
        }
    }
}
