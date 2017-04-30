<?php
namespace Futape\Parsers\Markdown;

include_once implode(array(rtrim(__DIR__, DIRECTORY_SEPARATOR), 'AbstractInlineParser.php'), DIRECTORY_SEPARATOR);

class EmphasisInlineParser extends AbstractInlineParser {
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $pattern = '\*';
    
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $tagName = 'em';
}
