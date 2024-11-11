<?php
declare(strict_types=1);

namespace App\Utility;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Aws\Exception\CredentialsException;
use Aws\S3\S3Client;
use Cake\Core\Configure;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\RejectedPromise;

class S3
{
    public static function listDirectory(string $s3ObjPrefix, bool &$isTruncated): array | null
    {
        $client = self::_getClient();
        $bucketName = self::_getBucketName();

        if(!$client->doesObjectExistV2($bucketName, $s3ObjPrefix)) {
            return null;
        }

        $callResult = $client->listObjectsV2([
            'Bucket' => $bucketName,
            'Prefix' => $s3ObjPrefix,
        ]);
        $isTruncated = $callResult['IsTruncated'];

        // see format here : https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#listobjectsv2
        return $callResult['Contents'];
    }

    public static function presignedUrlForDownload(string $s3ObjKey): string | null
    {
        $client = self::_getClient();
        $bucketName = self::_getBucketName();

        if(!$client->doesObjectExistV2($bucketName, $s3ObjKey)) {
            return null;
        }

        $filename = preg_replace('/.*\//i', '', $s3ObjKey);
        if(empty($filename) || !is_string($filename)) {
            $filename = $s3ObjKey; // I don't know when this occurs
        }
        $filename = str_replace('"', '', $filename);

        // see https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/s3-presigned-url.html
        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucketName,
            'Key' => $s3ObjKey,
            'ResponseContentDisposition' => 'attachment; filename="'.$filename.'"',
        ]);
        $request = $client->createPresignedRequest($command, '+5minutes');
        return $request->getUri()->__toString();
    }

    private static function _getClient(): S3Client
    {
        $config = Configure::read('AWS.S3');
        unset($config['bucketName']);
        unset($config['accessKey']);
        unset($config['accessSecret']);
        // "region" is directly passed to the AWS SDK

        if(isset($config['endpoint'])) {
            $config['use_path_style_endpoint'] = true;
        }
        $config['version'] = '2006-03-01';
        $config['signature_version'] = 'v4';
        $config['credentials'] = self::_credentials();
        return new S3Client($config);
    }

    private static function _getBucketName(): string
    {
        return Configure::readOrFail('AWS.S3.bucketName');
    }

    private static function _credentials(): callable|\Closure
    {
        return CredentialProvider::memoize(
            CredentialProvider::chain(
                self::credentialProvider(),
                // see https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials_provider.html#instanceprofile-provider
                CredentialProvider::instanceProfile(),
            )
        );
    }
    public static function credentialProvider(): callable
    {
        // see https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials_provider.html#creating-a-custom-provider
        // see https://docs.aws.amazon.com/aws-sdk-php/v3/api/function-GuzzleHttp.Promise.promise_for.html
        return function () {
            $key = Configure::read('AWS.S3.accessKey');
            $secret = Configure::read('AWS.S3.accessSecret');
            if ($key && $secret) {
                return Promise\Create::promiseFor(
                    new Credentials($key, $secret)
                );
            }

            return new RejectedPromise(new CredentialsException('There is no keys configured for AWS S3'));
        };
    }

}
