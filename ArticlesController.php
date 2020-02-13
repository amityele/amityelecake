<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;

class ArticlesController extends AppController
{

    public $paginate = [
        'fields' => ['Articles.id','Articles.title', 'Articles.created'],
        'limit' => 5 ,
        'order' => [
            'Articles.title' => 'asc'
        ]
    ];

    // public function initialize()
    // {
    //     parent::initialize();
    //     $this->loadComponent('Paginator');
    // }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // $this->viewBuilder()->layout('default');
        $this->viewBuilder()->layout('default');          

    }

    public function index()
    {
        $keyword =$this->request->query('keyword');
        if(!empty($keyword))
        {
            $this->paginate = [
                'conditions'=>['title LIKE '=>'%'.$keyword.'%']  
            ];
        }

       $articles = $this->paginate($this->Articles);
       $this->set(compact('articles'));
        $this->set('_serialize', ['Articles']);

    }

    public function view($id)
    {
        $article = $this->Articles->get($id);
        $this->set(compact('article'));
    }
    
    
    // public function add()
    // {
    //     $article = $this->Articles->newEntity();
    //     if ($this->request->is('post')) {
    //         // Prior to 3.4.0 $this->request->data() was used.
    //         $article = $this->Articles->patchEntity($article, $this->request->getData());
    //         if ($this->Articles->save($article)) {
    //             $this->Flash->success(__('Your article has been saved.'));
    //             return $this->redirect(['action' => 'index']);
    //         }
    //         $this->Flash->error(__('Unable to add your article.'));
    //     }
    //     $this->set('article', $article);
    // }
    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            // Prior to 3.4.0 $this->request->data() was used.
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            // Added this line
            $article->user_id = $this->Auth->user('id');
            // You could also do the following
            //$newData = ['user_id' => $this->Auth->user('id')];
            //$article = $this->Articles->patchEntity($article, $newData);
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);
    
        // Just added the categories list to be able to choose
        // one category for an article
        // $categories = $this->Articles->Categories->find('treeList');
        // $this->set(compact('categories'));
    }
   
public function edit($id = null)
{
    $article = $this->Articles->get($id);
    if ($this->request->is(['post', 'put'])) {
        // Prior to 3.4.0 $this->request->data() was used.
        $this->Articles->patchEntity($article, $this->request->getData());
        if ($this->Articles->save($article)) {
            $this->Flash->success(__('Your article has been updated.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('Unable to update your article.'));
    }

    $this->set('article', $article);
}

    // / src/Controller/ArticlesController.php//

        public function isAuthorized($user)
        {
            // All registered users can add articles
            // Prior to 3.4.0 $this->request->param('action') was used.
            if ($this->request->getParam('action') === 'add') {
                return true;
            }

            // The owner of an article can edit and delete it
            // Prior to 3.4.0 $this->request->param('action') was used.
            if (in_array($this->request->getParam('action'), ['edit', 'delete'])) {
                // Prior to 3.4.0 $this->request->params('pass.0')
                $articleId = (int)$this->request->getParam('pass.0');
                if ($this->Articles->isOwnedBy($articleId, $user['id'])) {
                    return true;
                }
            }

            return parent::isAuthorized($user);
        }

        // src/Controller/ArticlesController.php

    public function delete($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The article with id: {0} has been deleted.', h($id)));
            return $this->redirect(['action' => 'index']);
        }
    }
}