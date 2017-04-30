<?php
namespace Futape\Parsers\Markdown;

include_once implode(array(rtrim(__DIR__, DIRECTORY_SEPARATOR), 'AbstractInlineParser.php'), DIRECTORY_SEPARATOR);

class StrongInlineParser extends AbstractInlineParser {
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $pattern = '\*{2}';
    
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $tagName = 'strong';
}
