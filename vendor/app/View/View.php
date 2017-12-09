<?php

namespace App\Vendor\View;

use App\Vendor\Helper\ArrayUtils;

class View {

	private $name;
	private $vars;
	private $content;
	private $tags;
	private $regexes;

	public function __construct(
		$name,
		$vars = []
	) {
        $this->name = $name;
        $this->vars = $vars;

        $this->tags = [
            'block' => ['[%', '%]'],
            'var' => ['[[', ']]'],
            'condition' => ['{{', '}}'],
        ];

        $this->regexes = [
            'extend' => '/' . preg_quote($this->tags['block'][0]) . '\sextend\s(\w+)\s' . preg_quote($this->tags['block'][1]) . '/',
            'include' => '/' . preg_quote($this->tags['block'][0]) . '\sinclude\s(\w+)\s' . preg_quote($this->tags['block'][1]) . '/',
            'block' => '/(?s)(' . preg_quote($this->tags['block'][0]) . '\sbegin\s(.*?)\s%\])(.+?)(' . preg_quote($this->tags['block'][0]) . '\send\s' . preg_quote($this->tags['block'][1]) . ')/',
            'var' => '/' . preg_quote($this->tags['var'][0]) . '(.*?)' . preg_quote($this->tags['var'][1]) . '/',
        ];
    }

	public function getVars() {
		return $this->vars;
	}

	public function setVars($vars = []) {
		$this->vars = $vars;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

    public function getExtend($content) {
		preg_match($this->regexes['extend'], $content, $matches);

		if (isset($matches[1])) {
			return $matches[1];
		}

		return false;
	}

	public function getBlocks($content) {
		preg_match_all($this->regexes['block'], $content, $matches,2);

		if(empty($matches)) {
			return false;
		}

		$blocks = [];
		foreach ($matches as $block) {
			$blocks[$block[2]] = $block[3];
		}

		return $blocks;
	}

	public function setIncludes(&$content) {
		preg_match_all($this->regexes['include'], $content, $matches,2);

		if(empty($matches)) {
			return false;
		}

		$strParams = [];
		foreach ($matches as $match) {
			$fileName = str_replace(':', '/',  $match[1]) . '.html.tpl';
			$path = __DIR__ . '/../../../src/Resources/views/@blocks/' . $fileName;
			$include = file_get_contents($path, true);
			$strParams[$match[0]] = $include;
		}

		$content = strtr($content, $strParams);
	}

	public function getContent() {
		$fileName = str_replace(':', '/', $this->getName()) . '.html.tpl';
		$path = __DIR__ . '/../../../src/Resources/views/' . $fileName;

		if (!file_exists($path)) {
			throw new \Exception('Template Error.1!');
		}

		$content = file_get_contents($path);
		if($content === false) {
			throw new \Exception('Template Error.2!');
		}

		$this->setIncludes($content);

        $strParams = [];

        if (ArrayUtils::isIterable($this->vars)) {
            foreach ($this->vars as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    continue;
                }
                $strParams[$this->tags['var'][0] . $key  . $this->tags['var'][1]] = $value;
            }
        }

        $this->content = strtr($content, $strParams);

		if($extend = $this->getExtend($this->content)) {
			$blocks = $this->getBlocks($this->content);
			$this->setName($extend);
			$this->setVars($blocks);
			$this->getContent();
		}

        $this->content = preg_replace($this->regexes['var'], '', $this->content);

		return $this->content;
	}

}
