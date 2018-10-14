<?php
require_once ('lib/nusoap.php');

$server = new nusoap_server;
$server->configureWSDL('Web Service Data User', 'urn:server');
$server->xml_encoding = 'UTF-8';
$server->soap_defencoding = 'UTF-8';
$server->decode_utf8 = false;
$server->encode_utf8 = true;
$server->wsdl->schemaTargetNamespace = "urn:server";

// tempat fungsi web service
// kode program 13.2
// ini_set('display_errors', 0);

//require_once ('dbconn.php');
require_once 'Database.php';

function ambilDataUser($cari)
{
	//$conn = $GLOBALS['conn'];
    $conn = Database::getInstance()->getConnection();
	$result = [];
	$param = "%" . $cari . "%";
	$query = "SELECT * FROM tb_user WHERE nama_lengkap LIKE ?";

	// menjalankan aksi query

	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("s", $param);
		$stmt->execute();
		$hasil = $stmt->get_result();
	}

	// memasukkan $hasil ke $result

	while ($row = mysqli_fetch_array($hasil))
	{
		$result[] = [
			"id_user" => $row["id_user"],
			"nama_lengkap" => $row["nama_lengkap"],
			"alamat" => $row["alamat"],
			"jenis_kelamin" => $row["jenis_kelamin"]
		];
	}

	$conn->close();
	return $result;
}

// kode program 13.3

$server->wsdl->addComplexType(
    'User',
    'complexType',
    'struct',
    'all',
    '',
    [
        "id_user" =>
        [
            "name" => "id_user",
            "type" => "xsd:int"
        ],
        "nama_lengkap" =>
        [
            "name" => "nama_lengkap",
            "type" => "xsd:string"
        ],
        "alamat" => 
        [
            "name" => "alamat",
            "type" => "xsd:string"
        ],
        "jenis_kelamin" =>
        [
            "name" => "jenis_kelamin",
            "type" => "xsd:string"
        ]
    ]
);

$server->wsdl->addComplexType(
    'ArrayUser',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    [],
    [
        [
            'ref' => 'SOAP-ENC:arrayType',
            'wsdl:arrayType' => 'tns:User[]'
        ]
    ],
    'tns:User'
);

// kode program 13.4

$server->register(
    'ambilDataUser', //nama fungsi
    ['cari' => 'xsd:string'], //parameter
    ['return' => 'tns:ArrayUser'], //output
    'urn:server', //namespace
    'urn:server#ambilDataUser', //SOAP action
    'rpc', // style
    'encoded', // use
    "Ambil data user"// deskripsi
);

// tempat fungsi web service

$server->service(file_get_contents("php://input"));

// $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : "";
// $server->service($HTTP_RAW_POST_DATA);

?>