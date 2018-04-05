<?php





abstract class DomainObjectAbstract
{
    protected $_id = null;

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        if (!is_null($this->_id)) {
            throw new Exception('ID is immutable');
        }
        return $this->_id = $id;
    }
}
