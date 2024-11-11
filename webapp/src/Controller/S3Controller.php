<?php
declare(strict_types=1);

namespace App\Controller;

use App\Utility\S3;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

class S3Controller extends AppController
{
    public function index(...$prefixes): ?Response {
        $prefix = '';
        if(!empty($prefixes)) {
            $prefix = implode('/', $prefixes) . '/';
        }
        if(stripos($prefix, '../') !== false || str_ends_with($prefix, '..')) {
            throw new BadRequestException();
        }
        $result = S3::listDirectoryWithCache($prefix);
        //$result = S3::listDirectory($prefix);
        $this->set(compact('prefix', 'result'));
        return null;
    }

    public function download(...$keyPaths): ?Response {
        if(empty($keyPaths)) {
            throw new NotFoundException();
        }
        $key = implode('/', $keyPaths);
        if(stripos($key, '../') !== false) {
            throw new BadRequestException();
        }
        $presignedURL = S3::presignedUrlForDownload($key);
        return $this->redirect($presignedURL);
    }
}
