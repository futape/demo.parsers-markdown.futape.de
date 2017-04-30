<?php
namespace Futape\Parsers\Markdown;

include_once implode(array(rtrim(__DIR__, DIRECTORY_SEPARATOR), 'AbstractBlockParser.php'), DIRECTORY_SEPARATOR);

abstract class AbstractHeadlineBlockParser extends AbstractBlockParser {
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $pattern;
    
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
        $tagName = 'h' . mb_strlen($tag);
        
        return '<' . $tagName . '>' . $val . '</' . $tagName . '>';
    }
}
