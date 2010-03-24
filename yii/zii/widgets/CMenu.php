<?php
/**
 * CMenu class file.
 *
 * @author Jonah Turnquist <poppitypop@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CMenu displays a multi-level menu using nested HTML lists.
 *
 * The main property of CMenu is {@link items}, which specifies the possible items in the menu.
 * A menu item has three main properties: visible, active and items. The "visible" property
 * specifies whether the menu item is currently visible. The "active" property specifies whether
 * the menu item is currently selected. And the "items" property specifies the child menu items.
 *
 * The following example shows how to use CMenu:
 * <pre>
 * $this->widget('zii.widgets.CMenu', array(
 *     'items'=>array(
 *         array('label'=>'Home', 'url'=>array('site/index')),
 *         array('label'=>'Products', 'url'=>array('product/index'), 'items'=>array(
 *             array('label'=>'New Arrivals', 'url'=>array('product/new', 'tag'=>'new')),
 *             array('label'=>'Most Popular', 'url'=>array('product/index', 'tag'=>'popular')),
 *         )),
 *         array('label'=>'Login', 'url'=>array('site/login'), 'visible'=>Yii::app()->user->isGuest),
 *     ),
 * ));
 * </pre>
 *
 * @author Jonah Turnquist <poppitypop@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CMenu.php 140 2010-03-10 20:03:07Z qiang.xue $
 * @package zii.widgets
 * @since 1.1
 */
class CMenu extends CWidget
{
	/**
	 * @var array list of menu items. Each menu item is specified as an array of name-value pairs.
	 * Possible option names include the following:
	 * <ul>
	 * <li>label: string, required, specifies the menu item label. When {@link encodeLabel} is true, the label
	 * will be HTML-encoded.</li>
	 * <li>url: string or array, optional, specifies the URL of the menu item. It is passed to {@link CHtml::normalizeUrl}
	 * to generate a valid URL. If this is not set, the menu item will be rendered as a span text.</li>
	 * <li>visible: boolean, optional, whether this menu item is visible. Defaults to true.
	 * This can be used to control the visibility of menu items based on user permissions.</li>
	 * <li>items: array, optional, specifies the sub-menu items. Its format is the same as the parent items.</li>
	 * <li>active: boolean, optional, whether this menu item is in active state (currently selected).
	 * If a menu item is active and {@link activeClass} is not empty, its CSS class will be appended with {@link activeClass}.
	 * If this option is not set, the menu item will be set active automatically when the current request
	 * is triggered by {@link url}.</li>
	 * <li>template: string, optional, the template used to render this menu item.
	 * In this template, the token "{menu}" will be replaced with the corresponding menu link or text.
	 * Please see {@link itemTemplate} for more details. This option has been available since version 1.1.1.</li>
	 * <li>linkOptions: array, optional, additional HTML attributes to be rendered for the link or span tag of the menu item.</li>
	 * <li>itemOptions: array, optional, additional HTML attributes to be rendered for the container tag of the menu item.</li>
	 * </ul>
	 */
	public $items=array();
	/**
	 * @var string the template used to render an individual menu item. In this template,
	 * the token "{menu}" will be replaced with the corresponding menu link or text.
	 * If this property is not set, each menu will be rendered without any decoration.
	 * This property will be overridden by the 'template' option set in individual menu items via {@items}.
	 * @since 1.1.1
	 */
	public $itemTemplate;
	/**
	 * @var boolean whether the labels for menu items should be HTML-encoded. Defaults to true.
	 */
	public $encodeLabel=true;
	/**
	 * @var string the CSS class to be appended to the active menu item. Defaults to 'active'.
	 * If empty, the CSS class of menu items will not be changed.
	 */
	public $activeCssClass='active';
	/**
	 * @var boolean whether to activate parent menu items when one of the corresponding child menu items is active.
	 * The activated parent menu items will also have its CSS classes appended with {@link activeCssClass}.
	 * Defaults to false.
	 */
	public $activateParents=false;
	/**
	 * @var boolean whether to hide empty menu items. An empty menu item is one whose 'url' option is not
	 * set and which doesn't contain visible child menu items. Defaults to true.
	 */
	public $hideEmptyItems=true;
	/**
	 * @var array HTML attributes for the menu's root container tag
	 */
	public $htmlOptions=array();
	/**
	 * @var array HTML attributes for the submenu's container tag.
	 */
	public $submenuHtmlOptions=array();

