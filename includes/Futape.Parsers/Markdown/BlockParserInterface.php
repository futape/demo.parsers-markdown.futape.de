<?php
namespace Futape\Parsers\Markdown;

interface BlockParserInterface {
    /**
     * @return string
     */
    public function getPattern();
    
    /**
     * @return boolean
     */
    public function isInlineParsingPrevented();
    
    /**
     * @return boolean
     */
    public function isContainer();
    
    /**
     * @param string $value
     * @param string $tag
     * @param integer $line
     * @param boolean $isLastLine
     * @return string
     */
    public function render($val, $tag, $line, $isLastLine);
}
