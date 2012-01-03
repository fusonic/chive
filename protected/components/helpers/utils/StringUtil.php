<?php

/*
 * Chive - web based MySQL database management
 * Copyright (C) 2010 Fusonic GmbH
 *
 * This file is part of Chive.
 *
 * Chive is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * Chive is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library. If not, see <http://www.gnu.org/licenses/>.
 */


class StringUtil
{
	
/**
	 * Checks if a value is a valid integer
	 * @param string	$_value
	 * @return bool
	 */
	public static function isInt($_value) {

		if($_value === false)
			return false;

		if((string)((int)$_value) == $_value)
			return true;
		else
			return false;
	}

	/**
	 * Cuts the given text to a specific lenght and adds ... at the and
	 *
	 * @param string $_text
	 * @param int $_lenght
	 * @param bool $_isFoldable 		(for unfolding / folding longer texts)
	 * @return string
	 */
	public static function cutText($_text, $_length, $_isFoldable = false) 
	{
		$_text = strip_tags($_text);

		if(function_exists("mb_strlen"))
		{
			$length = mb_strlen($_text, "utf-8");
		}
		else
		{
			$length = strlen($_text);
		}

		if($length > $_length) 
		{
			$cutLength = max(1, $_length - 3);

			if(function_exists("mb_substr")) 
			{
				$text = mb_substr($_text, 0, $cutLength, "utf-8");
				$textRest = mb_substr($_text, $cutLength, mb_strlen($_text, "utf-8"), "utf-8");
			}
			else 
			{
				$text = substr($_text, 0, $cutLength);
				$textRest = substr($_text, $cutLength);
			}

			if(!$_isFoldable) 
			{
				$text .= "...";
			}
			else 
			{
				$id = "more_" . StringUtil::getRandom(5);
				$text .= " "
					. "<a style=\"text-decoration: none; font-size: 0.8em;\" id=\"show_" . $id . "\" href=" . CURRENT_PAGE_QS . "/# onclick=\"getById('" . $id . "').style.display = 'inline'; showHide('show_" . $id . "'); showHide('hide_" . $id . "'); return false;\">(" . Lang::get("global.w.more") . ")</a>"
					. "<b style=\"margin: 0px; font-weight: normal; display: none;\" id=\"" . $id . "\">" . $textRest . "</b>"
					. "<a style=\"text-decoration: none; font-size: 0.8em; float: left; display: none;\" id=\"hide_" . $id . "\" href=" . CURRENT_PAGE_QS . "/# onclick=\"getById('" . $id . "').style.display = 'none'; showHide('hide_" . $id . "'); showHide('show_" . $id . "'); return false;\">(" . Lang::get("global.w.less") . ")</a>";
			}

			return $text;
		}
		else
		{
			return $_text;
		}
	}

	/**
	 * Returns a random string with a specified length
	 * @param int	$_length
	 * @param bool	$_specialChars
	 * @param bool $_removeConfusable (removes o,i,l ....)
	 * @return string
	 */
	public static function getRandom($_length, $_specialChars = false, $_removeConfusable = false) 
	{
		$confusable = "IOlo0i";
		$chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz123456789" . ($_removeConfusable ? "" : $confusable);
		if($_specialChars)
		{
			$chars .= "!\"§$%&/()=?*+#'-_,;.:<>";
		}

		$return = "";

		for($i = 0; $i < $_length; $i++) 
		{
			$char = mt_rand(0, strlen($chars) - 1);
			$return .= substr($chars, $char, 1);
		}

		return $return;
	}

	/**
	 *
	 * Returns a boolean indicating if $string contains $needle.
	 * @param string $string	The haystack
	 * @param string $needle	The needle
	 * @return bool
	 */
	public static function contains($string, $needle)
	{
		return !(strpos($string, $needle) === false);
	}

	/**
	 *
	 * Returns a boolean indicating if the given $string starts with $needle.
	 * @param string $string
	 * @param string $needle
	 * @return bool
	 */
	public static function startsWith($string, $needle)
	{
		return strpos($string, $needle) === 0;
	}

	/**
	 *
	 * Returns a boolean indicating if the given $string ends with $needle.
	 * @param string $string
	 * @param string $needle
	 * @return bool
	 */
	public static function endsWith($string, $needle)
	{
		return substr($string, -strlen($needle)) === $needle;
	}
}