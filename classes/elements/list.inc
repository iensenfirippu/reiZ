<?php
class HtmlList extends HtmlElement
{
	protected $_rows = 0;
	protected $_altenaterow;
	protected $_altenatecell;
	protected $_nextrow;
	
	public function __construct($columnheaders, $alternaterow=true, $alternatecell=false)
	{
		parent::__construct('table');
		if ($alternaterow == false) { $this->_alternaterow = null; }
		else { $this->_alternaterow = true; }
		if ($alternatecell == false) { $this->_alternatecell = null; }
		else { $this->_alternatecell = true; }
		
		if (sizeof($columnheaders) > 0)
		{
			$this->AddHeader($columnheaders);
		}
	}
	
	public function __tostring()
	{
		$this->AppendRow($this->_nextrow, null);
		
		return parent::__tostring();
	}
	
	private function GetRowClass($i, $isheader=false)
	{
		// reset alternatecell, so every row starts with the same type of cell
		if ($this->_alternatecell !== null) { $this->_alternatecell = true; }
		
		$value = 'table_row';
		if ($i === 0) { $value .= ' table_first_row'; }
		if ($isheader) { $value .= ' table_header_row'; }
		else
		{
			reiZ::FlipBoolean($this->_alternaterow);
			if ($this->_alternaterow) { $value .= ' table_alternate_row'; }
		}
		if ($i === null) { $value .= ' table_last_row'; }
		
		return $value;
	}
	
	private function GetCellClass($i, $last, $isheader=false)
	{
		$value = 'table_cell';
		reiZ::FlipBoolean($this->_alternatecell);
		if ($i === 0) { $value .= ' table_first_cell'; }
		if ($isheader)
		{
			$value .= ' table_header_cell';
			if ($this->_alternatecell) { $value .= ' table_alternate_header_cell'; }
		}
		if ($this->_alternatecell) { $value .= ' table_alternate_cell'; }
		if ($i == $last) { $value .= ' table_last_cell'; }
		return $value;
	}
	
	private function AddHeader($row)
	{
		$header = new HtmlElement('tr', 'class="'.$this->GetRowClass($this->_rows, true).'"');
		
		$rowsize = sizeof($row) -1;
		for ($i = 0; $i <= $rowsize; $i++)
		{
			$header->AddChild(new HtmlElement('td', 'class="'.$this->GetCellClass($i, $rowsize, true).'"', $row[$i]));
		}
		
		$this->AddChild($header);
		$this->_rows++;
	}
	
	public function AddRow($row)
	{
		if ($this->_nextrow != null)
		{
			$this->AppendRow($this->_nextrow, $this->_rows);
			$this->_rows++;
		}
		
		$this->_nextrow = $row;
	}
	
	private function AppendRow($row, $i)
	{
		$line = new HtmlElement('tr', 'class="'.$this->GetRowClass($i).'"');
		
		$rowsize = sizeof($row) -1;
		for ($i = 0; $i <= $rowsize; $i++)
		{
			if (is_a($row[$i], 'HtmlElement'))
			{
				$line->AddChild(new HtmlElement('td', 'class="'.$this->GetCellClass($i, $rowsize).'"', EMPTYSTRING, $row[$i]));
			}
			else
			{
				$line->AddChild(new HtmlElement('td', 'class="'.$this->GetCellClass($i, $rowsize).'"', $row[$i]));
			}
		}
		
		$this->AddChild($line);
	}
}
?>