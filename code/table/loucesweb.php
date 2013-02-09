<?php
/**
 * @package    LouCesWeb
 * @subpackage Code
 * @author     Nikolai Plath {@link https://github.com/elkuku}
 * @author     Created on 08-Feb-2013
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

/**
 * _LouCesWeb table class.
 */
class LcesTableLoucesweb extends JTable
{
    private $pk = 'loucesweb_id';

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  $db  Database driver object.
     */
    public function __construct($db)
    {
        parent::__construct('#__loucesweb', $this->pk, $db);
    }

    /**
     * Get the primary key name.
     *
     * @return string
     */
    public function getPk()
    {
        return $this->pk;
    }

    /**
     * Magic get method.
     *
     * @param string $var
     *
     * @return mixed|string
     *
     * @throws UnexpectedValueException
     */
    public function __get($var)
    {
        switch($var)
        {
            case 'pk':
                return $this->pk;
                break;

            case 'id':
                return $this->{$this->pk};
                break;

            default:
                throw new UnexpectedValueException('Undefined property:'.__CLASS__.'::'.$var);
        }
    }

    /**
     * Method to bind an associative array or object to the JTable instance.This
     * method only binds properties that are publicly accessible and optionally
     * takes an array of properties to ignore when binding.
     *
     * @param   mixed  $src     An associative array or object to bind to the JTable instance.
     * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
     *
     * @return  boolean  True on success.
     *
     * @link    http://docs.joomla.org/JTable/bind
     * @since   11.1
     */
    public function bind($src, $ignore = array())
    {
        /* @var JInput $src */
        if(is_array($src))
            return parent::bind($src, $ignore);

        $this->{$this->pk} = $src->getInt('id');
        $this->{$this->pk} = $this->{$this->pk} ? : null;
        $this->a = $src->getString('a');
        $this->b = $src->getString('b');
        $this->c = $src->getString('c');

        return true;
    }
}
