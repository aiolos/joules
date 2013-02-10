<?php
/**
 * JOULES index controller
 * 
 * @author Henri de Jong
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $date = time();
        
        $viewModel = new ViewModel();
        $viewModel->shownDate = date('d-m-Y', $date);
        $viewModel->nextHour = date('Hi', $date);
        
        return $viewModel;
    }
    
    public function statsAction()
    {
        $date = time();
        
        $viewModel = new ViewModel();
        $viewModel->kwhPrijs = 0.23;
        
        return $viewModel;
    }
}
