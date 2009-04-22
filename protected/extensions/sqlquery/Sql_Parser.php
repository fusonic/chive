<?php
/**
 *
 * Sql_Parser
 * @package Sql
 * @author Thomas Sch&#65533;fer
 * @since 30.11.2008 07:49:30
 * @desc parses sql statements into parts
 */

/**
 *
 * Sql_Parser
 * @package Sql
 * @author Thomas Schaefer
 * @since 30.11.2008 07:49:30
 * @desc parses sql statements into parts
 */
class Sql_Parser {

	const SEMICOLON = ";";
	const OPENBRACE = "(";
	const CLOSEBRACE = ")";
	const ESCAPE = "'";
	const SPACE = " ";
	const TICK = "`";
	const ALIAS = " AS ";
	const ON = " ON ";
	const DBLQUOTE = '"';
	const QUOTE =  "'";

	private $isUnion = false;

	private static $dialects = array("Mysql");

	/**
	 * constructor
	 *
	 * @param string $string
	 * @param string $dialect
	 */
	public function __construct($string = null, $dialect = "Mysql")
	{
		Sql_Parser::setDialect($dialect);
		if (is_string($string))
		{
			Sql_Object::set("lexer", new Sql_Lexer($string, 1));
			Sql_Object::get("lexer")->symbols = Sql_Object::get("symbols");
		}
		if(stristr($string,"union")) {
			$this->isUnion = true;
		}
	}

	/**
	 * setDialect
	 * @desc set a sql dialect
	 * @param string $dialect mysql|ansi
	 * @return void
	 */
	private static function setDialect($dialect) {

		if (in_array($dialect, Sql_Parser::$dialects)) {

			include dirname(__FILE__)
				. DIRECTORY_SEPARATOR
				. 'Sql_Dialect'
				. DIRECTORY_SEPARATOR .
				'Sql_Dialect'. ucfirst($dialect) . '.inc.php';

			Sql_Object::set("types", array_flip($dialect['types']));
			Sql_Object::set("functions", array_flip($dialect['functions']));
			Sql_Object::set("controlFlowFunctions", array_flip($dialect['controlFlowFunctions']));
			Sql_Object::set("operators", array_flip($dialect['operators']));
			Sql_Object::set("commands", array_flip($dialect['commands']));
			Sql_Object::set("synonyms", array_flip($dialect['synonyms']));
			Sql_Object::set("selected_keyword", array_flip($dialect['selected_keyword']));

			$symbols = array_merge(
				Sql_Object::get("types"),
				Sql_Object::get("operators"),
				Sql_Object::get("commands"),
				array_flip($dialect['reserved']),
				array_flip($dialect['conjunctions'])
				);
			Sql_Object::set("symbols", $symbols);

		} else {
			return Sql_Parser::raiseError('Unknown SQL dialect:'.$dialect, basename(__FILE__).' '.__LINE__);
		}
	}

	/**
	 * check if is error
	 *
	 * @param Exception $data
	 * @param string $code
	 * @return mixed
	 */
	public static function isError($data, $code = null)
	{
		if (is_a($data, 'Exception')) {
			if (is_null($code)) {
				return true;
			} elseif (is_string($code)) {
				return $data->getMessage() == $code;
			} else {
				return $data->getCode() == $code;
			}
		}
		return false;
	}

	/**
	 * exception
	 *
	 * @param string $message
	 * @return Exception
	 */
	public static function raiseError($message, $line=null) {
		$end = 0;
		if (Sql_Object::get("lexer")->string != '') {
			while ((Sql_Object::get("lexer")->lineBegin+$end < Sql_Object::get("lexer")->stringLen)
			&& (Sql_Object::get("lexer")->string{Sql_Object::get("lexer")->lineBegin+$end} != "\n")){
				++$end;
			}
		}

		$message = 'Parse error: '.$message.' on line '. (Sql_Object::get("lexer")->lineNo+1)."\n";
		$message .= substr(Sql_Object::get("lexer")->string, Sql_Object::get("lexer")->lineBegin, $end)." ($line)\n";
		$length = is_null(Sql_Object::token()) ? 0 : strlen(Sql_Object::get("lexer")->tokText);
		$message .= str_repeat(' ', abs(Sql_Object::get("lexer")->tokPtr - Sql_Object::get("lexer")->lineBegin - $length))."^";
		$message .= ' found: "'.Sql_Object::get("lexer")->tokText.'"';

		// replace by Tool_Debugger
		throw new Exception($message);
	}


