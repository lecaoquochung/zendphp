<?php
namespace Album\Controller;

use Album\Model\AlbumTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    private $table;

    // construct table model
    public function __construct(AlbumTable $table)
    {
        $this->table = $table;
    }

    // index
    public function indexAction()
    {
        return new ViewModel(array(
             'albums' => $this->table->fetchAll(),
         ));
    }

    // add
    public function addAction()
    {
    }

    // edit
    public function editAction()
    {
    }

    // delete
    public function deleteAction()
    {
    }
}
