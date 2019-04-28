## Chronolabs Cooperative presents

# DNS Zones Propogation REST API Services

## Version: 1.0.8 (stable)

### Author: Dr. Simon Antony Roberts <simon@snails.email>

#### Demo: http://zones.snails.email

So this REST API allows for Zones or DNS Propogation with PowerDNS and MySQL backend incorporated, you can add zones to your system on the fly securely and concisely with this api.

# Supported Record Types

This lists all record types PowerDNS supports, and how they are stored in backends. The list is mostly alphabetical but some types are grouped.

    Warning: Host names and the MNAME of a SOA records are NEVER terminated with a '.' in PowerDNS storage! If a trailing '.' is present it will inevitably cause problems, problems that may be hard to debug. Use pdnsutil check-zone to validate your zone data.

    Note: Whenever the storage format is mentioned, this relates only to the way the record should be stored in one of the generic SQL backends. The other backends should use their native format.

The PowerDNS Recursor can serve and store all record types, regardless of whether these are explicitly supported.

## A
The A record contains an IP address. It is stored as a decimal dotted quad string, for example: '203.0.113.210'.

## AAAA
The AAAA record contains an IPv6 address. An example: '2001:DB8:2000:bf0::1'.

## AFSDB
A specialised record type for the 'Andrew Filesystem'. Stored as: '#subtype hostname', where subtype is a number.

## ALIAS
Since 4.0.0, the ALIAS pseudo-record type is supported to provide CNAME-like mechanisms on a zone's apex. See the howto for information on how to configure PowerDNS to serve records synthesized from ALIAS records.

## CAA
The "Certification Authority Authorization" record, specified in RFC 6844, is used to specify Certificate Authorities that may issue certificates for a domain.

## CERT
Specialised record type for storing certificates, defined in RFC 2538.

## CDNSKEY
The CDNSKEY (Child DNSKEY) type is supported.

## CDS
The CDS (Child DS) type is supported.

## CNAME
The CNAME record specifies the canonical name of a record. It is stored plainly. Like all other records, it is not terminated by a dot. A sample might be 'webserver-01.yourcompany.com'.

## DNSKEY
The DNSKEY DNSSEC record type is fully supported, as described in RFC 4034. Enabling DNSSEC for domains can be done with pdnsutil.

## DNAME
The DNAME record, as specified in RFC 6672 is supported. However, dname-processing has to be set to yes for PowerDNS to process these records.

## DS
The DS DNSSEC record type is fully supported, as described in RFC 4034. Enabling DNSSEC for domains can be done with pdnsutil.

## HINFO
Hardware Info record, used to specify CPU and operating system. Stored with a single space separating these two, example: 'i386 Linux'.

## KEY
The KEY record is fully supported. For its syntax, see RFC 2535.

## LOC
The LOC record is fully supported. For its syntax, see RFC 1876. A sample content would be: 51 56 0.123 N 5 54 0.000 E 4.00m 1.00m 10000.00m 10.00m

## MX
The MX record specifies a mail exchanger host for a domain. Each mail exchanger also has a priority or preference. For example 10 mx.example.net. In the generic SQL backends, the 10 should go in the 'priority field'.

## NAPTR
Naming Authority Pointer, RFC 2915. Stored as follows:

    '100  50  "s"  "z3950+I2L+I2C"     ""  _z3950._tcp.gatech.edu'.
    
The fields are: order, preference, flags, service, regex, replacement. Note that the replacement is not enclosed in quotes, and should not be. The replacement may be omitted, in which case it is empty. See also RFC 2916 for how to use NAPTR for ENUM (E.164) purposes.

## NS
Nameserver record. Specifies nameservers for a domain. Stored plainly: ns1.powerdns.com, as always without a terminating dot.

## NSEC, NSEC3, NSEC3PARAM
The NSEC, NSEC3 and NSEC3PARAM DNSSEC record type are fully supported, as described in RFC 4034. To enable DNSSEC, use pdnsutil.

## OPENPGPKEY
The OPENPGPKEY records, specified in RFC TBD, are used to bind OpenPGP certificates to email addresses.

## PTR
Reverse pointer, used to specify the host name belonging to an IP or IPv6 address. Name is stored plainly: www.powerdns.com. As always, no terminating dot.

## RP
Responsible Person record, as described in RFC 1183. Stored with a single space between the mailbox name and the more-information pointer. Example: peter.powerdns.com peter.people.powerdns.com, to indicate that peter@powerdns.com is responsible and that more information about peter is available by querying the TXT record of peter.people.powerdns.com.

## RRSIG
The RRSIG DNSSEC record type is fully supported, as described in RFC 4034. To enable DNSSEC processing, use pdnsutil.

