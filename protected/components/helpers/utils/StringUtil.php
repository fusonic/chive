<?php


/*
 * Provides basic string functionality
 */
class StringUtil
{

	/**
	 * Cuts the given text to a specific lenght and adds ... at the and
	 *
	 * @param string $_text
	 * @param int $_lenght
	 * @param bool $_isFoldable 		(for unfolding / folding longer texts)
	 * @return string
	 */
	public static function cutText($_text, $_length, $_isFoldable = false) {

		$_text = strip_tags($_text);

		if(function_exists("mb_strlen"))
			$length = mb_strlen($_text, "utf-8");
		else
			$length = strlen($_text);

		if($length > $_length) {

			$cutLength = max(1, $_length - 3);

			if(function_exists("mb_substr")) {
				$text = mb_substr($_text, 0, $cutLength, "utf-8");
				$textRest = mb_substr($_text, $cutLength, mb_strlen($_text, "utf-8"), "utf-8");
			}
			else {
				$text = substr($_text, 0, $cutLength);
				$textRest = substr($_text, $cutLength);
			}

			if(!$_isFoldable) {
				$text .= "...";
			}
			else {
				$id = "more_" . StringUtil::getRandom(5);
				$text .= " "
					. "<a style=\"text-decoration: none; font-size: 0.8em;\" id=\"show_" . $id . "\" href=" . CURRENT_PAGE_QS . "/# onclick=\"getById('" . $id . "').style.display = 'inline'; showHide('show_" . $id . "'); showHide('hide_" . $id . "'); return false;\">(" . Lang::get("global.w.more") . ")</a>"
					. "<b style=\"margin: 0px; font-weight: normal; display: none;\" id=\"" . $id . "\">" . $textRest . "</b>"
					. "<a style=\"text-decoration: none; font-size: 0.8em; float: left; display: none;\" id=\"hide_" . $id . "\" href=" . CURRENT_PAGE_QS . "/# onclick=\"getById('" . $id . "').style.display = 'none'; showHide('hide_" . $id . "'); showHide('show_" . $id . "'); return false;\">(" . Lang::get("global.w.less") . ")</a>";
			}

			return $text;

		}
		else
			return $_text;

	}

}

?>