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
    <h2>Objects of <?= h(empty($prefix) ? 'root directory' : $prefix) ?></h2>
    <ul>
        <?php
        $isEmpty = true;
        if(!empty($result['CommonPrefixes'])) {
            foreach($result['CommonPrefixes'] as $prefixObj) {
                $subPrefix = $prefixObj['Prefix'];
                echo '<li>';
                echo $this->Html->link($subPrefix, '/index/'.$subPrefix.'/');
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
                    echo ' ';
                    echo '<button'
                        .' data-key="' . $key . '"'
                        .' onclick="document.location.href='."'/download/".$key."'".'"'
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