## SOA
The Start of Authority record is one of the most complex available. It specifies a lot about a domain: the name of the master nameserver ('the primary'), the hostmaster and a set of numbers indicating how the data in this domain expires and how often it needs to be checked. Further more, it contains a serial number which should rise on each change of the domain.

The stored format is:

    primary hostmaster serial refresh retry expire default_ttl
    Besides the primary and the hostmaster, all fields are numerical. PowerDNS has a set of default values:

    primary: default-soa-name configuration option
    hostmaster: hostmaster@domain-name
    serial: 0
    refresh: 10800 (3 hours)
    retry: 3600 (1 hour)
    expire: 604800 (1 week)
    default_ttl: 3600 (1 hour)
 
The fields have complicated and sometimes controversial meanings. The 'serial' field is special. If left at 0, the default, PowerDNS will perform an internal list of the domain to determine highest change_date field of all records within the zone, and use that as the zone serial number. This means that the serial number is always raised when changes are made to the zone, as long as the change_date field is being set. Make sure to check whether your backend of choice supports Autoserial.

## SPF
SPF records can be used to store Sender Policy Framework details (RFC 4408).

## SSHFP
The SSHFP record type, used for storing Secure Shell (SSH) fingerprints, is fully supported. A sample from RFC 4255 is: 2 1 123456789abcdef67890123456789abcdef67890.

## SRV
SRV records can be used to encode the location and port of services on a domain name. When encoding, the priority field is used to encode the priority. For example, _ldap._tcp.dc._msdcs.conaxis.ch SRV 0 100 389 mars.conaxis.ch would be encoded with 0 in the priority field and 100 389 mars.conaxis.ch in the content field.

## TKEY, TSIG
The TKEY (RFC 2930) and TSIG records (RFC 2845, used for key-exchange and authenticated AXFRs, are supported. See the Modes of operation and DNS update documentation for more information.

## TLSA
The TLSA records, specified in RFC 6698, are used to bind SSL/TLS certificate to named hosts and ports.

## SMIMEA
The SMIMEA record type, specified in RFC 8162, is used to bind S/MIME certificates to domains.

## TXT
The TXT field can be used to attach textual data to a domain. Text is stored plainly, PowerDNS understands content not enclosed in quotes. However, all quotes characters (") in the TXT content must be preceded with a backslash (\).:

    "This \"is\" valid"

For a literal backslash in the TXT record, escape it:

    "This is also \\ valid"

Unicode characters can be added in two ways, either by adding the character itself or the escaped variant to the content field. e.g. "ç" is equal to "\195\167".

When a TXT record is longer than 255 characters/bytes (excluding possible enclosing quotes), PowerDNS will cut up the content into 255 character/byte chunks for transmission to the client.

## URI
The URI record, specified in RFC 7553, is used to publish mappings from hostnames to URIs.

# Apache Mod Rewrite (SEO Friendly URLS)

The follow lines go in your API_ROOT_PATH/.htaccess

    php_value memory_limit 16M
    php_value upload_max_filesize 1M
    php_value post_max_size 1M
    php_value error_reporting 0
    php_value display_errors 0
    
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^v([0-9]{1,2})/authkey.api ./index.php?version=$1&mode=authkey [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/([0-9a-z]{32})/(supermaster|domains|zones).api ./index.php?version=$1&authkey=$2&mode=$3 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/([0-9a-z]{32})/(masters|domains|users)/(raw|html|serial|json|xml).api ./index.php?version=$1&authkey=$2&mode=$3&format=$4 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/([0-9a-z]{32})/([0-9a-z]{32})/(zones)/(raw|html|serial|json|xml).api ./index.php?version=$1&authkey=$2&key=$3&mode=$4&format=$5 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/([0-9a-z]{32})/([0-9a-z]{32})/(edit|delete)/(zone|domain|master|user)/(raw|html|serial|json|xml).api ./index.php?version=$1&authkey=$2&key=$3&mode=$4&type=$5&format=$6 [L,NC,QSA]
    
    
## Scheduled Cron Job Details.,
    
There is one or more cron jobs that is scheduled task that need to be added to your system kernel when installing this API, the following command is before you install the chronological jobs with crontab in debain/ubuntu
    
    Execute:-
    $ sudo crontab -e


### CronTab Entry:

You have to add the following cronjobs to your cronjobs or on windows scheduled tasks!

    */15 * * * * /usr/bin/php /var/www/zones.snails.email/crons/import-dns-records.php
    
## Licensing

 * This is released under General Public License 3 - GPL3 - Only!

# Installation

Copy the contents of this archive/repository to the run time environment, configue apache2, ngix or iis to resolve the path of this repository and run the HTML Installer.

## Clients for the API

There is a XOOPS Module client for this api it is or will be found at the following path:~ https://github.com/Chronolabs-Cooperative/ZonesAPIClient-Module-Xoops25
