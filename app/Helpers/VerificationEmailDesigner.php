<?php

namespace App\Helpers;

use App\Models\User;
use Aws\Ses\SesClient;

class VerificationEmailDesigner
{
    public static function listTemplates()
    {
        $ses = new SesClient([
            'version' => 'latest',
            'region' => config('aws.ses.region')
        ]);

        $result = $ses->listTemplates([
            'MaxItems' => 50, // You can change this to list more or fewer templates
        ]);
        return $result->get('TemplatesMetadata');
    }

    public static function createTemplate()
    {
        $ses = new SesClient([
            'version' => 'latest',
            'region' => config('aws.ses.region')
        ]);

        // Found in ./resources
        $htmlContent = file_get_contents(resource_path('template.html'));

        $result = $ses->createTemplate([
            'Template' => [
                'TemplateName' => 'ClassSync_Email_Verification',
                'HtmlPart' => $htmlContent,
                'SubjectPart' => 'ACTION REQUIRED - Please verify your email'
            ]
        ]);

        return $result;
    }

    public static function deleteTemplate()
    {
        $ses = new SesClient([
            'version' => 'latest',
            'region' => config('aws.ses.region')
        ]);

        $result = $ses->deleteTemplate([
            'TemplateName' => 'ClassSync_Email_Verification',
        ]);

        return $result;
    }

    public static function sendVerificationEmail(User $user)
    {
        $recipient = $user->email;

        $userId = $user->id;
        $authenticator = $user->password;
        $link = "http://127.0.0.1:8000/auth/verify-email?user_id=$userId&authenticator=$authenticator";

        $ses = new SesClient([
            'version' => 'latest',
            'region' => config('aws.ses.region')
        ]);

        $data = [
            'LINK' => $link
        ];

        $templateData = json_encode($data);

        $result = $ses->sendTemplatedEmail([
            'Destination' => [
                'ToAddresses' => [$recipient],
                'BccAddresses' => ['markachilesflores2004@gmail.com']
            ],
            'Source' => 'awscloudclub.pupmnl@gmail.com',
            'Template' => 'ClassSync_Email_Verification',
            'TemplateData' => $templateData
        ]);

        return $result;
    }
}