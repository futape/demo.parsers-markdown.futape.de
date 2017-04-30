<?php
namespace Futape\Parsers\Markdown;

array_map(function($file) {
    include_once implode(array(rtrim(__DIR__, DIRECTORY_SEPARATOR), $file), DIRECTORY_SEPARATOR);
}, array(
    'Headline6BlockParser.php',
    'Headline5BlockParser.php',
    'Headline4BlockParser.php',
    'Headline3BlockParser.php',
    'Headline2BlockParser.php',
    'Headline1BlockParser.php',
    'UnorderedListBlockParser.php',
    'QuoteBlockParser.php',
    'ParagraphBlockParser.php',
    //'CodeBlockParser.php',
    //'HorizontalRuleBlockParser.php',
    'StrongInlineParser.php',
    'EmphasisInlineParser.php'
));

class Parser {
    /**
     * @var BlockParserInterface[]
     */
    protected $blocks;
    
    /**
     * @var InlineParserInterface[]
     */
    protected $inline;

    /**
     * @var string
     */
    protected $md;
    
    /**
     * @var array
     */
    protected $parsed;
    
    
    /**
     * @return void
     */
    public function __construct() {
        $this->blocks = array(
            new Headline6BlockParser(),
            new Headline5BlockParser(),
            new Headline4BlockParser(),
            new Headline3BlockParser(),
            new Headline2BlockParser(),
            new Headline1BlockParser(),
            new UnorderedListBlockParser(),
            new QuoteBlockParser(),
            new ParagraphBlockParser()
            //,new CodeBlockParser()
            //,new HorizontalRuleBlockParser()
        );
        $this->inline = array(
            new StrongInlineParser(),
            new EmphasisInlineParser()
        );
    }

    /**
     * @param BlockParserInterface[] $blockParsers
     * @return void
     */
    public function setBlockParsers($blockParsers) {
        $this->blocks = $blockParsers;
    }
    
    /**
     * @param BlockParserInterface $blockParser
     * @return void
     */
    public function addBlockParser($blockParser) {
        if ($this->indexOfClass($blockParser, $this->block) === false) {
            array_push($this->block, $blockParser);
        }
    }
    
    /**
     * @param BlockParserInterface $blockParser
     * @return void
     */
    public function removeBlockParser($blockParser) {
        while (($index = $this->indexOfClass($blockParser, $this->block)) !== false) {
            array_splice($this->block, $index, 1);
        }
    }
    
    /**
     * @param InlineParserInterface[] $inlineParsers
     * @return void
     */
    public function setInlineParsers($inlineParsers) {
        $this->inline = $inlineParsers;
    }
    
    /**
     * @param InlineParserInterface $inlineParser
     * @return void
     */
    public function addInlineParser($inlineParser) {
        if ($this->indexOfClass($inlineParser, $this->inline) === false) {
            array_push($this->inline, $inlineParser);
        }
    }
    
    /**
     * @param InlineParserInterface $inlineParser
     * @return void
     */
    public function removeInlineParser($inlineParser) {
        while (($index = $this->indexOfClass($inlineParser, $this->inline)) !== false) {
            array_splice($this->inline, $index, 1);
        }
    }
    
    /**
     * @param string $file The markdown file's path
     * @return string The file's markdown contents, converted to HTML
     */
    public function renderFile($file) {
        $md = file_get_contents($file);
        
        return $this->render($md);
    }

    /**
     * Parses and renders input markdown to HTML
     *
     * @param string $markdown The markdown source
     * @return string The input markdown, converted to HTML
     */
    public function render($md) {
        $this->md = $md;
        $this->parse();
        
        return $this->renderBlocks();
    }
    
    /**
     * Parses the input markdown
     *
     * @return void
     */
    protected function parse() {
        $this->parseBlocks();
        $this->processContainerBlocks();
        $this->parseInline();
    }
    
