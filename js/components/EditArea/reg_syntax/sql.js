editAreaLoader.load_syntax["sql"] = {
	'COMMENT_SINGLE' : {1 : '--', 2:'#'}
	,'COMMENT_MULTI' : {'/*' : '*/'}
	,'QUOTEMARKS' : {1: "'", 2: '"', 3: '`'}
	,'KEYWORD_CASE_SENSITIVE' : false
	,'KEYWORDS' : {
		'statements' : [
			'select', 'SELECT', 'where', 'order', 'by','set','DROP','DEFINER','BEGIN','END','BEFORE','DELETE',
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
			'precision', 'real', 'refcursor', 'serial', 'serial4', 'serial8', 'smallint', 'text', 'timestamp', 'varbit',
			'big5_chinese_ci','big5',
'big5_bin',
'dec8_swedish_ci',
'dec8_bin','dec8',
'cp850_general_ci','cp850',
'cp850_bin',
'hp8_english_ci','hp8',
'hp8_bin',
'koi8r_general_ci','koi8r',
'koi8r_bin',
'latin1_german1_ci','latin1',
'latin1_swedish_ci',
'latin1_danish_ci',
'latin1_german2_ci',
'latin1_bin',
'latin1_general_ci',
'latin1_general_cs',
'latin1_spanish_ci',
'latin2_czech_cs','latin2',
'latin2_general_ci',
'latin2_hungarian_ci',
'latin2_croatian_ci',
'latin2_bin',
'swe7_swedish_ci','swe7',
'swe7_bin',
'ascii_general_ci','ascii',
'ascii_bin',
'ujis_japanese_ci','ujis',
'ujis_bin',
'sjis_japanese_ci',
'sjis_bin',
'hebrew_general_ci','hebrew',
'hebrew_bin',
'tis620_thai_ci','tis620',
'tis620_bin',
'euckr_korean_ci','euckr',
'euckr_bin',
'koi8u_general_ci','koi8u',
'koi8u_bin',
'gb2312_chinese_ci','gb2312',
'gb2312_bin',
'greek_general_ci','greek',
'greek_bin',
'cp1250_general_ci','cp1250',
'cp1250_czech_cs',
'cp1250_croatian_ci',
'cp1250_bin',
'gbk_chinese_ci','gbk',
'gbk_bin',
'latin5_turkish_ci','latin5',
'latin5_bin',
'armscii8_general_ci','armscii8',
'armscii8_bin',
'utf8_general_ci','utf8',
'utf8_bin',
'utf8_unicode_ci',
'utf8_icelandic_ci',
'utf8_latvian_ci',
'utf8_romanian_ci',
'utf8_slovenian_ci',
'utf8_polish_ci',
'utf8_estonian_ci',
'utf8_spanish_ci',
'utf8_swedish_ci',
'utf8_turkish_ci',
'utf8_czech_ci',
'utf8_danish_ci',
'utf8_lithuanian_ci',
'utf8_slovak_ci',
'utf8_spanish2_ci',
'utf8_roman_ci',
'utf8_persian_ci',
'utf8_esperanto_ci',
'utf8_hungarian_ci',
'ucs2_general_ci','ucs2',
'ucs2_bin',
'ucs2_unicode_ci',
'ucs2_icelandic_ci',
'ucs2_latvian_ci',
'ucs2_romanian_ci',
'ucs2_slovenian_ci',
'ucs2_polish_ci',
'ucs2_estonian_ci',
'ucs2_spanish_ci',
'ucs2_swedish_ci',
'ucs2_turkish_ci',
'ucs2_czech_ci',
'ucs2_danish_ci',
'ucs2_lithuanian_ci',
'ucs2_slovak_ci',
'ucs2_spanish2_ci',
'ucs2_roman_ci',
'ucs2_persian_ci',
'ucs2_esperanto_ci',
'ucs2_hungarian_ci',
'cp866_general_ci','cp866',
'cp866_bin',
'keybcs2_general_ci','keybcs2',
'keybcs2_bin',
'macce_general_ci','macce',
'macce_bin',
'macroman_general_ci','macroman',
'macroman_bin',
'cp852_general_ci','cp852',
'cp852_bin',
'latin7_estonian_cs','latin7',
'latin7_general_ci',
'latin7_general_cs',
'latin7_bin',
'cp1251_bulgarian_ci','cp1251',
'cp1251_ukrainian_ci',
'cp1251_bin',
'cp1251_general_ci',
'cp1251_general_cs',
'cp1256_general_ci','cp1256',
'cp1256_bin',
'cp1257_lithuanian_ci','cp1257',
'cp1257_bin',
'cp1257_general_ci',
'binary','63',
'geostd8_general_ci','geostd8',
'geostd8_bin',
'cp932_japanese_ci','cp932',
'cp932_bin',
'eucjpms_japanese_ci','eucjpms',
'eucjpms_bin'

    
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