	/**
	 * get token
	 *
	 */
	public static function getTok() {
		Sql_Object::set("token", Sql_Object::get("lexer")->lex());
	}

	public static function ungetTok() {
		Sql_Object::set("token", Sql_Object::get("lexer")->unget());
	}

	/**
	 * check type
	 *
	 * @return bool
	 */
	public static function isType() {
		return Sql_Object::has("types.".Sql_Object::token());
	}

	/**
	 * check end
	 *
	 * @return bool
	 */
	public static function isEnd() {
		return (( Sql_Object::lexer()->tokText == '*end of input*') ? true : false);
	}

	public static function isSelectedKeyword($token=null) {
        if($token) {
            return Sql_Object::has("selected_keyword.".$token);
        }
		return Sql_Object::has("selected_keyword.".Sql_Object::token());
	}

	/**
	 * check value
	 *
	 * @return bool
	 */
	public static function isVal() {
		return ((Sql_Object::token() == 'real_val') ||
		(Sql_Object::token() == 'int_val') ||
		(Sql_Object::token() == 'text_val') ||
		(Sql_Object::token() == 'null'));
	}

	/**
	 * check Control Flow Function
	 *
	 * @return bool
	 */
	public static function isControlFlowFunction() {
		return Sql_Object::has("controlFlowFunctions.".Sql_Object::token());
	}

	/**
	 * check function
	 *
	 * @return bool
	 */
	public static function isFunc() {
		return Sql_Object::has("functions.".Sql_Object::token());
	}

	/**
	 * check command
	 *
	 * @return bool
	 */
	public static function isCommand() {
		return Sql_Object::has("commands.".Sql_Object::token());
	}

	/**
	 * check reserved word
	 *
	 * @return bool
	 */
	public static function isReserved() {
		return Sql_Object::has("symbols.".Sql_Object::token());
	}

	/**
	 * check operator
	 *
	 * @return bool
	 */
	public static function isOperator() {
		return Sql_Object::has("operators.".Sql_Object::token());
	}

	/**
	 * processAlias
	 * @desc process an alias for a statement part
	 * @param array $opts options array
	 * @return array
	 */
	public static function processAlias($opts){

		$previousToken = Sql_Object::token();

		Sql_Parser::getTok();

		$previousTokenValue = Sql_Object::lexer()->tokText;

		if (Sql_Object::token() == ',' || Sql_Object::token() == 'from')
		{
			Sql_Object::lexer()->pushBack();
		}

		elseif (Sql_Object::token() == 'as' or Sql_Object::token()=='ident')
		{
			Sql_Parser::getTok();

			if (Sql_Object::token() == 'ident' )
			{
				if(Sql_Object::token() == 'as') {
					Sql_Object::lexer()->pushBack();
				}
				$opts['Alias'] = Sql_Object::lexer()->tokText;
			}
			elseif (Sql_Object::token() == ',' )
			{
				if($previousToken=='as') {
					$opts['Alias'] = $previousTokenValue;
					Sql_Object::lexer()->pushBack();
				} else {
					$opts['Alias'] = Sql_Object::lexer()->tokText;
				}
			}
			else
			{
				return Sql_Parser::raiseError('Expected column alias', basename(__FILE__).' '.__LINE__);
			}
		}
		else
		{
			if (Sql_Object::token() == 'ident' )
			{
				$opts['Alias'] = Sql_Object::lexer()->tokText;
			}
			else
			{
				return Sql_Parser::raiseError('Expected column alias, from or comma', basename(__FILE__).' '.__LINE__);
			}
		}
		return $opts;
	}

	/**
	 * getParams
	 * @desc parses statement value part into array
	 * @return array
	 */
	public static function getParams() {

		$values = array();
		$types = array();

		while (Sql_Object::token() != ')') {

			Sql_Parser::getTok();

			if (Sql_Parser::isVal() || (Sql_Object::token() == 'ident'))
			{
				$values[] = Sql_Object::lexer()->tokText;
				$types[] = Sql_Object::token();
			}
			elseif (Sql_Object::token() == ')')
			{
				return false;
			}
			else
			{
				return Sql_Parser::raiseError('Expected a value', basename(__FILE__).' '.__LINE__);
			}

			Sql_Parser::getTok();

			if ((Sql_Object::token() != ',') && (Sql_Object::token() != ')'))
			{
				return Sql_Parser::raiseError('Expected , or )', __LINE__);
			}
		}
		return array("values" => $values, "types" => $types);
	}

