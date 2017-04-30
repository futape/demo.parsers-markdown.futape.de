<?php
namespace Futape\Parsers\Markdown;

class UnorderedListBlockParser extends AbstractBlockParser {
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $pattern = '\*[\t ]';
    
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
        $rendered = '<li>' . $val . '</li>';
        
        if ($line == 0) { // first line
            $rendered = '<ul>' . "\n" . $rendered;
        }
        if ($isLastLine) { // last line
            $rendered .= "\n" . '</ul>';
        }
        
        return $rendered;
    }
}
