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
        // $xmlContent = $request->input('xml_content');
        // $publicKey = $request->input('public_key');


        // XML content from your example
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<ns2:ReqHbt xmlns:ns2="http://npci.org/upi/schema/" xmlns:ns3="http://npci.org/cm/schema/"><Head ver="2.0" ts="2024-10-16T22:42:20+08:00" orgId="HIP103" msgId="HIP10301JAAWF7BPV8482EG2ES6H5G2A" prodType="UPI"/><Txn id="HIP10301JAAWF7BPV8482EG2ES6H5G29" note="GLOBALQR" refId="1720616742" refUrl="https://www.hitpayapp.com" ts="2024-10-16T22:42:20+08:00" type="Hbt"/><HbtMsg type="ALIVE" value="NA"/><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/><SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"/><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256"/><DigestValue>BM52nDXoFDaGz5of6vkL0UzHbsGYf9Nz/2+BroB6l5k=</DigestValue></Reference></SignedInfo><SignatureValue>jtpzpgw7WXj/2lOy0Y8xy/1/bNZHvTBba7d6fjdeYEiSXUDl0iP8hq0FP5txR1gSi9qIYxUmVvFqq7fcAqc4ghtcUypwwJBHECCXvuWlnLIYgJ5Zm0FXuSn9m/DW4L6mdHLThto7gRITv+8PCOPjqcrcMeXFbewnHddK4wQgD4+ERScyG2aPTqWEwXUFbbC8rtE4+tfPoP+L+09GaGMVAMIsGG1pO+5T4fkjojnlphlqMdUVmy3FVk0cVj1U9jeMM7bwPkYXuHuuZHMS1EeodLFSgSrNI3bmPnUoW//RSv7ESVJqklRHWeRQkRVo6ErJbY7od1qdCrUbvgV8CT9I3w==</SignatureValue><KeyInfo><KeyValue><RSAKeyValue><Modulus>xgUXbIg6ijxsh4Dqc8ZklNhEJJvs2GS75T5cJMMnxVi53RS2d7GsSUviyIsbBYbp661E/WWdMfmwejFeeaO9M+AATiURTVsdcGzBqnYgtBcn6cGnd4mKUVY2TvuXrRwPSRKGkz6Bcb/pLXfJFQC/T+SO/z4uoNUfgdrdpbGhNmzEsLWaoLOeYG+37Akd2eJ0IuwXXWEezGdpQDFn+4ew5JurkZRoZdkb7ZmGunVxZyt0YSKXUDlE96rsocHjBrKaMAv7WEhvgTYctVrpG8ISD+b0wL79Vmx04PlmlFFfqT9E4z0HGO93JR8TaSOc6zag5sA9q6IDX3jykVCOt8gVHw==</Modulus><Exponent>AQAB</Exponent></RSAKeyValue></KeyValue></KeyInfo></Signature></ns2:ReqHbt>';


        // Certificate key (PEM format, for example)
        $publicKey = '-----BEGIN CERTIFICATE-----
MIIDazCCAlOgAwIBAgIUOg12XrOythzxcAbjOw7uUgOGGBkwDQYJKoZIhvcNAQEL
BQAwRTELMAkGA1UEBhMCU0cxEzARBgNVBAgMClNvbWUtU3RhdGUxITAfBgNVBAoM
GEludGVybmV0IFdpZGdpdHMgUHR5IEx0ZDAeFw0yNDEwMTYxNDAyNDJaFw0yNTEw
MTYxNDAyNDJaMEUxCzAJBgNVBAYTAlNHMRMwEQYDVQQIDApTb21lLVN0YXRlMSEw
HwYDVQQKDBhJbnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQwggEiMA0GCSqGSIb3DQEB
AQUAA4IBDwAwggEKAoIBAQDGBRdsiDqKPGyHgOpzxmSU2EQkm+zYZLvlPlwkwyfF
WLndFLZ3saxJS+LIixsFhunrrUT9ZZ0x+bB6MV55o70z4ABOJRFNWx1wbMGqdiC0
Fyfpwad3iYpRVjZO+5etHA9JEoaTPoFxv+ktd8kVAL9P5I7/Pi6g1R+B2t2lsaE2
bMSwtZqgs55gb7fsCR3Z4nQi7BddYR7MZ2lAMWf7h7Dkm6uRlGhl2RvtmYa6dXFn
K3RhIpdQOUT3quyhweMGspowC/tYSG+BNhy1WukbwhIP5vTAvv1WbHTg+WaUUV+p
P0TjPQcY73clHxNpI5zrNqDmwD2rogNfePKRUI63yBUfAgMBAAGjUzBRMB0GA1Ud
DgQWBBToi6zi6p+0BfkuyLEC5e6FMezbtDAfBgNVHSMEGDAWgBToi6zi6p+0Bfku
yLEC5e6FMezbtDAPBgNVHRMBAf8EBTADAQH/MA0GCSqGSIb3DQEBCwUAA4IBAQAf
ZMO/87RIVwXZv03q86VueYWRJax67e42336G9D8zIJawSuczVyPcNSh4Le6BqCki
jwCZkK/x5k8zj2EUnsLPVHxlsfzHLk4w4Z/N2FHd8kfx2SzJ5URZyWFnmLhUxuNE
py3bzD0KIHKnnSjNgWjXx8oCUcJd0GbN0TdT1QOu1J/y27Z0HUWEmsJSXkBzRzjd
W9nRZvyjGZ9K/bdA8UG0no8Zz51fqyn0d8adIaWjFXso45M6MdLyr1moNezO6vic
bq6QY1E4vJdyHyJQOMji53/BaBzkP21cW1INLgqfuMTxlNFeVsfIUqQ2qbzbnsV6
kuSPxesZnCMrkc711jTd
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