	/**
	 * parseSearchClause
	 * @desc parses conditional statement into array
	 * @param bool $subSearch
	 * @return array
	 */
	public static function parseSearchClause($subSearch = false)
	{

		$clause = array();
		// parse the first argument

		Sql_Parser::getTok();
        switch(Sql_Object::lexer()->lookaheadToken()) {
            case "limit":
            case "order":
            case "group":
            case "having":
                // where 1 => where 1=1
                $clause['Left']['Value'] = Sql_Object::lexer()->tokText;
                $clause['Left']['Type'] = Sql_Object::token();
                $clause['Right']['Value']["Op"] = "=";
    			$clause['Right']['Value'] = Sql_Object::lexer()->tokText;
    			$clause['Right']['Type'] = Sql_Object::token();
                Sql_Parser::getTok();
                return $clause;
        }

		if (Sql_Object::token() == 'not') {
			$clause['Neg'] = true;
			Sql_Parser::getTok();
		}

		$foundSubclause = false;
		if (Sql_Object::token() == '(') {

			$clause['Left']['Value'] = Sql_Parser::parseSearchClause(true);
			$clause['Left']['Type'] = 'subclause';
			if (Sql_Object::token() != ')' and Sql_Object::token()!="ident") {
				return Sql_Parser::raiseError('Expected ")"', basename(__FILE__).' '.__LINE__);
			}
			$foundSubclause = true;

		} else if (Sql_Parser::isReserved()) {
			return Sql_Parser::raiseError('Expected a column name or value', basename(__FILE__).' '.__LINE__);
		} else {
			$clause['Left']['Value'] = Sql_Object::lexer()->tokText;
			$clause['Left']['Type'] = Sql_Object::token();
		}

		// parse the operator
		if (!$foundSubclause) {

			Sql_Parser::getTok();

			// added 2008-12-20 => sql condition where 1 now works
			if(Sql_Parser::isEnd()) {
				return $clause;
			} else if(!Sql_Parser::isOperator()) {
				return Sql_Parser::raiseError('Expected an operator', basename(__FILE__).' '.__LINE__);
			}

			$clause['Op'] = Sql_Object::lexer()->tokText;

			if(Sql_Parser::isOperator()) { // important when using back-ticks
				Sql_Parser::getTok();
			}

			switch ( strtolower( $clause['Op'] ) ) {
				// chg 2008-12-19
				case 'between':

					Sql_Parser::getTok();

					switch(Sql_Object::token())
					{
						case "int_val":
						case "real_val":
							$clause['Right']['Value']["Left"]["Value"] = Sql_Object::lexer()->tokText;
							$clause['Right']['Value']["Left"]["Type"] = Sql_Object::token();
							$clause['Right']['Type'] = Sql_Object::token();

							Sql_Parser::getTok();

							if(!Sql_Parser::isOperator()){
								return Sql_Parser::raiseError('Expected an operator', basename(__FILE__).' '.__LINE__);
							} else {
								$clause['Right']['Value']["Op"] = Sql_Object::lexer()->tokText;

								Sql_Parser::getTok();

								switch(Sql_Object::token()){
									case "int_val":
									case "real_val":
										$clause['Right']['Value']["Right"]["Value"] = Sql_Object::lexer()->tokText;
										$clause['Right']['Value']["Right"]["Type"] = Sql_Object::token();
									break;
									default:
										return Sql_Parser::raiseError('No subclause supported at the moment', basename(__FILE__).' '.__LINE__);
								}
							}

							break;
						default:
							return Sql_Parser::raiseError('No subclause supported at the moment', basename(__FILE__).' '.__LINE__);
					} // endswitch
					break;
				case 'is':
					// parse for 'is' operator
					if (Sql_Object::token() == 'not') {
						$clause['Neg'] = true;
						Sql_Parser::getTok();
					}
					if (Sql_Object::token() != 'null') {
						return Sql_Parser::raiseError('Expected "null"', basename(__FILE__).' '.__LINE__);
					}
					$clause['Right']['Value'] = '';
					$clause['Right']['Type'] = Sql_Object::token();
					break;
				case 'not':
					// parse for 'not in' operator
					if (Sql_Object::token() != 'in') {
						return Sql_Parser::raiseError('Expected "in"', basename(__FILE__).' '.__LINE__);
					}
					$clause['Op'] = strtoupper( Sql_Object::token() );
					$clause['Neg'] = true;
					Sql_Parser::getTok();
				case 'in':
					// parse for 'in' operator
					if (Sql_Object::token() != '(') {
						return Sql_Parser::raiseError('Expected "("', basename(__FILE__).' '.__LINE__);
					}

					// read the subset
					Sql_Parser::getTok();
					// is this a subselect?
					if (Sql_Object::token() == 'select') {
						$clause['Right']['Value'] = Sql_ParserSelect::parse(true);
						$clause['Right']['Type'] = 'command';
					} else {
						Sql_Object::lexer()->pushBack();
						// parse the set
						$result = $this->getParams($clause['Right']['Value'], $clause['Right']['Type']);
						if (Sql_Parser::isError($result)) {
							return $result;
						}
					}

					if (Sql_Object::token() != ')') {
						return Sql_Parser::raiseError('Expected ")"', basename(__FILE__).' '.__LINE__);
					}
					$clause["Right"]["Value"] = $result["values"];
					$clause["Right"]["Type"] = $result["types"];
					break;
				case 'and':
				case 'or':
					Sql_Object::lexer()->unget();
					break;
				default:
					// parse for in-fix binary operators

					if (Sql_Parser::isReserved()) {
						return Sql_Parser::raiseError('Expected a column name or value', basename(__FILE__).' '.__LINE__);
					}
					if (Sql_Object::token() == '(') {
						$clause['Right']['Value'] = Sql_Parser::parseSearchClause(true);
						$clause['Right']['Type'] = 'subclause';

						// begin added on 2008-12-13 process subselect on conditional right value
						if(Sql_Parser::isCommand() and Sql_Object::token()=='select' ){
							$result = array();
							$result['Left']['Value'] = $clause['Left']['Value'];
							$result['Left']['Type'] = $clause['Left']['Type'];
							$result['Op'] = $clause['Op'];
							$result['Right']['Value'] = Sql_ParserSelect::doParse(true);
							$result['Right']['Type'] = 'subselect';
							return $result;
						}
						// end added on 2008-12-13 process subselect on conditional right value

						Sql_Parser::getTok();

						if (Sql_Object::token() != ')') {
							return Sql_Parser::raiseError('Expected ")"', __FILE__.' at '.__LINE__);
						}
					} else {
						$clause['Right']['Value'] = Sql_Object::lexer()->tokText;
						$clause['Right']['Type'] = Sql_Object::token();
					}
			}
		}

		Sql_Parser::getTok();

		if ((Sql_Object::token() == 'and') || (Sql_Object::token() == 'or')) {
			$op = Sql_Object::token();
			$subClause = Sql_Parser::parseSearchClause($subSearch);
			if (Sql_Parser::isError($subClause)) {
				return $subClause;
			} else {
				$clause = array('Left' => $clause, 'Op' => $op, 'Right' => $subClause);
			}
		} else {
			Sql_Object::lexer()->unget();
		}
		return $clause;
	}

