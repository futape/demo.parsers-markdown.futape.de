<?php
namespace Futape\Parsers\Markdown;

include_once implode(array(rtrim(__DIR__, DIRECTORY_SEPARATOR), 'InlineParserInterface.php'), DIRECTORY_SEPARATOR);

abstract class AbstractInlineParser implements InlineParserInterface {
    /**
     * @var string
     */
    protected $pattern;
    
    /**
     * @var string
     */
    protected $tagName;
    
    
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
     * @return string
     */
    public function getTagName() {
        return $this->tagName;
    }
}
