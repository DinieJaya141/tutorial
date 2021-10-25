<?php
//namespace ;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
//use Merchandise;

class MerchandiseController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for merchandise
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Merchandise', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $merchandise = Merchandise::find($parameters);
        if (count($merchandise) == 0) {
            $this->flash->notice("The search did not find any merchandise");

            $this->dispatcher->forward([
                "controller" => "merchandise",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $merchandise,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a merchandise
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $merchandise = Merchandise::findFirstByid($id);
            if (!$merchandise) {
                $this->flash->error("merchandise was not found");

                $this->dispatcher->forward([
                    'controller' => "merchandise",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $merchandise->id;

            $this->tag->setDefault("id", $merchandise->id);
            $this->tag->setDefault("name", $merchandise->name);
            $this->tag->setDefault("details", $merchandise->details);
            $this->tag->setDefault("price", $merchandise->price);
            $this->tag->setDefault("image", $merchandise->image);
            
        }
    }

    /**
     * Creates a new merchandise
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "merchandise",
                'action' => 'index'
            ]);

            return;
        }

        $merchandise = new Merchandise();
        $merchandise->name = $this->request->getPost("name");
        $merchandise->details = $this->request->getPost("details");
        $merchandise->price = $this->request->getPost("price");
        $merchandise->image = $this->request->getPost("image");
        

        if (!$merchandise->save()) {
            foreach ($merchandise->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "merchandise",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("merchandise was created successfully");

        $this->dispatcher->forward([
            'controller' => "merchandise",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a merchandise edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "merchandise",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $merchandise = Merchandise::findFirstByid($id);

        if (!$merchandise) {
            $this->flash->error("merchandise does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "merchandise",
                'action' => 'index'
            ]);

            return;
        }

        $merchandise->name = $this->request->getPost("name");
        $merchandise->details = $this->request->getPost("details");
        $merchandise->price = $this->request->getPost("price");
        $merchandise->image = $this->request->getPost("image");
        

        if (!$merchandise->save()) {

            foreach ($merchandise->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "merchandise",
                'action' => 'edit',
                'params' => [$merchandise->id]
            ]);

            return;
        }

        $this->flash->success("merchandise was updated successfully");

        $this->dispatcher->forward([
            'controller' => "merchandise",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a merchandise
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $merchandise = Merchandise::findFirstByid($id);
        if (!$merchandise) {
            $this->flash->error("merchandise was not found");

            $this->dispatcher->forward([
                'controller' => "merchandise",
                'action' => 'index'
            ]);

            return;
        }

        if (!$merchandise->delete()) {

            foreach ($merchandise->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "merchandise",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("merchandise was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "merchandise",
            'action' => "index"
        ]);
    }

}
