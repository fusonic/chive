<?php

/**
 * LinkPager displays a list of hyperlinks that lead to different pages of target.
 */
class LinkPager extends CLinkPager
{
	const CSS_FIRST_ELEMENT = 'first-element';
	const CSS_LAST_ELEMENT = 'last-element';
	const CSS_SETTINGS = 'settings';

	/**
	 * Executes the widget.
	 * This overrides the parent implementation by displaying the generated page buttons.
	 */
	public function run()
	{
		if($this->nextPageLabel === null)
		{
			$this->nextPageLabel = '&raquo;';
		}
		if($this->prevPageLabel === null)
		{
			$this->prevPageLabel = '&laquo;';
		}
		if($this->firstPageLabel === null)
		{
			$this->firstPageLabel = Yii::t('core', 'first');
		}
		if($this->lastPageLabel === null)
		{
			$this->lastPageLabel = Yii::t('core', 'last');
		}
		$this->maxButtonCount = 5;

		$buttons = $this->createPageButtons();

		if(empty($buttons))
		{
			return;
		}

		$htmlOptions = $this->htmlOptions;
		if(!isset($htmlOptions['id']))
		{
			$htmlOptions['id'] = $this->getId();
		}
		if(!isset($htmlOptions['class']))
		{
			$htmlOptions['class'] = 'yiiPager';
		}
		echo $this->header;
		echo CHtml::tag('ul', $htmlOptions, implode("\n", $buttons));
		echo $this->footer;
	}

	/**
	 * Creates the page buttons.
	 * @return array a list of page buttons (in HTML code).
	 */
	protected function createPageButtons()
	{
		list($beginPage, $endPage) = $this->getPageRange();
		$currentPage = $this->getCurrentPage(false); // currentPage is calculated in getPageRange()
		$buttons = array();
		$pageCount = $this->getPageCount();

		// first page
		if($beginPage > 0)
		{
			$buttons[] = $this->createPageButton(
				$this->firstPageLabel,
				0,
				self::CSS_FIRST_PAGE . ' ' . self::CSS_FIRST_ELEMENT,
				$beginPage <= 0,
				false);
		}

		// prev page
		if(($page = $currentPage - 1) < 0)
		{
			$page = 0;
		}
		$buttons[] = $this->createPageButton(
			$this->prevPageLabel,
			$page,
			self::CSS_PREVIOUS_PAGE . ($beginPage == 0 ? ' ' . self::CSS_FIRST_ELEMENT : null),
			$currentPage <= 0,
			false);

		// internal pages
		for($i = $beginPage; $i <= $endPage; ++$i)
		{
			$buttons[] = $this->createPageButton(
				$i + 1,
				$i,
				self::CSS_INTERNAL_PAGE,
				false,
				$i == $currentPage);
		}

		// next page
		if(($page = $currentPage + 1) >= $pageCount - 1)
		{
			$page = $pageCount - 1;
		}
		$buttons[] = $this->createPageButton(
			$this->nextPageLabel,
			$page,
			self::CSS_NEXT_PAGE,
			$currentPage >= $pageCount - 1,
			false);

		// last page
		if($endPage < $pageCount - 1)
		{
			$buttons[] = $this->createPageButton(
				$this->lastPageLabel,
				$pageCount - 1,
				self::CSS_LAST_PAGE,
				$endPage >= $pageCount - 1,
				false);
		}

		// settings
		$content = '';
		$sizes = array(5, 10, 50, 100, 500);
		$currentSize = $this->getPageSize();
		foreach($sizes AS $size)
		{
			if($size == $currentSize)
			{
				$content .= '&nbsp;' . $size . '&nbsp;';
			}
			else
			{
				$content .= '&nbsp;<a href="' . $this->createPageUrl($this->getCurrentPage(), $size) . '">' . $size . '</a>&nbsp;';
			}
		}
		$icon = new Icon();
		$icon->text = 'core.settings';
		$icon->name = 'operation';
		$icon->size = 12;
		$buttons[] =
			'<li class="' . self::CSS_SETTINGS . ' ' . self::CSS_LAST_ELEMENT . '">'
				. '<a href="javascript:void(0)" '
					. 'onclick="$(this).parent().hide().next().show()">' . Yii::t('core', 'entriesPerPage') . ':&nbsp;&nbsp;' . $currentSize . '</a>'
			. '</li>'
			. '<li class="' . self::CSS_SETTINGS . ' ' . self::CSS_LAST_ELEMENT . '" style="display: none">'
				. '<span>' . Yii::t('core', 'entriesPerPage') . ':&nbsp;&nbsp;' . $content . '</span>'
			. '</li>';

		return $buttons;
	}

	/**
	 * @see CBasePager::createPageUrl()
	 */
	protected function createPageUrl($page, $pageSize = null)
	{
		return $this->getPages()->createPageUrl($this->getController(), $page, $pageSize);
	}

}
