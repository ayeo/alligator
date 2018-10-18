<?php
namespace Ayeo\Validator2\Constraint;

use Libs\Form;

class LowerThanField extends \Ayeo\Validator2\Constraint\AbstractConstraint
{
	/**
	 * @var string
	 */
	private $field;

	/**
	 * @param $field
	 */
	public function __construct($field)
	{
		$this->field = $field;
	}

	public function run($value)
	{
        $anotherFieldValue = $this->getFieldValue($this->field);
		if ($value >= $anotherFieldValue)
		{
			$this->addError('must_be_lower_than_'.$this->field);
		}
	}
}