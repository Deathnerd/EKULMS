<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 7/7/14
	 * Time: 7:30 PM
	 */

	/**
	 * The purpose of this central config file is configuring all examples
	 * in one place with minimal work for your working environment
	 * Just configure all the items in this config according to your environment
	 * and rename the file to config.php
	 *
	 * PHP Version 5
	 *
	 * @file     config.php
	 * @category Authentication
	 * @package  PhpCAS
	 * @author   Joachim Fritschi <jfritschi@freenet.de>
	 * @author   Adam Franco <afranco@middlebury.edu>
	 * @license  http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
	 * @link     https://wiki.jasig.org/display/CASC/phpCAS
	 */

	$phpcas_path = '../../source/';

///////////////////////////////////////
// Basic Config of the phpCAS client //
///////////////////////////////////////

// Full Hostname of your CAS Server
	$cas_host = 'casauth.eku.edu';

// Context of the CAS Server
	$cas_context = '/cas';

// Port of your CAS server. Normally for a https server it's 443
	$cas_port = 443;

// Path to the ca chain that issued the cas server certificate
//	$cas_server_ca_cert_path = '/path/to/cachain.pem';