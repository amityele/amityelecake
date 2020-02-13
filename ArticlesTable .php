<?php 
namespace App\Model\Table;
use Cake\ORM\Table;

class ArticlesTable extends Table
{
   
    
    
// src/Model/Table/ArticlesTable.php
public function initialize(array $config)
{
    $this->addBehavior('Timestamp');
    // Just add the belongsTo relation with CategoriesTable
    $this->belongsTo('users', [
        'foreignKey' => 'id',
    ]);
}

    public function isOwnedBy($articleId, $userId)
    {
        return $this->exists(['id' => $articleId, 'user_id' => $userId]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('title')
            ->requirePresence('title')
            ->notEmpty('body')
            ->requirePresence('body');

        return $validator;
    }
}


?>