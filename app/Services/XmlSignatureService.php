<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Exception;

class XmlSignatureService
{
    /**
     * Verifies the XML signature using the provided public key.
     *
     * @param string $xmlContent The XML content to verify.
     * @param string $publicKey The public key in PEM format.
     * @return bool True if the signature is valid, false otherwise.
     * @throws Exception if any step of the verification fails.
     */
    public function verifyXmlSignature(string $xmlContent, string $publicKey): bool
    {
        // Load the XML content
        $dom = new DOMDocument();
        $dom->loadXML($xmlContent);

        // Extract the Signature node
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');

        // Get the SignedInfo node and canonicalize it
        $signedInfoNode = $xpath->query('//ds:SignedInfo')->item(0);
        if (!$signedInfoNode) {
            throw new Exception("SignedInfo element not found.");
        }
        $canonicalSignedInfo = $signedInfoNode->C14N(true, false);

        // Log the canonical signed info
        \Log::info('Canonical SignedInfo: ' . $canonicalSignedInfo);

        // Extract the SignatureValue (base64 encoded)
        $signatureValueNode = $xpath->query('//ds:SignatureValue')->item(0);
        if (!$signatureValueNode) {
            throw new Exception("SignatureValue element not found.");
        }
        // dd($signatureValueNode->textContent);
        $signatureValue = base64_decode($signatureValueNode->textContent);

        // Log the signature value
        \Log::info('Signature Value: ' . base64_encode($signatureValue));

        // Use the provided public key to verify the signature
        $publicKeyResource = openssl_pkey_get_public($publicKey);
        if (!$publicKeyResource) {
            throw new Exception("Invalid public key.");
        }

        // Verify the signature
        $isValid = openssl_verify($canonicalSignedInfo, $signatureValue, $publicKeyResource, OPENSSL_ALGO_SHA256);

        // Log the verification result
        \Log::info('Verification Result: ' . $isValid);

        // Free the public key resource
        openssl_free_key($publicKeyResource);

        return $isValid === 1;
    }

}
