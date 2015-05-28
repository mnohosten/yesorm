<?php

namespace YesPanel;

use Tracy\Debugger;
use Tracy\IBarPanel;
use YesORM\ORM;

class YesPanel implements IBarPanel {

	/** @var NotORMPanel singleton instance */
	private static $_instance = null;

	/** @var array */
	private $queries = array();

    /** @var  float */
    private $totalTime;

	/**
	 * @var string
	 */
	private $platform = '';

    public function register(ORM &$db) {
        Debugger::getBar()->addPanel($this);
        $db->debug = function($query, $parameters, $microtime=null) {
            self::getInstance()->logQuery($query, $parameters, $microtime);
        };
    }

	public function getPlatform() {
		return $this->platform;
	}

	public function setPlatform($platform) {
		$this->platform = $platform;
	}

    private function getTotalTime() {
        if(!isset($this->totalTime) || !$this->totalTime) {
            foreach ($this->queries as $query) {
                $this->totalTime += $query['microtime'];
            }
        }
        return $this->totalTime;
    }


	public function __construct() {
		self::$_instance = $this;
	}

	/**
	 * Enforce singleton. Disallow cloning.
	 *
	 * @return void
	 */
	private function __clone() {}

	/**
	 * Create singleton instance
	 *
	 * @return NotORMPanel
	 */
	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function getId() {
		return 'NotORM';
	}

	/**
	 * @return string HTML code for Debugbar
	 */
	public function getTab() {
		return '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAHpJREFUOMvVU8ENgDAIBON8dgY7yU3SHTohfoQUi7FGH3pJEwI9oBwl+j1YDRGR8AIzA+hiAIxLsoOW1R3zB9Cks1VKmaQWXz3wHWEJpBbilF3wivxKB9OdiUfDnJ6Q3RNGyWp3MraytbKqjADkrIvhPYgSDG3itz/TBsqre3ItA1W8AAAAAElFTkSuQmCC">'
            . count($this->queries) . ' queries / ' . sprintf("%.3f ms", $this->getTotalTime()*1000);
	}

	/**
	 * @return string HTML code for Debugbar detail
	 */
	public function getPanel() {
		if (count($this->queries) == 0) {
			return NULL;
		}

		$i = 0;
		$platform = $this->platform;
		$queries = $this->queries;
        $totalTime = $this->getTotalTime();

        ob_start();
		require_once __DIR__ . '/panel.latte';
		return ob_get_clean();
	}

	public function logQuery($sql, array $params = NULL, $microtime = NULL) {
		$this->queries[] = array('sql' => $sql, 'params' => $params, 'microtime' => $microtime);
	}

	public static function dump($sql) {
		$keywords1 = 'CREATE\s+TABLE|CREATE(?:\s+UNIQUE)?\s+INDEX|SELECT|UPDATE|INSERT(?:\s+INTO)?|REPLACE(?:\s+INTO)?|DELETE|FROM|WHERE|HAVING|GROUP\s+BY|ORDER\s+BY|LIMIT|SET|VALUES|LEFT\s+JOIN|INNER\s+JOIN|TRUNCATE';
		$keywords2 = 'ALL|DISTINCT|DISTINCTROW|AS|USING|ON|AND|OR|IN|IS|NOT|NULL|LIKE|TRUE|FALSE|INTEGER|CLOB|VARCHAR|DATETIME|TIME|DATE|INT|SMALLINT|BIGINT|BOOL|BOOLEAN|DECIMAL|FLOAT|TEXT|VARCHAR|DEFAULT|AUTOINCREMENT|PRIMARY\s+KEY';

		// insert new lines
		$sql = " $sql ";
		$sql = self::replace($sql, "#(?<=[\\s,(])($keywords1)(?=[\\s,)])#", "\n\$1");
		if (strpos($sql, "CREATE TABLE") !== FALSE)
			$sql = Strings::replace($sql, "#,\s+#i", ", \n");

		// reduce spaces
		$sql = self::replace($sql, '#[ \t]{2,}#', " ");

		$sql = wordwrap($sql, 100);
		$sql = htmlSpecialChars($sql);
		$sql = self::replace($sql, "#([ \t]*\r?\n){2,}#", "\n");
		$sql = self::replace($sql, "#VARCHAR\\(#", "VARCHAR (");

		// syntax highlight
		$sql = self::replace($sql, "#(/\\*.+?\\*/)|(\\*\\*.+?\\*\\*)|(?<=[\\s,(])($keywords1)(?=[\\s,)])|(?<=[\\s,(=])($keywords2)(?=[\\s,)=])#s", function ($matches) {
				if (!empty($matches[1])) // comment
					return '<em style="color:gray">' . $matches[1] . '</em>';

				if (!empty($matches[2])) // error
					return '<strong style="color:red">' . $matches[2] . '</strong>';

				if (!empty($matches[3])) // most important keywords
					return '<strong style="color:blue">' . $matches[3] . '</strong>';

				if (!empty($matches[4])) // other keywords
					return '<strong style="color:green">' . $matches[4] . '</strong>';
			}
		);
		$sql = trim($sql);
		return '<pre class="dump">' . $sql . "</pre>\n";
	}

    private static function replace($subject, $pattern, $replacement = NULL, $limit = -1)
    {
        if (is_object($replacement) || is_array($replacement)/*5.2* || preg_match('#^\x00lambda_\d+\z#', $replacement)*/) {
            
            set_error_handler(function($severity, $message) use (& $tmp) { // preg_last_error does not return compile errors
                restore_error_handler();
                throw new \Exception("$message in pattern: $tmp");
            });
            foreach ((array) $pattern as $tmp) {
                preg_match($tmp, '');
            }
            restore_error_handler();

            $res = preg_replace_callback($pattern, $replacement, $subject, $limit);
            if ($res === NULL && preg_last_error()) { // run-time error
                throw new \Exception(NULL, preg_last_error(), $pattern);
            }
            return $res;

        } elseif ($replacement === NULL && is_array($pattern)) {
            $replacement = array_values($pattern);
            $pattern = array_keys($pattern);
        }

        set_error_handler(function($severity, $message) use ($pattern) { // preg_last_error does not return compile errors
            restore_error_handler();
            throw new \Exception("$message in pattern: " . implode(' or ', (array) $pattern));
        });
        $res = preg_replace($pattern, $replacement, $subject, $limit);
        restore_error_handler();
        if (preg_last_error()) { // run-time error
            throw new \Exception(NULL, preg_last_error(), implode(' or ', (array) $pattern));
        }
        return $res;
    }

}
