<?php
namespace Futape\Parsers\Markdown;

include_once implode(array(rtrim(__DIR__, DIRECTORY_SEPARATOR), 'AbstractHeadlineBlockParser.php'), DIRECTORY_SEPARATOR);

class Headline5BlockParser extends AbstractHeadlineBlockParser {
    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $pattern = '#{5}';
}
