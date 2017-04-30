<?php
namespace Futape\Parsers\Markdown;

include_once implode(array(rtrim(__DIR__, DIRECTORY_SEPARATOR), 'AbstractBlockParser.php'), DIRECTORY_SEPARATOR);

class CodeBlockParser extends AbstractBlockParser {
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $pattern = '(?:\t| {4})';
    
    /**
     * {@inheritDoc}
     *
     * @var boolean
     */
    protected $isInlineParsingPrevented = true;
    
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
        $rendered = preg_replace('/^\t| {4}/', '', $val);
        
        if ($line == 0) { // first line
            $rendered = '<pre><code>' . $rendered;
        }
        if ($isLastLine) { // last line
            $rendered .= '</code></pre>'
        }
        
        return $rendered;
    }
}