    /**
     * @return void
     */
    protected function parseBlocks() {
        $md = $this->normalizeNl($this->md);
        $md = explode("\n", $md);
        $parsed = array();
        
        foreach ($md as $line) {
            if (preg_match('/^(' . implode($this->getPatterns($this->blocks), '|') . ')(.*)$/', $line, $match) === 1) {
                array_push($parsed, array(
                    'tag' => $match[1],
                    'value' => $match[2]
                ));
            } else {
                array_push($parsed, array(
                    'tag' => null,
                    'value' => $line
                ));
            }
        }

        $this->parsed = $parsed;
    }
    
    /**
     * @return void
     */
    protected function processContainerBlocks() {
        for ($i = 0; $i < count($this->parsed); $i++) {
            $parsed = $this->parsed[$i];
            $block = $parsed['tag'] !== null ? $this->getBlockByTag($parsed['tag']) : false;
            
            if ($block !== false && $block->isContainer()) {
                $nextInlines = 0;
                
                foreach (array_slice($this->parsed, $i + 1) as $nextParsed) {
                    if ($nextParsed['tag'] !== null) {
                        break;
                    }
                    
                    $nextInlines++;
                }
                
                $children = array_splice($this->parsed, $i + 1, $nextInlines);
                $value = implode(array_map(function($val) {
                    return $val['value'];
                }, $children), ' ');
                
                $this->parsed[$i]['value'] = $value;
            }
        }
    }
    
    /**
     * @return void
     */
    protected function parseInline() {
        array_walk($this->parsed, function(&$val) {
            $block = $val['tag'] !== null ? $this->getBlockByTag($val['tag']) : false;
            
            if ($block !== false && $block->isInlineParsingPrevented()) {
                return;
            }
            
            $parsed = array();
            $matchResult = preg_match_all('/' . implode($this->getPatterns($this->inline), '|') . '/', $val['value'], $matches, PREG_OFFSET_CAPTURE|PREG_SET_ORDER);
            
            if ($matchResult !== false && $matchResult > 0) {
                foreach ($matches as $i => $match) {
                    $valueStart = $i == 0 ? 0 : $matches[$i - 1][0][1] + mb_strlen($matches[$i - 1][0][0]);
                    $value = mb_substr($val['value'], $valueStart, $match[0][1] - $valueStart);
                    
                    array_push($parsed, array(
                        'tag' => null,
                        'value' => $value
                    ), array(
                        'tag' => $match[0][0],
                        'value' => null
                    ));
                }
                
                $valueStart = $matches[count($matches) - 1][0][1] + mb_strlen($matches[count($matches) - 1][0][0]);
                $value = mb_substr($val['value'], $valueStart);
                
                if ($value != '') {
                    array_push($parsed, array(
                        'tag' => null,
                        'value' => $value
                    ));
                }
            } else {
                array_push($parsed, array(
                    'tag' => null,
                    'value' => $val['value']
                ));
            }
            
            $this->orderInline($parsed);
        
            $val['value'] = $parsed;
        });
    }
    
    /**
     * @param array &$parsedInline
     * @return void
     */
    protected function orderInline(&$parsed) {
        for ($i = count($parsed); $i >= 0;) {
            $group = array();
            
            foreach (array_reverse(array_slice($parsed, 0, $i + 1)) as $val) {
                if ($val['tag'] !== null) {
                    array_unshift($group, $val);
                } else {
                    break;
                }
            }
            
            if (count($group) > 1) {
                $nextTags = array_filter(array_slice($parsed, $i + count($group)), function($val) {
                    return ($val['tag'] !== null);
                });
                $groupTags = array_map(function($val) {
                    return $val['tag'];
                }, $group);
                
                array_walk($group, function(&$val, $i) use (&$group, $groupTags, $nextTags) {
                    if (!array_key_exists('order', $val)) {
                        $groupClosingTag = array_search($val['tag'], $groupTags);
                        $closingTag = array_search($val['tag'], $nextTags);
                        
                        if ($groupClosingTag !== false) {
                            $val['order'] = -($i + 1);
                            $group[$groupClosingTag]['order'] = -($i + 1);
                        } else if ($closingTag !== false) {
                            $val['order'] = $closingTag + 1;
                        } else {
                            $val['order'] = 0;
                        }
                    }
                }, range(0, count($group)));
                
                usort($group, function($a, $b) {
                    if ($a['order'] == $b['order']) {
                        return 0;
                    } else if ($a['order'] < 0) {
                        return -1;
                    } else {
                        return $a['order'] < $b['order'] ? 1 : -1;
                    }
                });
                
                array_walk($group, function(&$val) {
                    unset($val['order']);
                });
                
                array_splice($parsed, $i + 1 - count($group), count($group), $group);
                
                $i -= count($group);
            } else {
                $i--;
            }
        }
    }
    