	public static function parseColumns(array $tree) {

		if (Sql_Object::token() == '*')
		{
			$tree['ColumnNames'][] = '*';

			Sql_Parser::getTok();

		}
		elseif (
			Sql_Object::token() == 'ident' or
			Sql_Parser::isFunc() or
			Sql_Parser::isControlFlowFunction()
		) {

			while (Sql_Object::token() != 'from')
			{
				if(Sql_Parser::isFunc())
				{
					if (!isset($tree['Quantifier'])) {

						$result = Sql_ParserFunction::parse();

						if (Sql_Parser::isError($result)) {
							return $result;
						}
						$tree['Function'][] = $result;

						Sql_Parser::getTok();

						if (Sql_Object::token() == 'as') {

							Sql_Parser::getTok();

							if (Sql_Object::token() == 'ident' ) {
								$columnAlias = Sql_Object::lexer()->tokText;
							} else {
								return Sql_Parser::raiseError('Expected column alias', __LINE__);
							}
						} else {
							$columnAlias = '';
						}
					} else {
						return Sql_Parser::raiseError('Cannot use "'.$tree['Quantifier'].'" with '.Sql_Object::token(), __LINE__);
					}
				}
				elseif(Sql_Parser::isControlFlowFunction())
				{
					if (!isset($tree['Quantifier'])) {

						$result = Sql_ParserFlow::parse();

						if (Sql_Parser::isError($result)) {
							return $result;
						}
						$tree['Function'][] = $result;
						if(isset($result["Bridge"])){
							$tree["Bridge"] = true;
						}
						Sql_Parser::getTok();

						if (Sql_Object::token() == 'as') {
							Sql_Parser::getTok();
							if (Sql_Object::token() == 'ident' ) {
								$columnAlias = Sql_Object::lexer()->tokText;
							} else {
								return Sql_Parser::raiseError('Expected column alias', __LINE__);
							}
						} else {
							$columnAlias = '';
						}
					}
				}
				elseif (Sql_Object::token() == 'ident')
				{

					$prevTok = Sql_Object::token();

					$prevTokText = Sql_Object::lexer()->tokText;

                    // added due to Alireza Eliaderani's mail from 2008-01-16
        		    $columnDatabase = false;
					if(strpos($prevTokText,".")>0)
					{
						$arrPrevTokText = explode(".",$prevTokText);
						switch(count($arrPrevTokText)) {
						    case 2:
        						$columnTable = $arrPrevTokText[0];
        						$columnName = $arrPrevTokText[1];
        						break;
        					case 3:
        						$columnDatabase = $arrPrevTokText[0];
        						$columnTable = $arrPrevTokText[1];
        						$columnName = $arrPrevTokText[2];
        						break;
        				}

						Sql_Parser::getTok();

						if(Sql_Object::token()=='*'){
						    $columnName .= '*';
						}
						$prevTok = Sql_Object::token();
						$prevTokText = Sql_Object::lexer()->tokText;
					}
					else
					{

						Sql_Parser::getTok();

						if (Sql_Object::token() == '.') {
							$columnTable = $prevTokText;
							Sql_Parser::getTok();
							$prevTok = Sql_Object::token();
							$prevTokText = Sql_Object::lexer()->tokText;
						} else {
							$columnTable = '';
						}

						// added 2008-12-19
						if(Sql_Object::token()=='*'){
							$prevTokText .= '*';
						}

						if ($prevTok == 'ident') {
							$columnName = $prevTokText;
						} else {
							return Sql_Parser::raiseError('Expected column name', __LINE__);
						}
					}
					if (Sql_Object::token() == 'as') {
						Sql_Parser::getTok();
						if (Sql_Object::token() == 'ident' ) {
							$columnAlias = Sql_Object::lexer()->tokText;
						} else {
							return Sql_Parser::raiseError('Expected column alias', __LINE__);
						}
					} elseif (Sql_Object::token() == 'ident') {
						$columnAlias = Sql_Object::lexer()->tokText;
					} else {
						$columnAlias = '';
					}

	                if(!empty($columnDatabase)) $tree['ColumnDatabases'][] = $columnDatabase;
					$tree['ColumnTables'][] = $columnTable;
					$tree['ColumnNames'][] = $columnName;
					$tree['ColumnAliases'][] = $columnAlias;
                    if(isset($tree['ColumnTables']) and count($tree['ColumnTables']) ) {
                        $tree['ColumnTableAliases'] = array();
                    }

					if (Sql_Object::token() != 'from') {
						Sql_Parser::getTok();
					}

					if (Sql_Object::token() == ',') {
						Sql_Parser::getTok();
					}

				}
				elseif (Sql_Object::token() == ',')
				{
					Sql_Parser::getTok();
				}
				else
				{
					return Sql_Parser::raiseError('Unexpected token "'.Sql_Object::token().'"', __LINE__);
				}
			}

		}
		else
		{
			return Sql_Parser::raiseError('Expected columns or a set function', __LINE__);
		}

		return $tree;
	}

