<?php
/**
 * iNewS Project
 *
 * LICENSE
 *
 * Base on Zend Framework (http://framework.zend.com/).
 * Not for OpenSource.
 *
 * @category    iNewS
 * @package     lib_misc
 * @copyright   Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author      Woody ( GTalk: ahdong.com@gmail.com )
 * @since       2007-12-27
 * @version     SVN: $$Id: PaginationHelper.php 7804 2010-10-21 05:22:11Z woody $$
 * @license     http://www.inews.com.cn/license    Private License
 */


class Com_PaginationHelper
{
	//Attributes
	protected $_data;
	protected $_totalCount;
	protected $_startIndex;
	protected $_countPerPage = 10;
	protected $_currentPage = 1;
	protected $_totalPage = 0;


	/**
	 * Constructor
	 *
	 * @param Integer $countPerPage
	 * @param Integer $currentPage
	 * @param Integer $totalCount
	 * @param Array $data
	 */
	public function __construct($countPerPage = 10, $currentPage = 1, $totalCount = 0, $data = array())
	{
		//
		$this->_currentPage = intval($currentPage);
		if($this->_currentPage < 1) $this->_currentPage = 1;

		$this->_countPerPage = $countPerPage;
		$this->_data = $data;
		$this->_totalCount = $totalCount;

		//Calculate start Index
		$this->_startIndex = ($currentPage - 1) * $countPerPage;

		$lastPage = $this->getLastPage();
		if($this->_currentPage > $lastPage) $this->_currentPage = $lastPage;
	}

	//
	public function getCurrentPage() {
		return $this->_currentPage;
	}
	public function setCurrentPage($currentPage) {
		$this->_currentPage = $currentPage;
		$this->_startIndex = ($currentPage - 1) * $this->_countPerPage;
		return $this;
	}

	//
	public function getCountPerPage() {
		return $this->_countPerPage;
	}
	public function setCountPerPage($countPerPage) {
		$this->_countPerPage = $countPerPage;
		return $this;
	}

	public function getItems() {
		return $this->_items;
	}
	public function setItems($items) {
		$this->_items = $items;
		return $this;
	}

	public function getTotalCount() {
		return $this->_totalCount;
	}
	public function setTotalCount($totalCount) {
		$this->_totalCount = $totalCount;
		return $this;
	}

	public function getLastPage() {
		$lastPage = ceil($this->_totalCount/$this->_countPerPage);
		return $lastPage;
	}

	public function getNextPage() {
		$tmp = $this->_currentPage + 1;
		if($tmp > $this->getLastPage()) return 0;
		return $tmp;
	}

	public function getPreviousPage() {
		$tmp = $this->_currentPage - 1;
		if($tmp < 0) return 0;
		return $tmp;
	}

	public function getStartIndex() {
		return $this->_startIndex;
	}

