<?php
namespace Futape\Parsers\Markdown;

include_once implode(array(rtrim(__DIR__, DIRECTORY_SEPARATOR), 'AbstractBlockParser.php'), DIRECTORY_SEPARATOR);

class ParagraphBlockParser extends AbstractBlockParser {
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $pattern = '\s*$';
    
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
    protected $isContainer = true;

    
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
        $rendered = $val;
        
        if ($line == 0) { // first line
            $rendered = '<p>' . $rendered;
        }
        if ($isLastLine) { // last line
            $rendered .= '</p>';
        }
        
        return $rendered;
    }
}
