<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Expression;

class MeasurementTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function findMeasurements($options)
    {
        if (isset($options['toDate'])) {
            $where = new Where();
            $where->between('timestamp', $options['fromDate']->format('Y-m-d H:i:s'), $options['toDate']->format('Y-m-d H:i:s'));
            $resultSet = $this->tableGateway->select($where);
        } else {
            $value = $options['fromDate']->format('Y-m-d') . '%';
            $where = new Where();
            $where->like('timestamp', $value);
            $resultSet = $this->tableGateway->select($where);
        }

        return $resultSet;
    }
    
    public function findRotates($options)
    {
        //$query = '
        //SELECT DATE_FORMAT(timestamp, "%Y-%m-%d") as date, count(meting) as rotates
        //FROM ' . $tablename . ' 
        //GROUP BY DATE_FORMAT(timestamp, "%Y-%m-%d")
        //ORDER BY timestamp DESC';
        
        if (isset($options['toDate'])
            && isset($options['fromDate'])
        ) {
            $where = new Where();
            $where->between('timestamp', $options['fromDate']->format('Y-m-d H:i:s'), $options['toDate']->format('Y-m-d H:i:s'));
        }

        $sql = new Sql($this->tableGateway->getAdapter());
        $select = $sql->select();
        //$select = new Select();
        $select->columns(
            array(
                'date' => new Expression('DATE_FORMAT(timestamp, "%Y-%m-%d")'), 
                'rotates' => new Expression('count(meting)')
            ), 
            FALSE
        );
        $select->from('kwhmetingen');
        $select->group(new Expression('DATE_FORMAT(timestamp, "%Y-%m-%d")'));
        $select->order('timestamp');

        $selectString = $sql->prepareStatementForSqlObject($select);
        $results = $selectString->execute();

        return $results;
    }

    public function getMeasurement($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('meting' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

}