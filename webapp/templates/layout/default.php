<?php
/**
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        Share S3
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build('/') ?>">Share S3</a>
        </div>
        <div class="top-nav-links">
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
        <ul>
            <li>
                Contents(files) are managed by
                <?php
                $siteOwnerName = Configure::read('site_owner_name');
                if(empty($siteOwnerName)) {
                    echo 'the owner of this site';
                }
                else {
                    echo h($siteOwnerName);
                }
                ?>
            </li>
            <li>Application : Copyright &copy; T.MOTOOKA</li>
            <li>Source Code of the Application : <a href="https://github.com/motooka/share-s3" target="_blank" rel="noopener">available on GitHub</a></li>
            <li>Powered by <a href="https://cakephp.org/" target="_blank" rel="noopener noreferrer">CakePHP</a></li>
        </ul>
    </footer>
</body>
</html>
