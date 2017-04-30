<?php
namespace Futape\Parsers\Markdown;

include_once implode(array(rtrim(__DIR__, DIRECTORY_SEPARATOR), 'BlockParserInterface.php'), DIRECTORY_SEPARATOR);

abstract class AbstractBlockParser implements BlockParserInterface {
    /**
     * @var string
     */
    protected $pattern;
    
    /**
     * @var boolean
     */
    protected $isInlineParsingPrevented;
    
    /**
     * {@inheritDoc}
     *
     * @var boolean
     */
    protected $isContainer;
    
    
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getPattern() {
        return $this->pattern;
    }
    
    /**
     * {@inheritDoc}
     *
     * @return boolean
     */
    public function isInlineParsingPrevented() {
        return $this->isInlineParsingPrevented;
    }
    
    /**
     * {@inheritDoc}
     *
     * @return boolean
     */
    public function isContainer() {
        return $this->isContainer;
    }
    
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
    }
}
