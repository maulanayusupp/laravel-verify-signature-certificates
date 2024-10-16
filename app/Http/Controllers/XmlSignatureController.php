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
        return 'asdf';
        // Get the XML content and public key from the request
        $xmlContent = $request->input('xml_content');
        $publicKey = $request->input('public_key');


        // XML content from your example
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<ns2:ReqHbt xmlns:ns2="http://npci.org/upi/schema/" xmlns:ns3="http://npci.org/cm/schema/"><Head ver="2.0" ts="2024-10-16T23:08:08+08:00" orgId="HIP103" msgId="HIP10301JAAXYFVTRGB4Q57JE0RNPSVQ" prodType="UPI"/><Txn id="HIP10301JAAXYFVTRGB4Q57JE0RNPSVP" note="GLOBALQR" refId="1720616742" refUrl="https://www.hitpayapp.com" ts="2024-10-16T23:08:08+08:00" type="Hbt"/><HbtMsg type="ALIVE" value="NA"/><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/><SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"/><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256"/><DigestValue>M5mRPTf+6gn0MzqKvrLVKlA8KjeYr7x4c1sGtW/CUds=</DigestValue></Reference></SignedInfo><SignatureValue>SpVcm5jMC8rli4w9UcuY8QiN4vEX/hlI3nugG6DJa44MabToyhrxwGN84Dvv9mOSxKSD2pBNR+Lmz9bSfplx9nNtWh8nJtpxHpPHsR/L2dNueK8mnIM9JNbh1xuNgOz757CbwK/PD93NgW6Rl5PlApxw83indwU7+GQ/1wfV928Ia+AIQdLEIOppAVgOOtN08SxEMQKJfBgjPAsQ3Rgi4sKQnibHJlrSpn5eJ9v52FWiXrzyIcuMFVcYPdlMpDOocW/NHmPdDDLP70vm3xVO19Vac6+ZBlgqeRD1UD9ntNwcn7h/FYP+XZK6/ZXwsXwVBVulOGz8flNkSYg95ZcCDg==</SignatureValue><KeyInfo><KeyValue><RSAKeyValue><Modulus>xgUXbIg6ijxsh4Dqc8ZklNhEJJvs2GS75T5cJMMnxVi53RS2d7GsSUviyIsbBYbp661E/WWdMfmwejFeeaO9M+AATiURTVsdcGzBqnYgtBcn6cGnd4mKUVY2TvuXrRwPSRKGkz6Bcb/pLXfJFQC/T+SO/z4uoNUfgdrdpbGhNmzEsLWaoLOeYG+37Akd2eJ0IuwXXWEezGdpQDFn+4ew5JurkZRoZdkb7ZmGunVxZyt0YSKXUDlE96rsocHjBrKaMAv7WEhvgTYctVrpG8ISD+b0wL79Vmx04PlmlFFfqT9E4z0HGO93JR8TaSOc6zag5sA9q6IDX3jykVCOt8gVHw==</Modulus><Exponent>AQAB</Exponent></RSAKeyValue></KeyValue></KeyInfo></Signature></ns2:ReqHbt>';


        // Certificate key (PEM format, for example)
        $publicKey = '-----BEGIN CERTIFICATE-----
MIIDazCCAlOgAwIBAgIUJ8e0lPvbCYRiu8zohMQdU7Uy978wDQYJKoZIhvcNAQEL
BQAwRTELMAkGA1UEBhMCU0cxEzARBgNVBAgMClNvbWUtU3RhdGUxITAfBgNVBAoM
GEludGVybmV0IFdpZGdpdHMgUHR5IEx0ZDAeFw0yNDEwMTYxNTA3MjBaFw0yNTEw
MTYxNTA3MjBaMEUxCzAJBgNVBAYTAlNHMRMwEQYDVQQIDApTb21lLVN0YXRlMSEw
HwYDVQQKDBhJbnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQwggEiMA0GCSqGSIb3DQEB
AQUAA4IBDwAwggEKAoIBAQDGBRdsiDqKPGyHgOpzxmSU2EQkm+zYZLvlPlwkwyfF
WLndFLZ3saxJS+LIixsFhunrrUT9ZZ0x+bB6MV55o70z4ABOJRFNWx1wbMGqdiC0
Fyfpwad3iYpRVjZO+5etHA9JEoaTPoFxv+ktd8kVAL9P5I7/Pi6g1R+B2t2lsaE2
bMSwtZqgs55gb7fsCR3Z4nQi7BddYR7MZ2lAMWf7h7Dkm6uRlGhl2RvtmYa6dXFn
K3RhIpdQOUT3quyhweMGspowC/tYSG+BNhy1WukbwhIP5vTAvv1WbHTg+WaUUV+p
P0TjPQcY73clHxNpI5zrNqDmwD2rogNfePKRUI63yBUfAgMBAAGjUzBRMB0GA1Ud
DgQWBBToi6zi6p+0BfkuyLEC5e6FMezbtDAfBgNVHSMEGDAWgBToi6zi6p+0Bfku
yLEC5e6FMezbtDAPBgNVHRMBAf8EBTADAQH/MA0GCSqGSIb3DQEBCwUAA4IBAQCA
Vq2qn+elCiEdOpmfiz2hY9ZBDCWJBQvDqwKa0jp7wmhZoLsa8VPR6Redq9eQc0LN
fvrnFiEOC6avsFT8dGb41pNrynGHHMgl9laKb/+RGoaMt4Dm6nAv2vPux5+3Yek9
407f2IvNACZQvNQw1md5G0Vz4/uj7gFjCha8k6DWZJijrLznYp4SWtXSI4g/vyXb
8YG96Lmha/j5RpG9VV4QPxCJg1KbtwMl+sTix1EB+id9ig0AAalGqKcg0iVeHSzc
WQJ9keUURJkxDWSh0LIrdysAEbfV0BtTHfi+7PCFipoyn2lEYI/OvL1aE3dOGGA4
vTViZKfBXbxW6T1FQh8P
-----END CERTIFICATE-----';

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
