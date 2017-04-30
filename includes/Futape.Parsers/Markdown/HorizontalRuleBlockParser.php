<?php
namespace Futape\Parsers\Markdown;

include_once implode(array(rtrim(__DIR__, DIRECTORY_SEPARATOR), 'AbstractBlockParser.php'), DIRECTORY_SEPARATOR);

class HorizontalRuleBlockParser extends AbstractBlockParser {
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $pattern = '(?:=+|-+|\*+|_+)\s*$';
    
    /**
     * {@inheritDoc}
     *
     * @var boolean
     */
    protected $isInlineParsingPrevented = false;
    
    /**
     * {@inheritDoc}
     *
     * @var boolean
     */
    protected $isContainer = false;
    
    
    /**
     * {@inheritDoc}
     *
     * @param string $value
     * @param string $tag
     * @param integer $line
     * @param boolean $isLastLine
     * @return string
     */
    public function render($val, $tag, $line, $isLastLine) {
        return '<hr />';
    }
}
