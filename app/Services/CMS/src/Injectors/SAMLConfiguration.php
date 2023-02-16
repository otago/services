<?php

namespace Services\CMS\Injectors;

use OneLogin\Saml2\Constants;
use SilverStripe\Core\Environment;
use SilverStripe\SAML\Services\SAMLConfiguration as ServicesSAMLConfiguration;

class SAMLConfiguration extends ServicesSAMLConfiguration
{
    public function asArray()
    {
        return [
            "strict"    => Environment::getEnv("SAMLADFS_STRICT"),
            "debug"     => Environment::getEnv("SAMLADFS_DEBUG"),
            "sp"        => [
                "entityId" => Environment::getEnv("SAMLADFS_SP_ENTITY_ID"),
                "assertionConsumerService" => [
                    "url"       => Environment::getEnv("SAMLADFS_SP_ENTITY_ID") . "/saml/acs",
                    "binding"   => Constants::BINDING_HTTP_POST
                ],
                "NameIDFormat"  => Constants::NAMEID_TRANSIENT,
                "x509cert" => Environment::getEnv("SAMLADFS_SP_X509_CERT"),
                "privateKey" => Environment::getEnv("SAMLADFS_SP_PRIVATE_KEY"),
            ],
            "idp" => [
                "entityId" => Environment::getEnv("SAMLADFS_IDP_ENTITY_ID"),
                "singleSignOnService" => [
                    "url" => Environment::getEnv("SAMLADFS_IDP_SINGLE_SIGNON_SERVICE"),
                    "binding" => Constants::BINDING_HTTP_REDIRECT
                ],
                "x509cert" => Environment::getEnv("SAMLADFS_IDP_X509_CERT"),
            ],
            "security" => [
                "nameIdEncrypted" => Environment::getEnv("SAMLADFS_NAME_ID_ENCRYPED"),
                "authnRequestsSigned" => true,
                "logoutRequestSigned" => true,
                "logoutResponseSigned" => true,
                "signMetadata" => false,
                "wantMessagesSigned" => false,
                "wantAssertionsSigned" => true,
                "wantNameIdEncrypted" => false,
                "signatureAlgorithm" => "http://www.w3.org/2001/04/xmldsig-more#rsa-sha256",
                "requestedAuthnContext" => [
                    "urn:federation:authentication:windows",
                    "urn:oasis:names:tc:SAML:2.0:ac:classes:Password",
                    "urn:oasis:names:tc:SAML:2.0:ac:classes:X509"
                ],
                "wantXMLValidation" => true
            ]
        ];
    }
}
