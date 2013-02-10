<?php
/**
 * JOULES data controller
 *
 * @author Henri de Jong
 * 
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Debug;
use Application\Model\MeasurementTable;

class DataController extends AbstractActionController
{
    protected $measurementTable;
    
    protected $wattPerRotate;
    
    public function __construct()
    {
        $this->wattPerRotate = (2 + (2/3));
    }
    public function indexAction()
    {
        $options = array();
        
        if (!is_null($this->params()->fromQuery('fromDate'))) {
            $fromDate = new \DateTime($this->params()->fromQuery('fromDate'));
        } else {
            $fromDate = new \DateTime();
        }
        $options['fromDate'] = $fromDate;

        if (!is_null($this->params()->fromQuery('toDate'))) {
            $toDate = new \DateTime($this->params()->fromQuery('toDate'));
            $options['toDate'] = $toDate;
        }

        $measurements = $this->getMeasurementTable()->findMeasurements($options);

        $sum = 0;
        $counter = 0;
        $max = 0;

        foreach ($measurements as $measurement) {
            $measurementArray[] = array($measurement->getTimestamp(), (int) $measurement->getWatt());

            $sum += (int) $measurement->getWatt();
            $counter++;
            
            if ($measurement->getWatt() > $max) {
                $max = $measurement->getWatt();
            }
        }

        $avg = round($sum / $counter);
        $kwh = round($this->wattPerRotate * $counter)/1000;

        $result = new JsonModel(array(array($measurementArray), array('max' => $max, 'avg' => $avg, 'sum' => $kwh)));
 
        return $result;
    }
    
    public function statsAction()
    {
        $options = array();
        if (!is_null($this->params()->fromQuery('fromDate'))) {
            $fromDate = new \DateTime($this->params()->fromQuery('fromDate'));
            $options['fromDate'] = $fromDate;
        }

        if (!is_null($this->params()->fromQuery('toDate'))) {
            $toDate = new \DateTime($this->params()->fromQuery('toDate'));
            $options['toDate'] = $toDate;
        }

        $dates = $this->getMeasurementTable()->findRotates($options);

        $sum = 0;
        $counter = 0;
        $max = 0;
        $result = array();

        foreach ($dates as $date) {
            $measurementArray[] = array($date['date'], (int) $date['rotates'] * $this->wattPerRotate);
            $sum += (int) $date['rotates'];
            $counter++;
            if ((int) $date['rotates'] > $max) {
                $max = (int) $date['rotates'];
            }
        }

        $sum = round($sum * $this->wattPerRotate, 0);
        $max = round($max * $this->wattPerRotate, 0);
        $avg = round($sum / $counter);

        $kwh = $sum / 1000;

        $result = new JsonModel(array(array($measurementArray), array('max' => $max, 'avg' => $avg, 'sum' => $kwh)));
 
        return $result;
    }
    
    protected function getMeasurementTable()
    {
        if (!$this->measurementTable) {
            $sm = $this->getServiceLocator();
            $this->measurementTable = $sm->get('Application\Model\MeasurementTable');
        }
        return $this->measurementTable;
    }
}
