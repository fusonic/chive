<?php

/**
 * PHP ctype compatibility functions. See the PHP ctype module for more
 * information on usage.
 *
 * @author John Millaway
 * @author Brent Cook
 * @author Thomas Schaefer
 * 
 * Note: These functions expect an integer argument, like the C versions
 * To use with a PHP character, use ord($c). These functions do not support
 * string arguments like their PHP extension counterparts
 */
if (!extension_loaded('ctype')) {
    function ctype_alnum($c) {
        static $ctype__;
        return ($ctype__[$c] & 7); // (1 | 2 | 4)
    }
    function ctype_alpha($c) {
        static $ctype__;
        return ($ctype__[$c] & 3); // (1 | 2)
    }
    function ctype_cntrl($c) {
        static $ctype__;
        return ($ctype__[$c] & 40);
    }
    function ctype_digit($c) {
        static $ctype__;
        return ($ctype__[$c] & 4);
    }
    function ctype_graph($c) {
        static $ctype__;
        return ($ctype__[$c] & 27); // (20 | 1 | 2 | 4)
    }
    function ctype_lower($c) {
        static $ctype__;
        return ($ctype__[$c] & 2);
    }
    function ctype_print($c) {
        static $ctype__;
        return ($ctype__[$c] & 227); // (20 | 1 | 2 | 4 | 200)
    }
    function ctype_punct($c) {
        static $ctype__;
        return ($ctype__[$c] & 20);
    }
    function ctype_space($c) {
        static $ctype__;
        return ($ctype__[$c] & 10);
    }
    function ctype_upper($c) {
        static $ctype__;
        return ($ctype__[$c] & 1);
    }
    function ctype_xdigit($c) {
        static $ctype__;
        return ($ctype__[$c] & 104); // (100 | 4));
    }
    $ctype__ = array(
    	  32,32,32,32,32,32,32,32,32,40,40,40,40,40,32,32,32,32,32,32,32,32,32,
          32,32,32,32,32,32,32,32,32,-120,16,16,16,16,16,16,16,16,16,16,16,16,
          16,16,16,4,4,4,4,4,4,4,4,4,4,16,16,16,16,16,16,16,65,65,65,65,65,65,
          1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,16,16,16,16,16,16,66,66,66,
          66,66,66,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,16,16,16,16,32,0,0,
          0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
          0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
          0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
          0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
}

// {{{ token definitions
// variables: 'ident', 'sys_var'
// values:    'real_val', 'text_val', 'int_val', null
// }}}

final class Sql_Lexer
{
	// array of valid tokens for the lexer to recognize
	// format is 'token literal'=>TOKEN_VALUE
	public $symbols = array();

	// {{{ instance variables
	public $tokPtr = 0;
	public $tokStart = 0;
	public $tokLen = 0;
	public $tokText = '';
	public $lineNo = 0;
	public $lineBegin = 0;
	public $string = '';
	public $stringLen = 0;

	// Will not be altered by skip()
	public $tokAbsStart = 0;
	public $skipText = '';

	// Provide lookahead capability.
	public $lookahead = 3;
	// Specify how many tokens to save in tokenStack, so the
	// token stream can be pushed back.
	public $tokenStack = array();
	public $stackPtr = 0;
	// }}}

	// {{{ incidental functions
	public function __construct($string = '', $lookahead=0)
	{
		$this->string = $string;
		$this->stringLen = strlen($string);
		$this->lookahead = $lookahead;
	}

	public function get() {
		++$this->tokPtr;
		++$this->tokLen;
		return ($this->tokPtr <= $this->stringLen) ? $this->string{$this->tokPtr - 1} : null;
	}

	public function unget() {
		--$this->tokPtr;
		--$this->tokLen;
	}

	public function skip() {
		++$this->tokStart;
		return ($this->tokPtr != $this->stringLen) ? $this->string{$this->tokPtr++} : '';
	}

	public function revert() {
		$this->tokPtr = $this->tokStart;
		$this->tokLen = 0;
	}

	public function isCompop($c) {
		return (($c == '<') || ($c == '>') || ($c == '=') || ($c == '!'));
	}
	// }}}

	// {{{ pushBack()
	/*
	* Push back a token, so the very next call to lex() will return that token.
	* Calls to this function will be ignored if there is no lookahead specified
	* to the constructor, or the pushBack() function has already been called the
	* maximum number of token's that can be looked ahead.
	*/
	public function pushBack()
	{
		if($this->lookahead>0 && count($this->tokenStack)>0 && $this->stackPtr>0) {
			$this->stackPtr--;
		}
	}
	// }}}
	
	// {{{ lex()
	public function lex()
	{
		if($this->lookahead>0) {
			// The stackPtr, should always be the same as the count of
			// elements in the tokenStack.  The stackPtr, can be thought
			// of as pointing to the next token to be added.  If however
			// a pushBack() call is made, the stackPtr, will be less than the
			// count, to indicate that we should take that token from the
			// stack, instead of calling nextToken for a new token.
			if ($this->stackPtr<count($this->tokenStack)) {

				$this->tokText = $this->tokenStack[$this->stackPtr]['tokText'];
				$this->skipText = $this->tokenStack[$this->stackPtr]['skipText'];
				$token = $this->tokenStack[$this->stackPtr]['token'];

				// We have read the token, so now iterate again.
				$this->stackPtr++;
				return $token;

			} else {

				// If $tokenStack is full (equal to lookahead), pop the oldest
				// element off, to make room for the new one.

				if ($this->stackPtr == $this->lookahead) {
					// For some reason array_shift and
					// array_pop screw up the indexing, so we do it manually.
					for($i=0; $i<(count($this->tokenStack)-1); $i++) {
						$this->tokenStack[$i] = $this->tokenStack[$i+1];
					}

					// Indicate that we should put the element in
					// at the stackPtr position.
					$this->stackPtr--;
				}

				$token = $this->nextToken();
				$this->tokenStack[$this->stackPtr] =
				array('token'=>$token,
                      'tokText'=>$this->tokText,
                      'skipText'=>$this->skipText);
				$this->stackPtr++;
				return $token;
			}
		}
		else
		{
			return $this->nextToken();
		}
	}
	// }}}

	public function getToken() {
		return array("type"=>$this->token,"value"=>$this->tokText,"line"=>$this->lineNo,"charno"=>$this->tokAbsStart);	
	}
	
    public function hasNextToken()
    {
    	$tokPtr = $this->tokPtr;
    	$tokStart = $this->tokStart;
    	$tokText = $this->tokText;
    	$tokLen = $this->tokLen;
    	$tokAbsStart = $this->tokAbsStart;
    	
    	$this->lex();    	
    	
    	$tok = ($this->tokText!="*end of input*")?true:false;
    	
    	if($tok){
	    	$this->tokPtr = $tokPtr;
	    	$this->tokStart = $tokStart;
	    	$this->tokText = $tokText;
	    	$this->tokAbsStart = $tokAbsStart;
	    	$this->tokStack=null;
    	} 
    	return $tok;
    }
    
	/**
	* nextTokenIs
	* @param string $token
	* @param bool $return
	* @return mixed
	*/
    public function nextTokenIs($token , $return=false)
    {
    	$tokPtr = $this->tokPtr;
    	$tokStart = $this->tokStart;
    	$tokText = $this->tokText;
    	$tokLen = $this->tokLen;
    	$tokAbsStart = $this->tokAbsStart;
    	
    	$nextToken = $this->nextToken();    	
    	$tok = ($nextToken==$token)?true:false;

		if($return and $tok) {
    	    return $nextToken;
    	}
    	
    	if($tok) {
	    	$this->tokPtr = $tokPtr;
	    	$this->tokStart = $tokStart;
	    	$this->tokText = $tokText;
	    	$this->tokAbsStart = $tokAbsStart;
	    	$this->tokStack=null;
    	} 
    	return $tok;
    }

    public function lookaheadToken()
    {
    	$tokPtr = $this->tokPtr;
    	$tokStart = $this->tokStart;
    	$tokText = $this->tokText;
    	$tokLen = $this->tokLen;
    	$tokAbsStart = $this->tokAbsStart;
    	
    	$nextToken = $this->nextToken();    	
    	$this->tokPtr = $tokPtr;
    	$this->tokStart = $tokStart;
    	$this->tokText = $tokText;
    	$this->tokAbsStart = $tokAbsStart;
    	$this->tokStack=null;
    	return $nextToken;
    }

    public function tokenIsNot($token)
    {
    	$tokens = is_array($token) ? $token : array($token);
    	$check=true;
    	foreach($tokens as $tok) {
    		if(Sql_Object::token()==$tok){
    			$check=false;
    		}
    	}
    	return $check;
    }

    public function tokenIs($token)
    {
    	$tokens = is_array($token) ? $token : array($token);
    	$check=false;
    	foreach($tokens as $tok) {
    		if(Sql_Object::token()==$tok){
    			$check=true;
    		}
    	}
    	return $check;
    }

	/**
	* nextTextIs
	* @param string $token
	* @param bool $return
	* @return mixed
	*/
    public function nextTextIs($token , $return=false)
    {
    	$tokPtr = $this->tokPtr;
    	$tokStart = $this->tokStart;
    	$tokText = $this->tokText;
    	$tokLen = $this->tokLen;
    	$tokAbsStart = $this->tokAbsStart;
    	
    	$this->nextToken();
    	    	
    	$tok = ($this->tokText==$token)?true:false;    	
    	if($return and $tok) {
    	    return $this->tokText;
    	}
    	
    	if($tok){
	    	$this->tokPtr = $tokPtr;
	    	$this->tokStart = $tokStart;
	    	$this->tokText = $tokText;
	    	$this->tokAbsStart = $tokAbsStart;
	    	$this->tokStack=null;
    	} 
    	return $tok;
    }

	// {{{ nextToken()
	public function nextToken()
	{
		if ($this->string == '') return;
		$state = 0;
		$this->tokAbsStart = $this->tokStart;

		while (true){
			//echo "State: $state, Char: $c\n";
			switch($state) {
				// {{{ State 0 : Start of token
				case 0:
					$this->tokPtr = $this->tokStart;
					$this->tokText = '';
					$this->tokLen = 0;
					$c = $this->get();

					if (is_null($c)) { // End Of Input
						$state = 1000;
						break;
					}

					while (($c == ' ') || ($c == "\t")
					|| ($c == "\n") || ($c == "\r")) {
						if ($c == "\n" || $c == "\r") {
							// Handle MAC/Unix/Windows line endings.
							if($c == "\r") {
								$c = $this->skip();

								// If not DOS newline
								if($c != "\n")
								$this->unget();
							}
							++$this->lineNo;
							$this->lineBegin = $this->tokPtr;
						}

						$c = $this->skip();
						$this->tokLen = 1;
					}

					// Escape quotes and backslashes
					if ($c == '\\') {
						$t = $this->get();
						if ($t == '\'' || $t == '\\' || $t == '"') {
							$this->tokText = $t;
							$this->tokStart = $this->tokPtr;
							return $this->tokText;
						} else {
							$this->unget();

							// Unknown token.  Revert to single char
							$state = 999;
							break;
						}
					}

					if (($c == '\'') || ($c == '"')) { // text string
						$quote = $c;
						$state = 12;
						break;
					}

					if ($c == '_') { // system variable
						$state = 18;
						break;
					}

					if (ctype_alpha(ord($c)) || ($c == '`')) { // keyword or ident
						$state = 1;
						break;
					}

					if (ctype_digit(ord($c))) { // real or int number
						$state = 5;
						break;
					}

					if ($c == '.') {
						$t = $this->get();
						if ($t == '.') { // ellipsis
							if ($this->get() == '.') {
								$this->tokText = '...';
								$this->tokStart = $this->tokPtr;
								return $this->tokText;
							} else {
								$state = 999;
								break;
							}
						} else if (ctype_digit(ord($t))) { // real number
							$this->unget();
							$state = 7;
							break;
						} else { // period
							$this->unget();
						}
					}

					if ($c == '#') { // Comments
						$state = 14;
						break;
					}
					if ($c == '-') {
						$t = $this->get();
						if ($t == '-') {
							$state = 14;
							break;
						} elseif ($t == ' ') {
							$state = 15;
							break;
						} elseif (is_numeric( $t )) {
							$state = 15;
							break;
						} elseif (ord($t)==32 ) {
							$state = 16;
							break;
						} else { // negative number
							$this->unget();
							$state = 5;
							break;
						}
					}

					if ($c == '+') {
						$t = $this->get();
						if ($t == '+') {
							$state = 14;
							break;
						} elseif ($t == ' ') {
							$state = 15;
							break;
						} elseif (is_numeric( $t )) {
							$state = 15;
							break;
						}
					}

					if ($this->isCompop($c)) { // comparison operator
						$state = 10;
						break;
					}
					// Unknown token.  Revert to single char
					$state = 999;
					break;
					// }}}

					// {{{ State 1 : Incomplete keyword or ident
				case 1:
					$c = $this->get();
					if (ctype_alnum(ord($c)) || ($c == '_') || ($c == '.') || ($c == '`')) {
						$state = 1;
						break;
					}
					$state = 2;
					break;
					// }}}

					/* {{{ State 2 : Complete keyword or ident */
				case 2:
					$this->unget();
					$this->tokText = substr($this->string, $this->tokStart,
					$this->tokLen);

					$testToken = strtolower($this->tokText);
					if (isset($this->symbols[$testToken])) {

						$this->skipText = substr($this->string, $this->tokAbsStart,
						$this->tokStart-$this->tokAbsStart);
						$this->tokStart = $this->tokPtr;
						return $testToken;
					} else {
						$this->skipText = substr($this->string, $this->tokAbsStart,
						$this->tokStart-$this->tokAbsStart);
						$this->tokStart = $this->tokPtr;
						return 'ident';
					}
					break;
					// }}}

					// {{{ State 5: Incomplete real or int number
				case 5:
					$c = $this->get();
					if (ctype_digit(ord($c))) {
						$state = 5;
						break;
					} else if ($c == '.') {
						$t = $this->get();
						if($t == '.') { // ellipsis
							$this->unget();
						} else { // real number
							$state = 7;
							break;
						}
					} else if(ctype_alpha(ord($c))) { // number must end with non-alpha character
						$state = 999;
						break;
					} else {
						// complete number
						$state = 6;
						break;
					}
					// }}}

					// {{{ State 6: Complete integer number
				case 6:
					$this->unget();
					$this->tokText = intval(substr($this->string, $this->tokStart,
					$this->tokLen));
					$this->skipText = substr($this->string, $this->tokAbsStart,
					$this->tokStart-$this->tokAbsStart);
					$this->tokStart = $this->tokPtr;
					return 'int_val';
					break;
					// }}}

					// {{{ State 7: Incomplete real number
				case 7:
					$c = $this->get();

					/* Analogy Start */
					if ($c == 'e' || $c == 'E') {
						$state = 15;
						break;
					}
					/* Analogy End   */

					if (ctype_digit(ord($c))) {
						$state = 7;
						break;
					}
					$state = 8;
					break;
					// }}}

					// {{{ State 8: Complete real number */
				case 8:
					$this->unget();
					$this->tokText = floatval(substr($this->string, $this->tokStart,
					$this->tokLen));
					$this->skipText = substr($this->string, $this->tokAbsStart,
					$this->tokStart-$this->tokAbsStart);
					$this->tokStart = $this->tokPtr;
					return 'real_val';
					// }}}

					// {{{ State 10: Incomplete comparison operator
				case 10:
					$c = $this->get();
					if ($this->isCompop($c))
					{
						$state = 10;
						break;
					}
					$state = 11;
					break;
					// }}}

					// {{{ State 11: Complete comparison operator
				case 11:
					$this->unget();
					$this->tokText = substr($this->string, $this->tokStart,
					$this->tokLen);
					if($this->tokText) {
						$this->skipText = substr($this->string, $this->tokAbsStart,
						$this->tokStart-$this->tokAbsStart);
						$this->tokStart = $this->tokPtr;
						return $this->tokText;
					}
					$state = 999;
					break;
					// }}}

					// {{{ State 12: Incomplete text string
				case 12:
					$bail = false;
					while (!$bail) {
						switch ($this->get()) {
							case '':
								$this->tokText = null;
								$bail = true;
								break;
							case "\\":
								if (!$this->get()) {
									$this->tokText = null;
									$bail = true;
								}
								//$bail = true;
								break;
							case $quote:
								$this->tokText = stripslashes(substr($this->string,
								($this->tokStart+1), ($this->tokLen-2)));
								$bail = true;
								break;
						}
					}
					if (!is_null($this->tokText)) {
						$state = 13;
						break;
					}
					$state = 999;
					break;
					// }}}

					// {{{ State 13: Complete text string
							case 13:
								$this->skipText = substr($this->string, $this->tokAbsStart,
								$this->tokStart-$this->tokAbsStart);
								$this->tokStart = $this->tokPtr;
								return 'text_val';
								break;
								// }}}

								// {{{ State 14: Comment
							case 14:
								$c = $this->skip();
								if ($c == "\n" || $c == "\r" || $c == "") {
									// Handle MAC/Unix/Windows line endings.
									if ($c == "\r") {
										$c = $this->skip();
										// If not DOS newline
										if ($c != "\n") {
											$this->unget();
										}
									}

									if ($c != "") {
										++$this->lineNo;
										$this->lineBegin = $this->tokPtr;
									}

									// We need to skip all the text.
									$this->tokStart = $this->tokPtr;
									$state = 0;
								} else {
									$state = 14;
								}
								break;
								// }}}

								// {{{ State 15: Exponent Sign in Scientific Notation
							case 15:
								$c = $this->get();
								if($c == '-' || $c == '+' || $c == '/'  || $c == '*') {
									$state = 16;
									break;
								}
								$state = 999;
								break;
								// }}}

								// {{{ state 16: Exponent Value-first digit in Scientific Notation
							case 16:
								$c = $this->get();
								if (ctype_digit(ord($c))) {
									$state = 17;
									break;
								}
								$state = 999;  // if no digit, then token is unknown
								break;
								// }}}

								// {{{ State 17: Exponent Value in Scientific Notation
							case 17:
								$c = $this->get();
								if (ctype_digit(ord($c))) {
									$state = 17;
									break;
								}
								$state = 8;  // At least 1 exponent digit was required
								break;
								// }}}

								// {{{ State 18 : Incomplete System Variable
							case 18:
								$c = $this->get();
								if (ctype_alnum(ord($c)) || $c == '_') {
									$state = 18;
									break;
								}
								$state = 19;
								break;
								// }}}

								// {{{ State 19: Complete Sys Var
							case 19:
								$this->unget();
								$this->tokText = substr($this->string, $this->tokStart,
								$this->tokLen);
								$this->skipText = substr($this->string, $this->tokAbsStart,
								$this->tokStart-$this->tokAbsStart);
								$this->tokStart = $this->tokPtr;
								return 'sys_var';
								// }}}

								// {{{ State 999 : Unknown token.  Revert to single char
							case 999:
								$this->revert();
								$this->tokText = $this->get();
								$this->skipText = substr($this->string, $this->tokAbsStart,
								$this->tokStart-$this->tokAbsStart);
								$this->tokStart = $this->tokPtr;
								return $this->tokText;
								// }}}

								// {{{ State 1000 : End Of Input
							case 1000:
								$this->tokText = "*end of input*";
								$this->skipText = substr($this->string, $this->tokAbsStart,
								$this->tokStart-$this->tokAbsStart);
								$this->tokStart = $this->tokPtr;
								return null;
								// }}}
			}
		}
	}
	// }}}
}