    /**
     * @return string
     */
    protected function renderBlocks() {
        /**
         * @var string[]
         */
        $rendered = array();
        
        foreach ($this->parsed as $i => $parsed) {
            $value = $this->renderInline($parsed['value']);
            $block = $parsed['tag'] !== null ? $this->getBlockByTag($parsed['tag']) : false;
            
            if ($block !== false) {
                $prevLines = 0;
                $nextLines = 0;
                
                foreach (array_reverse(array_slice($this->parsed, 0, $i)) as $prevParsed) {
                    $prevBlock = $prevParsed['tag'] !== null ? $this->getBlockByTag($prevParsed['tag']) : false;
                    
                    if ($prevBlock !== false && $this->isSameClass($prevBlock, $block)) {
                        $prevLines++;
                    }
                }
                
                foreach (array_slice($this->parsed, $i + 1) as $nextParsed) {
                    $nextBlock = $nextParsed['tag'] !== null ? $this->getBlockByTag($nextParsed['tag']) : false;
                    
                    if ($nextBlock !== false && $this->isSameClass($nextBlock, $block)) {
                        $nextLines++;
                    }
                }
                
                $line = $prevLines;
                $isLastLine = ($nextLines == 0);
                
                array_push($rendered, $block->render($value, $parsed['tag'], $line, $isLastLine));
            } else {
                array_push($rendered, $value);
            }
        }
        
        return implode($rendered, "\n");
    }
    
    /**
     * @param array $parsedParent
     * @return string
     */
    protected function renderInline($parsedParent) {
        /**
         * @var string[]
         */
        $rendered = array();
        
        foreach ($parsedParent as $parsed) {
            $value = $parsed['value'];
            $inline = $parsed['tag'] !== null ? $this->getInlineByTag($parsed['tag']) : false;
            
            if ($inline !== false) {
                array_push($rendered, '<' . $inline->getTagName() . '>' . $value . '</' . $inline->getTagName() . '>');
            } else {
                array_push($rendered, $value);
            }
        }
        
        return implode($rendered, '');
    }
    
    /**
     * @param string $tag
     * @return array|false
     */
    protected function getBlockByTag($tag) {
        $block = false;
        
        foreach ($this->blocks as $val) {
            if (preg_match('/^' . $val->getPattern() . '$/', $tag) === 1) {
                $block = $val;
                
                break;
            }
        }
        
        return $block;
    }
    
    /**
     * @param string $tag
     * @return array|false
     */
    protected function getInlineByTag($tag) {
        $inline = false;
        
        foreach ($this->inline as $val) {
            if (preg_match('/^' . $val->getPattern() . '$/', $tag) === 1) {
                $inline = $val;
                
                break;
            }
        }
        
        return $inline;
    }
    
    /**
     * @param array $parseDefinitions
     * @return string[] The parse definitions' match patterns
     */
    protected function getPatterns($parseDefinitions) {
        return array_map(function($val) {
            return $val->getPattern();
        }, $parseDefinitions);
    }
    
    /**
     * @param object $needle
     * @param $haystack
     * @return integer|false
     */
    protected function indexOfClass($needle, $haystack) {
        $index = false;
        
        foreach ($haystack as $i => $val) {
            if ($this->isSameClass($val, $needle)) {
                $index = $i;
                
                break;
            }
        }
        
        return $index;
    }
    
    /**
     * @param object $a
     * @param object $b
     * @return boolean
     */
    protected function isSameClass($a, $b) {
        return (get_class($a) == get_class($b));
    }
    
    /**
     * @param string $string The string whose line-endings should be normalized to LFs
     * @return string The input string with its line-endings normalized to LFs
     */
    protected function normalizeNl($str) {
        return preg_replace('/[\r\n]+$/m', "\n", $str);
    }
}