	/**
	 * parse
	 *
	 * @param string $string receives a sql string
	 * @desc identifies action which has to be processed
	 * @return array
	 */
	public function parse($string = null)
	{
		if (is_string($string)) {
			// Initialize the Lexer with a 3-level look-back buffer
			Sql_Object::set("lexer", new Sql_Lexer($string, 3));
			Sql_Object::get("lexer")->symbols = Sql_Object::get("symbols");
		} else {
			if (!is_object(Sql_Object::get("lexer"))) {
				return Sql_Parser::raiseError('No initial string specified', basename(__FILE__).' '.__LINE__);
			}
		}

		// get action
		Sql_Parser::getTok();
		$token = Sql_Object::token();

		switch ($token) {
			case null:
				// null == end of string
				return Sql_Parser::raiseError('Nothing to do', basename(__FILE__).' '.__LINE__);
			case 'select':
				if($this->isUnion) {
					$token = "union";
				}
			case 'update':
			case 'replace':
			case 'insert':
			case 'delete':
			case 'create':
				$className = __CLASS__.ucfirst($token);
				return call_user_func(array($className, "parse"));
			default:
				return Sql_Parser::raiseError('Unknown action :'.Sql_Object::token(), basename(__FILE__).' '.__LINE__);
		}
	}

}

