
<h1>Blog articles</h1>
<p><?= $this->Html->link('Add Article', ['action' => 'add']) ?></p>
<?= $this->Form->create("",['type'=>'get'])?>
<?= $this->Form->control('keyword',['default'=>$this->request->query('keyword')]); ?>
<button>search</button>
<?= $this->Form->end() ?>
<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>

<!-- Here's where we loop through our $articles query object, printing out article info -->

    <?php foreach ($articles as $article): ?>
    <tr>
        <td><?= $article->id ?></td>
        <td>
            <?= $this->Html->link($article->title, ['action' => 'view', $article->id]) ?>
        </td>
        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
        <td>
            <?= $this->Form->postLink(
                'Delete',
                ['action' => 'delete', $article->id],
                ['confirm' => 'Are you sure?'])
            ?>
            <?= $this->Html->link('Edit', ['action' => 'edit', $article->id]) ?>
        </td>
    </tr>
    <?php endforeach; ?>
    

</table>
<div class="pagination">
    <?php echo $this->paginator->prev(); ?>
    <?php echo $this->paginator->numbers(); ?>
    <?php echo $this->paginator->next(); ?>
    </div>
<style>
.pagination li(display:inline; magin-left:5px;)
</style>