	/**
	 * Generate pagination string
	 *
	 *
	 *
	 * @param PaginationConfig  $c Config
	 * @return String
	 */
	public function showPage(PaginationConfig $c=null,$url=null)
	{
		if(is_null($c)) {
			$c = PaginationConfig::instance()
			->setMatchs('<span class="pagination">#F##P##I##N##L#</span>')
			->setMatchItem('<a href="'.$url.'">#NOS#</a>')->setMatchCurrent('<a class="current">#NOS#</a>')
			->setLength(10)->setItemSpliter("")
			->setMatchFirst('<a href="'.$url.'">First</a>')->setMatchFirstFirst('<a>First</a>')
			->setMatchLast('<a href="'.$url.'">Last</a>')->setMatchLastLast('<a>Last</a>')
			->setMatchPrevious('<a href="'.$url.'">Pre</a>')->setMatchPreviousPrevious('<a>Pre</a>')
			->setMatchNext('<a href="'.$url.'">Next</a>')->setMatchNextNext('<a>Next</a>');
		}
		//ZeedUtil::print_r($c);

		//TODO:clean the rubbish below
		$matchs=$c->matchs;
		$matchItem=$c->matchItem;
		$matchCurrent=$c->matchCurrent;$length=$c->length;
		$matchsIfTotalIsOne=$c->matchsIfTotalIsOne;$matchsIfTotalIsLesser=$c->matchsIfTotalIsLesser;
		$itemSpliter=$c->itemSpliter;$matchBlank=$c->matchBlank;$nosFunction=$c->nosFunction;
		$matchOption=$c->matchOption;$selected=$c->selected;$unselected=$c->unselected;
		$matchFirst=$c->matchFirst;$matchFirstFirst=$c->matchFirstFirst;
		$matchLast=$c->matchLast;$matchLastLast=$c->matchLastLast;
		$matchPrevious=$c->matchPrevious;$matchPreviousPrevious=$c->matchPreviousPrevious;
		$matchNext=$c->matchNext;$matchNextNext=$c->matchNextNext;

		$CurrentPage = $this->getCurrentPage();
		$TotalPage = $this->getLastPage();
		if($TotalPage < 2 && !is_null($matchsIfTotalIsOne)) {
			$matchs = $matchsIfTotalIsOne;
		}
		else if($TotalPage < $length && !is_null($matchsIfTotalIsLesser)) {
			$matchs = $matchsIfTotalIsLesser;
		}

		$matchsFrom = array();
		$matchsTo = array();

		//For element list: 1 2 3 4 5
		if(!empty($matchItem) && strpos($matchs,'#I#') !== false) {
			$itemsArray = array();

			$PageStart = $CurrentPage - intval($length/2);
			if ($length%2 == 0) {
				$PageStart ++;
			}
            if ($PageStart < 1) {
                $PageStart = 1;
            }
            
            $PageEnd = $CurrentPage + intval($length/2);
            if ($PageEnd > $TotalPage) {
            	$PageEnd = $TotalPage;
            }
		
            while ( (($PageEnd-$PageStart+1) < $length) && $PageEnd < $TotalPage) {
                $PageEnd ++;
            }
            while ( (($PageEnd-$PageStart+1) < $length) && $PageStart > 1) {
                $PageStart --;
            }

			for($i = $PageStart;$i <= $PageEnd;$i++)
			{
				if ($i == $CurrentPage && !empty($matchCurrent)) {
					$t_match = $matchCurrent;
				} else {
					$t_match = $matchItem;
				}

				if( !empty($nosFunction) ) {
					eval('$t_nos = '.$nosFunction.'('.$i.');');
					$t_replace = array($i,$t_nos);
				} else {
					$t_replace = array($i,$i);
				}
					
				$t_item = str_replace(array('#NO#','#NOS#'),$t_replace,$t_match);
				$itemsArray[] = $t_item;


			}
			$output = implode($itemsArray,$itemSpliter);

			$matchsFrom[] = '#I#';
			$matchsTo[] = $output;

		}
		//For option select
		if(!empty($matchOption) && strpos($matchs,'#IO#') !== false) {
			$min = $this->_currentPage - 500;
			$max = $this->_currentPage + 500;
			if($min < 1) $min = 1;
			if($max > $TotalPage) $max = $TotalPage;

			$itemsArray = array();
			for($i = $min;$i <= $max;$i++)
			{
				if ($i == $CurrentPage) {
					$t_selected = $selected;
				} else {
					$t_selected = $unselected;
				}

				if( !empty($nosFunction) ) {
					eval('$t_nos = '.$nosFunction.'('.$i.');');
					$t_replace = array($i,$t_nos,$t_selected);
				} else {
					$t_replace = array($i,$i,$t_selected);
				}
					
				$t_item = str_replace(array('#NO#','#NOS#','#SLTD#'),$t_replace,$matchOption);
				$itemsArray[] = $t_item;


			}
			$output = implode($itemsArray,"\n");

			$matchsFrom[] = '#IO#';
			$matchsTo[] = $output;
		}

		//For first page
		if(!empty($matchFirst) && strpos($matchs,'#F#') !== false) {
			if(1== $CurrentPage && empty($matchFirstFirst)) {
				$matchsFrom[] = '#F#';
				$matchsTo[] = '';
			} else
			{
				if(1== $CurrentPage && !empty($matchFirstFirst)) {
					$t_match = $matchFirstFirst;
				} else {
					$t_match = $matchFirst;
				}
				$t_match = str_replace(array('#NO#'),array(1),$t_match);
				$matchsFrom[] = '#F#';
				$matchsTo[] = $t_match;
			}
		}

		//For last page
		if(!empty($matchLast) && strpos($matchs,'#L#') !== false) {
			if($TotalPage == $CurrentPage && empty($matchLastLast)) {
				$matchsFrom[] = '#L#';
				$matchsTo[] = '';
			} else
			{
				if($TotalPage == $CurrentPage && !empty($matchLastLast)) {
					$t_match = $matchLastLast;
				} else {
					$t_match = $matchLast;
				}

				$t_match = str_replace(array('#NO#'),array($TotalPage),$t_match);
				$matchsFrom[] = '#L#';
				$matchsTo[] = $t_match;
			}
		}

		//For previous page
		if(!empty($matchPrevious) && strpos($matchs,'#P#') !== false) {
			$PreviousPage = $this->getPreviousPage();

			if(0 == $PreviousPage && empty($matchPreviousPrevious)) {
				$matchsFrom[] = '#P#';
				$matchsTo[] = '';
			} else
			{
				if(0 == $PreviousPage && !empty($matchPreviousPrevious)) {
					$t_match = $matchPreviousPrevious;
					$PreviousPage = 1;
				} else {
					$t_match = $matchPrevious;
				}

				$t_match = str_replace(array('#NO#'),array($PreviousPage),$t_match);
				$matchsFrom[] = '#P#';
				$matchsTo[] = $t_match;
			}
		}

		//For next page
		if(!empty($matchNext) && strpos($matchs,'#N#') !== false) {
			$NextPage = $this->getNextPage();

			if($TotalPage == $NextPage && empty($matchNextNext)) {
				$matchsFrom[] = '#N#';
				$matchsTo[] = '';
			} else
			{
				if($TotalPage == $CurrentPage && !empty($matchNextNext)) {
					$t_match = $matchNextNext;
					$NextPage = $TotalPage;
				} else {
					$t_match = $matchNext;
				}
				$t_match = str_replace(array('#NO#'),array($NextPage),$t_match);
				$matchsFrom[] = '#N#';
				$matchsTo[] = $t_match;
			}
		}


		return str_replace($matchsFrom,$matchsTo,$matchs);
	}

}

