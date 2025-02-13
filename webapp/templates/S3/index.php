<?php
/**
 * @var \App\View\AppView $this
 * @var string $prefix
 * @var array $result
 */

function humanReadableBytes(int $bytes): string {
    if($bytes < 1024) {
        return number_format($bytes) . 'bytes';
    }
    if($bytes < 1024 * 1024) {
        return number_format($bytes / 1024, 1) . 'KB';
    }
    if($bytes < 1024 * 1024 * 1024) {
        return number_format($bytes / 1024 / 1024, 1) . 'MB';
    }

    return number_format($bytes / 1024 / 1024 / 1024, 1) . 'GB';
}

?>
<div>
    <nav>
        <?php
        $paths = explode('/', $prefix);
        $accumulation = '';
        echo $this->Html->link('root directory', '/index/');

        foreach($paths as $path) {
            $accumulation .= $path . '/';
            echo ' / ';
            if($accumulation === $prefix) {
                echo h($path);
            }
            else {
                echo $this->Html->link($path, '/'.$accumulation);
            }
        }
        ?>
    </nav>
    <h2>Objects of <?= h(empty($prefix) ? 'root directory' : $prefix) ?></h2>
    <ul>
        <?php
        $isEmpty = true;
        if(!empty($result['CommonPrefixes'])) {
            foreach($result['CommonPrefixes'] as $prefixObj) {
                $childFullPrefix = $prefixObj['Prefix'];
                $dirName = mb_ereg_replace('.*/', '', mb_substr($childFullPrefix, 0, mb_strlen($childFullPrefix)-1));
                echo '<li>';
                echo $this->Html->link($dirName, '/index/'.$childFullPrefix.'/');
                echo '</li>';
                $isEmpty = false;
            }
        }
        if(!empty($result['Contents'])) {
            foreach($result['Contents'] as $contentObj) {
                $key = $contentObj['Key'];
                $fileName = mb_ereg_replace('.*\\/', '', $key);
                /**
                 * @var Aws\Api\DateTimeResult $lastModified
                 */
                $lastModified = $contentObj['LastModified'];
                $size = $contentObj['Size'];
                $sizeStr = humanReadableBytes($size);

                echo '<li>';
                echo h($fileName);
                echo ' (' . $sizeStr . ')';
                if(stripos($key, '"') === false) {
                    $url = \Cake\Routing\Router::url('/download/'.$key);
                    $onclick = 'document.location.href='."'" . $url . "'";
                    //echo $onclick;
                    echo ' ';
                    echo '<button'
                        .' data-key="' . $key . '"'
                        .' onclick="' . $onclick . '"'
                        .'>Download</button>';
                }
                else {
                    echo ' ** this file cannot be downloaded because the name has special characters.';
                }
                echo '</li>';
                $isEmpty = false;
            }
        }
        ?>
    </ul>
    <?php
    if($isEmpty) {
        echo '<div class="message warning">';
        echo 'No objects found.';
        echo '</div>';
    }
    if(\Cake\Core\Configure::read('debug')) {
        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }
    ?>
</div>
