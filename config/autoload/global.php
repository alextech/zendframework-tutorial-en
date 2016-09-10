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
        'hostname' => 'pgsql.ad.alex-tech-adventures.com',
        'driver' => 'Pdo_Pgsql',
        'database' => 'zftutorial-en',
        'username' => 'postgres', //@TODO use forms to request privileged permissions on migrations run
        'password' => '',
    ],
    'ldap' => [
        'host' => 'dc-server.ad.alex-tech-adventures.com',
        'useStartTls' => true,
        'accountDomainName' => 'ad.alex-tech-adventures.com',
        'accountDomainNameShort' => 'alex-tech',
        'baseDn' => 'CN=Users,DC=ad,DC=alex-tech-adventures,DC=com',
        'accountCanonicalForm' => \Zend\Ldap\Ldap::ACCTNAME_FORM_BACKSLASH, // alex-tech\sasha
        'accountFilterFormat' => '(&(objectClass=user)(sAMAccountName=%s))'
    ],

//    'ldap' => [
//        'host' => 'apacheds.ad.alex-tech-adventures.com',
//        'port' => 10389,
//        'accountDomainName' => 'ds.alex-tech-adventures.com',
//        'accountDomainNameShort' => 'alex-tech',
//        'accountCanonicalForm' => \Zend\Ldap\Ldap::ACCTNAME_FORM_DN, // alex-tech\sasha
//        'baseDn' => 'ou=Users,DC=ds,DC=alex-tech-adventures,DC=com',
//        'accountFilterFormat' => '(&(objectClass=inetOrgPerson)(uid=%s))'
//    ]
    // ...
];