/**
 * #NO#:Page number;
 * #NOS$:Page number after processed by $nosFunction
 */
class PaginationConfig
{
	public  $matchs = '#F# #P# #I# #N# #L# #IO#'; // Config what to show #F#:First page button;#P#:Previous;#I#:Items list;#N#:Next;#L#:Last;#IO#:Item operations
	public  $matchItem = '<a href="/test/test/page/#NO#/">#NOS#</a>'; // Template of item element
	public  $matchCurrent = '<a href="#">#NOS#</a>'; // Template of current item element
	public  $length = 7; // How many pages to show in the list
	public  $matchsIfTotalIsOne = ''; // Config what to show when there is only one page
	public  $matchsIfTotalIsLesser = null; // Config what to show when total page is lesser then $length
	public  $itemSpliter = ' '; // Splitter between elements
	public  $matchBlank; // ...
	public  $nosFunction = null; // The function name trim page number to cool or local word
	public  $matchOption = '<option value="#NO#"#SLTD#>#NOS#</option>'; // Template of option
	public  $selected = ' selected="selected"'; // Flag Template for element selected,commonly seleted="selected"
	public  $unselected = ''; // Flag Template for element no selected,commonly ''
	public  $matchFirst; // Template of first page button
	public  $matchFirstFirst; // Template of first page button when the current page is the first page
	public  $matchLast; // Template of last page button
	public  $matchLastLast; // Template of last page button when the current page is the last page
	public  $matchPrevious; // Template of previous page button
	public  $matchPreviousPrevious; // Template of previous page button when the current page is the first page
	public  $matchNext; // Template of next page button
	public  $matchNextNext; // Template of next page button when the current page is the last page

	/**$matchs='#F# #P# #I# #N# #L# #IO#',
		$matchItem='<a href="/test/test/page/#NO#/">#NOS#</a>',
		$matchCurrent='<a href="#CURRENT">#NOS#</a>',$length=7,
		$matchsIfTotalIsOne=null,$matchsIfTotalIsLesser=null,
		$itemSpliter=' ',$matchBlank='...',$nosFunction=null,
		$matchOption='<option value="#NO#"#SLTD#>#NOS#</option>',$selected=' selected="selected"',$unselected='',
		$matchFirst='',$matchFirstFirst='',
		$matchLast='',$matchLastLast='',
		$matchPrevious='',$matchPreviousPrevious='',
		$matchNext='',$matchNextNext=''*/


	/**
	 * Get an instance
	 *
	 * @return PaginationConfig
	 */
	public static function instance() {
		return new PaginationConfig();
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchs($v) {
		$this->matchs = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchItem($v) {
		$this->matchItem = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchCurrent($v) {
		$this->matchCurrent = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setLength($v) {
		$this->length = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchsIfTotalIsOne($v) {
		$this->matchsIfTotalIsOne = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchsIfTotalIsLesser($v) {
		$this->matchsIfTotalIsLesser = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setItemSpliter($v) {
		$this->itemSpliter = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchBlank($v) {
		$this->matchBlank = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setNosFunction($v) {
		$this->nosFunction = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchOption($v) {
		$this->matchOption = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setSelected($v) {
		$this->Selected = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setUnselected($v) {
		$this->unselected = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchFirst($v) {
		$this->matchFirst = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchFirstFirst($v) {
		$this->matchFirstFirst = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchLast($v) {
		$this->matchLast = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchLastLast($v) {
		$this->matchLastLast = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchPrevious($v) {
		$this->matchPrevious = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchPreviousPrevious($v) {
		$this->matchPreviousPrevious = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchNext($v) {
		$this->matchNext = $v;
		return $this;
	}

	/**
	 * Set var
	 *
	 * @param String $v
	 * @return PaginationConfig
	 */
	public function setMatchNextNext($v) {
		$this->matchNextNext = $v;
		return $this;
	}

}
