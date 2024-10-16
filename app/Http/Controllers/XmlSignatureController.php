<?php

namespace App\Http\Controllers;

use App\Services\XmlSignatureService;
use Illuminate\Http\Request;

class XmlSignatureController extends Controller
{
    protected $xmlSignatureService;

    public function __construct(XmlSignatureService $xmlSignatureService)
    {
        $this->xmlSignatureService = $xmlSignatureService;
    }

    /**
     * Handle XML signature verification.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        // Get the XML content and public key from the request
        $xmlContent = $request->input('xml_content');
        $publicKey = $request->input('public_key');

        // Verify the signature
        try {
            $isValid = $this->xmlSignatureService->verifyXmlSignature($xmlContent, $publicKey);

            if ($isValid) {
                return response()->json(['message' => 'Signature is valid.']);
            } else {
                return response()->json(['message' => 'Signature is invalid.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
