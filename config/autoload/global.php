<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db' => [
        'driver' => 'Pgsql'
    ],
    'ldapServers' => [
        'mainDC' => [
            'host' => 'dc-server.ad.alex-tech-adventures.com',
            'useStartTls' => true,
            'accountDomainName' => 'ad.alex-tech-adventures.com',
            'accountDomainNameShort' => 'alex-tech',
            'baseDN' => 'CN=Users,DC=ad,DC=alex-tech-adventures,DC=com',
            'accountCanonicalForm' => \Zend\Ldap\Ldap::ACCTNAME_FORM_BACKSLASH // alex-tech\sasha
        ],

        'apacheDS' => [
            'host' => 'apacheds.ad.alex-tech-adventures.com',
            'port' => 10389,
            'accountDomainShort' => 'alex-tech',
            'accountDomainName' => 'ds.alex-tech-adventures.com',
            'accountCanonicalForm' => \Zend\Ldap\Ldap::ACCTNAME_FORM_DN, // alex-tech\sasha
            'baseDN' => 'CN=Users,DC=ad,DC=alex-tech-adventures,DC=com'
        ]
    ]
    // ...
];
