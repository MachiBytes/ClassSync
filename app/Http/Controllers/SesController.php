<?php

namespace App\Http\Controllers;

use App\Helpers\VerificationEmailDesigner;
use Aws\Exception\AwsException;

class SesController extends Controller
{
    public function listTemplates()
    {
        try {
            $result = VerificationEmailDesigner::listTemplates();

            return $result;
        } catch (AwsException $e) {
            // Output error message if fails
            return 'Error: ' . $e->getAwsErrorMessage();
        }
    }

    public function createTemplate()
    {
        try {
            $result = VerificationEmailDesigner::createTemplate();

            return response()->json([
                'status' => true,
                'message' => $result
            ], 200);
        } catch (AwsException $e) {
            // Output error message if fails
            return 'Error: ' . $e->getAwsErrorMessage();
        }
    }

    public function deleteTemplate()
    {
        try {
            $result = VerificationEmailDesigner::deleteTemplate();

            return response()->json([
                'status' => true,
                'message' => $result
            ], 200);
        } catch (AwsException $e) {
            // Output error message if fails
            return 'Error: ' . $e->getAwsErrorMessage();
        }
    }
}
