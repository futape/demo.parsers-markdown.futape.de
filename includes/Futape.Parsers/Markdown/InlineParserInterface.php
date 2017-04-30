<?php
namespace Futape\Parsers\Markdown;

interface InlineParserInterface {
    /**
     * @return string
     */
    public function getPattern();
    
    /**
     * @return string
     */
    public function getTagName();
}
