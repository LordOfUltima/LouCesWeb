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
 * LouCesWeb _LouCesWeb model.
 *
 * @package     LouCesWeb
 * @subpackage  Model
 */
class LcesModelLoucesweb extends JModelBase
{
    /**
     * @var LcesTableLoucesweb
     */
    private $table;

    /**
     * @var JDatabase
     */
    private $db;

    /**
     * @var int
     */
    private $id = 0;

    /**
     * Instantiate the model.
     *
     * @param   JRegistry  $state  The model state.
     */
    public function __construct(JRegistry $state = null)
    {
        $this->db = JFactory::getDbo();
        $this->id = JFactory::getApplication()->input->getInt('id');
        $this->table = new LcesTableLoucesweb($this->db);

        parent::__construct($state);
    }

    /**
     * Get tha data.
     *
     * @return LcesTableLoucesweb
     *
     * @throws UnexpectedValueException
     */
    public function getData()
    {
        if(0 === $this->id)
            return $this->table;

        if(false === $this->table->load($this->id))
            throw new UnexpectedValueException(sprintf('%s - Failed to load the data for id: %s'
                , __METHOD__, $this->id));

        return $this->table;
    }

    /**
     * Save the data.
     *
     * @return bool
     *
     * @throws UnexpectedValueException
     */
    public function save()
    {
        $input = JFactory::getApplication()->input;

        if(false === $this->table->save($input))
            throw new UnexpectedValueException($this->table->getError(), 1);

        return true;
    }

    /**
     * Delete data.
     *
     * @return mixed
     */
    public function delete()
    {
        $db = JFactory::getDbo();

        $table = new LcesTableLoucesweb($db);

        $input = JFactory::getApplication()->input;

        return $table->delete($input->getInt('id'));
    }
}