	/**
	 * Initializes the menu widget.
	 * This method mainly normalizes the {@link items} property.
	 * If this method is overridden, make sure the parent implementation is invoked.
	 */
	public function init()
	{
		$this->htmlOptions['id']=$this->getId();
		$route=$this->getController()->getRoute();
		$this->items=$this->normalizeItems($this->items,$route,$hasActiveChild);
	}

	/**
	 * Calls {@link renderMenu} to render the menu.
	 */
	public function run()
	{
		$this->renderMenu($this->items);
	}

	/**
	 * Renders the menu items.
	 * @param array menu items. Each menu item will be an array with at least two elements: 'label' and 'active'.
	 * It may have three other optional elements: 'items', 'linkOptions' and 'itemOptions'.
	 */
	protected function renderMenu($items)
	{
		if(count($items))
		{
			echo CHtml::openTag('ul',$this->htmlOptions)."\n";
			$this->renderMenuRecursive($items);
			echo CHtml::closeTag('ul');
		}
	}

	/**
	 * Recursively renders the menu items.
	 * @param array the menu items to be rendered recursively
	 */
	protected function renderMenuRecursive($items)
	{
		foreach($items as $item)
		{
			echo CHtml::openTag('li', isset($item['itemOptions']) ? $item['itemOptions'] : array());
			if(isset($item['url']))
				$menu=CHtml::link($item['label'],$item['url'],isset($item['linkOptions']) ? $item['linkOptions'] : array());
			else
				$menu=CHtml::tag('span',isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
			if(isset($this->itemTemplate) || isset($item['template']))
			{
				$template=isset($item['template']) ? $item['template'] : $this->itemTemplate;
				echo strtr($template,array('{menu}'=>$menu));
			}
			else
				echo $menu;
			if(isset($item['items']) && count($item['items']))
			{
				echo "\n".CHtml::openTag('ul',$this->submenuHtmlOptions)."\n";
				$this->renderMenuRecursive($item['items']);
				echo CHtml::closeTag('ul')."\n";
			}
			echo CHtml::closeTag('li')."\n";
		}
	}

	/**
	 * Normalizes the {@link items} property so that the 'active' state is properly identified for every menu item.
	 * @param array the items to be normalized.
	 * @param string the route of the current request.
	 * @param boolean whether there is an active child menu item.
	 * @return array the normalized menu items
	 */
	protected function normalizeItems($items,$route,&$active)
	{
		foreach($items as $i=>$item)
		{
			if(isset($item['visible']) && !$item['visible'])
			{
				unset($items[$i]);
				continue;
			}
			if($this->encodeLabel)
				$items[$i]['label']=CHtml::encode($item['label']);
			$hasActiveChild=false;
			if(isset($item['items']))
			{
				$items[$i]['items']=$this->normalizeItems($item['items'],$route,$hasActiveChild);
				if(empty($items[$i]['items']) && $this->hideEmptyItems)
					unset($items[$i]['items']);
			}
			if(!isset($item['active']))
			{
				if($this->activateParents && $hasActiveChild || $this->isItemActive($item,$route))
					$active=$items[$i]['active']=true;
				else
					$items[$i]['active']=false;
			}
			else if($item['active'])
				$active=true;
			if($items[$i]['active'] && $this->activeCssClass!='')
			{
				if(isset($item['itemOptions']['class']))
					$items[$i]['itemOptions']['class'].=' '.$this->activeCssClass;
				else
					$items[$i]['itemOptions']['class']=$this->activeCssClass;
			}
		}
		return array_values($items);
	}

	/**
	 * Checks whether a menu item is active.
	 * This is done by checking if the currently requested URL is generated by the 'url' option
	 * of the menu item.
	 * @param array the menu item to be checked
	 * @param string the route of the current request
	 * @return boolean whether the menu item is active
	 */
	protected function isItemActive($item,$route)
	{
		if(isset($item['url']) && is_array($item['url']) && !strcasecmp(trim($item['url'][0],'/'),$route))
		{
			if(count($item['url'])>1)
			{
				foreach(array_splice($item['url'],1) as $name=>$value)
				{
					if(!isset($_GET[$name]) || $_GET[$name]!=$value)
						return false;
				}
			}
			return true;
		}
		return false;
	}
}