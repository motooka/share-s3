<?php
return [
    'site_owner_name' => '', // this appears in the footer. If you keep this blank, the site will say "Contents(files) are managed by owner of this site"
    'debug' => filter_var(env('DEBUG', false), FILTER_VALIDATE_BOOLEAN),
    'Security' => [
        'salt' => env('SECURITY_SALT', '__SALT__'),
    ],
    'AWS' => [
        'S3' => [
            'bucketName' => '', // required
            'region' => 'ap-northeast-1', // required : see region list on https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/Concepts.RegionsAndAvailabilityZones.html
            'endpoint' => '', // required if you use S3-compatible storages

            // keep the following items blank if you use IAM Role(Instance Profile)
            'accessKey' => '', // required if you use IAM user
            'accessSecret' => '', // required if you use IAM user
        ],
    ],
];
