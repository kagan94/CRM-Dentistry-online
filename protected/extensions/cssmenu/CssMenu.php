<?

class CssMenu extends CWidget
{
	public $items;
	private $_items;
	public $readCurrentPage = false;
	private $currentPage = '';
	
	
	//Public
	
	/*
	*	The initializer/main class for the Menu
	*	Calls the parser and registers the css
	*/
	public function init()
	{
		$this->_items = new CssMenuItem();
		$this->parseItems($this->items,$this->_items);
		
		$this->registerCss();
		if($this->readCurrentPage) $this->readCurrentPage();
		//var_dump($this->_items);
		
		$this->makeMenu();
	}

	
	//Private
	
	/*
	*	Creates the holder for the menu
	*	Calls the top level generator for each top level item
	*/
	private function makeMenu()
	{
		echo '<div class="css_menu">';
		foreach($this->_items->getChildren() as $item)
		{
			$this->menuTopLevelItem($item);
		}
		echo '</div>';
	}
	
	/*
	*	Creates a top level menu item
	*	Calls the sub level generator for each of its children
	*/
	private function menuTopLevelItem($item)
	{
		echo '<div class="css_top_item';
		if($item->first) echo ' first';
		if($item->last) echo ' last';
		if($this->readCurrentPage and $item->scanForUrl($this->currentPage)) echo ' current';
		echo '">';
		$item->makeItem();
		if($item->hasChildren())
		{
			echo '<div class="css_sub_menu">';
			foreach($item->getChildren() as $child)
			{
				$this->menuSubLevelItem($child);
			}
			echo '</div>';
		}
		echo '</div>';
	}
	
	/*
	*	Creates the sub level menu item
	*	Self-recursive for each of its children
	*/
	private function menuSubLevelItem($item)
	{
		echo '<div class="css_sub_item';
		if($item->first) echo ' first';
		if($item->last) echo ' last';
		echo '">';;
		$item->makeItem();
		if($item->hasChildren())
		{
			echo '<div class="css_sub_menu2">';
			foreach($item->getChildren() as $child)
			{
				$this->menuSubLevelItem($child);
			}
			echo '</div>';
		}
		echo '</div>';
	}
	
	/*
	*	Gets the current page name
	*/
	private function readCurrentPage()
	{
		$this->currentPage = $_REQUEST['r'];
		
	}
	
	/*
	*	Registers default CSS 
	*/
	private function registerCSS()
	{
		$url = Yii::app()->getAssetManager()->publish( Yii::getPathOfAlias('ext.cssmenu.assets') );
		Yii::app()->clientScript->registerCssFile($url.'/cssmenu.css');
	}
	
	/*
	*	Parses the input array and sets the flags for each item
	*/
	private function parseItems($items,$parent)
	{
		foreach($items as $item_raw)
		{
			$children = false;
			if(isset($item_raw['visible']))
			{
				if(!$item_raw['visible']) continue;
			
				unset($item_raw['visible']);
			}
			if(isset($item_raw['items']))
			{
				$children = $item_raw['items'];
				unset($item_raw['items']);
			}
			$child = $this->parseItem($item_raw);
			if($children)
			{
				$this->parseItems($children,$child);
			}
			$parent->addChild($child);
		}
		$parent->locateChildren();
	}
	
	/*
	*	Creates the helper class item object for an item
	*	Calls the Options Parser
	*/
	private function parseItem($options)
	{

		$temp = new CssMenuItem;
		$temp->parseOptions($options);
		return $temp;
	}
	
	
	
}

/*
*	Indivdual item class
*	Helper class for CssMenu
*/
class CssMenuItem
{
	private $label = 'Menu Item';
	private $url = false;
	private $children = array();
	public $first = false;
	public $last = false;
	public $itemOptions = array();
	public $current = false;
	
	//Public
	
	/*
	*	Creates an individual item
	*	Uses hrefs and spans
	*/
	public function makeItem()
	{
		echo '<a href="';
		echo $this->makeLinkUrl();
		echo '"';
		echo $this->parseExtra();
		echo '>';
		echo '<span>';
		echo $this->label;
		echo '</span>';
		echo '</a>';
	}
	
	/*
	*	Adds a child to this items children array
	*/
	public function addChild($child)
	{
		$this->children[] = $child;
	}
	
	/*
	*	Parses the options passed in the array to properties of the object
	*/
	public function parseOptions($options)
	{
		foreach($options as $option => $value)
		{
			$this->$option = $value;
		}
	}
	
	/*
	*	Return true or false if the object has any children items
	*/
	public function hasChildren()
	{
		return !count($this->children) == 0;
	}
	
	/*
	*	Gets all the children of this object (as an array of objects)
	*/
	public function getChildren()
	{
		return $this->children;
	}
	
	/*
	*	Sets the first childs and last childs first or last property to true respectivly
	*/
	public function locateChildren()
	{
		$count = count($this->children);
		if($count)
		{
			$this->children[0]->first = true;
			$this->children[$count-1]->last = true;
		}
	}
	
	/*
	*	parses and outputs the "extra" options for the item
	*/
	public function parseExtra()
	{
		$return = '';
		foreach($this->itemOptions as $item => $value)
		{
			$return .= ' '.$item.'="'.$value.'"';
		}
		echo $return;
	}
	
	/*
	*	Returns true or false if the inputted URL is the target of itself or one of its children
	*/
	public function scanForUrl($url)
	{
		if($this->url)
		{
			if(is_array($this->url))
				$curl = $this->url[0];
			else
				$curl = $this->url;
			
			if($curl[0] == '/') $curl = substr($curl,1);
			if($curl[strlen($curl)-1] == '/') $curl = substr($curl,0,strlen($curl)-2);
			if($url == $curl) return true;
		}
		
		if(count($this->children))
		{
			foreach($this->children as $child)
			{
				if($child->scanForUrl($url)) return true;
			}
		}
		else return false;
	}

	private function makeLinkUrl(){
		if($this->url == false)
		{
			return 'javascript:void(0)';
		}
		else
		{
			if(is_array($this->url))
			{
				$first = array_shift($this->url);
				return Yii::app()->createUrl($first,$this->url);
			}
			elseif(preg_match('/(https?|ftp|mailto|tel):\/\//i', $this->url)){
				return $this->url;
			}
			else
			{
				return Yii::app()->createUrl($this->url);
			}
		}
	}
}

?>