editAreaLoader.load_syntax["sql"] = {
	'COMMENT_SINGLE' : {1 : '--', 2:'#'}
	,'COMMENT_MULTI' : {'/*' : '*/'}
	,'QUOTEMARKS' : {1: "'", 2: '"', 3: '`'}
	,'KEYWORD_CASE_SENSITIVE' : false
	,'KEYWORDS' : {
		'statements' : [
			'select', 'SELECT', 'where', 'order', 'by','set',
			'insert', 'from', 'update', 'grant', 'left join', 'right join', 
            'union', 'group', 'having', 'limit', 'alter', 'LIKE','IN','CASE','CREATE','VIEW','add', 'after', 'aggregate', 
			'alias', 'all', 'and', 'as', 'authorization', 'between', 'by', 'cascade', 'cache', 'cache', 'called', 'case', 
			'charset', 'check', 'column', 'comment', 'constraint', 'createdb', 'createuser', 'cycle', 'database', 'default',
			 'deferrable', 'deferred', 'diagnostics', 'distinct', 'domain', 'each', 'else', 'elseif', 'elsif', 'encrypted', 
			 'engine', 'except', 'exception', 'for', 'foreign', 'from', 'full', 'function', 'get', 'group', 'having', 'if', 
			 'immediate', 'immutable', 'in', 'increment', 'initially', 'increment', 'index', 'inherits', 'inner', 'input',
			  'intersect', 'into', 'invoker', 'is', 'join', 'key', 'language', 'left', 'like', 'limit', 'local', 'loop', 'match',
			   'maxvalue', 'minvalue', 'natural', 'nextval', 'no', 'nocreatedb', 'nocreateuser', 'not', 'of', 'offset',
			    'oids', 'on', 'only', 'operator', 'or', 'order', 'outer', 'owner', 'partial', 'password', 'perform', 'plpgsql',
				 'primary', 'record', 'references', 'replace', 'restrict', 'return', 'returns', 'right', 'row', 'rule', 'schema',
				  'security', 'sequence', 'session', 'sql', 'stable', 'statistics', 'sum', 'table', 'temp', 'temporary', 'then',
				   'time', 'to', 'transaction', 'trigger', 'type', 'unencrypted', 'union', 'unique', 'user', 'using', 'valid', 'value', 
			       'values', 'view', 'volatile', 'when', 'where', 'with', 'without', 'zone'
		]
		,'reserved' : [
			'binary','null', 'enum', 'int', 'boolean', 'add', 'varchar','bigint', 'bigserial', 'bit', 'bytea', 'char', 'character', 
			'cidr', 'circle', 'date', 'datetime', 'decimal', 'double', 'float4', 'float8', 'inet', 'int2', 'int4', 'int8', 
			'integer', 'interval', 'line', 'lseg', 'macaddr', 'mediumint', 'money', 'numeric', 'oid', 'path', 'point', 'polygon', 
			'precision', 'real', 'refcursor', 'serial', 'serial4', 'serial8', 'smallint', 'text', 'timestamp', 'varbit'
    
		]
		,'functions' : [
   'ABS','ACOS','ADDDATE','ADDTIME','AES_DECRYPT','AES_ENCRYPT','ASCII','ASIN','ATAN2 ATAN','ATAN','AVG','BENCHMARK','DISTINCT','BIN','BIT_AND','BIT_COUNT','BIT_LENGTH','BIT_OR','BIT_XOR','CAST','CEILING CEIL','CHAR_LENGTH','CHAR',
'CHARACTER_LENGTH','CHARSET','COALESCE','COERCIBILITY','COLLATION','COMPRESS','CONCAT_WS','CONCAT','CONNECTION_ID','CONV','CONVERT_TZ','COS','COT','COUNT','CRC32','CURDATE','CURRENT_DATE','CURRENT_TIME','CURRENT_TIMESTAMP','CURRENT_USER','CURTIME','DATABASE','DATE_ADD','DATE_FORMAT','DATE_SUB','DATE','DATEDIFF','DAY','DAYNAME','DAYOFMONTH',
'DAYOFWEEK','DAYOFYEAR','DECODE','DEFAULT','DEGREES','DES_DECRYPT','DES_ENCRYPT','ELT','ENCODE','ENCRYPT','EXP','EXPORT_SET','EXTRACT','FIELD','FIND_IN_SET','FLOOR','FORMAT','FOUND_ROWS','FROM_DAYS','FROM_UNIXTIME','GET_FORMAT','GET_LOCK','GREATEST','GROUP_CONCAT','HEX','HOUR','IF','IFNULL','INET_ATON','INET_NTOA',
'INSERT','INSTR','INTERVAL','IS_FREE_LOCK','IS_USED_LOCK','ISNULL','LAST_DAY','LAST_INSERT_ID','LCASE','LEAST','LEFT','LENGTH','LN','LOAD_FILE','LOCALTIME','LOCALTIMESTAMP','LOCATE','LOG10','LOG2','LOG','LOWER','LPAD','LTRIM','MAKE_SET','MAKEDATE','MAKETIME','MASTER_POS_WAIT','MAX','MD5','MICROSECOND',
'MID','MIN','MINUTE','MOD','MONTH','MONTHNAME','NOW','NULLIF','OCT','OCTET_LENGTH','OLD_PASSWORD','ORD','PASSWORD','PERIOD_ADD','PERIOD_DIFF','PI','POSITION','POW','POWER','PROCEDURE ANALYSE','QUARTER','QUOTE','RADIANS','RAND','RELEASE_LOCK','REPEAT','REPLACE','REVERSE','RIGHT','ROUND',
'RPAD','RTRIM','SEC_TO_TIME','SECOND','SESSION_USER','SHA1','SHA','SIGN','SIN','SOUNDEX','SOUNDS LIKE','SPACE','SQRT','STD','STDDEV','STR_TO_DATE','STRCMP','SUBDATE','SUBSTRING_INDEX','SUBSTRING','SUBSTR','SUBTIME','SUM','SYSDATE','SYSTEM_USER','TAN','TIME_FORMAT','TIME_TO_SEC','TIME','TIMEDIFF',
'TIMESTAMP','TO_DAYS','TRIM','TRUNCATE','UCASE','UNCOMPRESS','UNCOMPRESSED_LENGTH','UNHEX','UNIX_TIMESTAMP','UPPER','USER','UTC_DATE','UTC_TIME','UTC_TIMESTAMP','UUID','VALUES','VARIANCE','WEEK','WEEKDAY','WEEKOFYEAR','YEAR','YEARWEEK'
		]
	}
	,'OPERATORS' :[
     'AND','&&','BETWEEN','&','|','^','/','DIV','<=>','=','>=','>','<<','>>','IS','<=','<','-','%','!=','<>','!','||','OR','+','REGEXP','RLIKE','XOR','~','*'
	]
	,'DELIMITERS' :[
		'(', ')', '[', ']', '{', '}'
	]
	,'REGEXPS' : {
		// highlight all variables (@...)
		'variables' : {
			'search' : '()(\\@\\w+)()'
			,'class' : 'variables'
			,'modifiers' : 'g'
			,'execute' : 'before' // before or after
		}
	}
	,'STYLES' : {
		'COMMENTS': 'color: #969696;'
		,'QUOTESMARKS': 'color: #CE7B00;'
		,'KEYWORDS' : {
			'reserved' : 'color: #259D9D;'
			,'functions' : 'color: #259D9D;'
			,'statements' : 'color: #0000E6;'
			}
		,'OPERATORS' : 'color: #000000;'
		,'DELIMITERS' : 'color: #000000;'
		,'REGEXPS' : {
			'variables' : 'color: #E0BD54;'
		}		
	}
};


/* Original colors
 ,'STYLES' : {
		'COMMENTS': 'color: #969696;'
		,'QUOTESMARKS': 'color: #CE7B00;'
		,'KEYWORDS' : {
			'reserved' : 'color: #48BDDF;'
			,'functions' : 'color: #0040FD;'
			,'statements' : 'color: #60CA00;'
			}
		,'OPERATORS' : 'color: #FF00FF;'
		,'DELIMITERS' : 'color: #2B60FF;'
		,'REGEXPS' : {
			'variables' : 'color: #E0BD54;'
		}		
	}
	 
 */
