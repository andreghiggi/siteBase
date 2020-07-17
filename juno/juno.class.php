<?php

require_once('../s_acessos.php');

$debug = false;

if($debug)
{
    ini_set('display_errors',1);
    ini_set('display_startup_erros',1);
    error_reporting(E_ALL);
}

/*
    Conta Teste "resourceToken":"CB79F7A0F088B8AF11F2C5DEAAF949E5E3CE69AB878D87C5173BCFA13D8B7EB1"
*/

class Juno
{
    public $clientId = "fnkLj1Luh3nJwM4v";
    public $clientSecret = "1yW@M+lI,-dxmmnLUzaxo5,NP;Zv(If^";
    
    # token da sessão
    private $token = "";
    private $expires = "";
    # token do cliente
    private $recipientToken = "";
    # token da i9
    private $masterToken = "1230E4ECA4E796BF373DD272FFC5D24482D4DA794EA41A4455EEB95AC7A9A75B";

    private $resourceToken = "";

    private $curl;
    private $url = "";

    private $taxa = 2.8;

    function __construct()
    {
        $resp = mysql_fetch_assoc(mysql_query('select recipient_token,sandbox from juno_configuracao'));
        
        $this->recipientToken = $resp['recipient_token'];
        $this->url = $resp['sandbox']? 'https://sandbox.boletobancario.com/api-integration/':'https://boletobancario.com/api-integration/';

        if(function_exists('curl_version')){
            $this->curl = curl_init();
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            
            $this->loadKey();
            //$this->loadResourceToken();
        }
    }

    private function setRecipientToken($token)
    {
        $this->recipientToken = $token;
    }

    private function loadKey()
    {
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_URL, "https://sandbox.boletobancario.com/authorization-server/oauth/token");
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: Basic ".base64_encode($this->clientId.":".$this->clientSecret)
        ));
        curl_setopt($this->curl, CURLOPT_POSTFIELDS,"grant_type=client_credentials");

        $resp = json_decode(curl_exec($this->curl));

        $this->token = $resp->access_token;
        $this->expires = $resp->expires_in;

        $this->isTokenVal();
    }

    private function isTokenVal()
    {
        $now = intval(substr(date("U"),5,5));

        /*if($now >= $this->expires)
        {
            //$this->loadKey();
        }*/
    }

    public function getBusinessAreas()
    {
        $this->isTokenVal();

        curl_setopt($this->curl, CURLOPT_URL, $this->url."data/business-areas");
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$this->token,
            "X-Api-Version: 2",
            "X-Resource-Token: ".$this->masterToken
        ));

        $resp = json_decode(curl_exec($this->curl));

        return $resp;
    }

    public function getCompanyTypes()
    {
        $this->isTokenVal();

        curl_setopt($this->curl, CURLOPT_URL, $this->url."data/company-types");
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$this->token,
            "X-Api-Version: 2",
            "X-Resource-Token: ".$this->masterToken
        ));

        $resp = json_decode(curl_exec($this->curl));

        return $resp;
    }

    /* ========== CONTA ========== */

    /*
    "type" => "PAYMENT",
    "name" => "b-cup estampas",
    "document" => "02694062040",
    "email" => "juliobenin@yahoo.com.br",
    "birthDate" => "1996-03-24",
    "phone" => "54999994316",
    "businessArea" => "1000",
    "linesOfBusiness" => "Descrição da empresa",
    "companyType" => "MEI",
    "address" => array(
        "street" => "rua marechal floriano",
        "number" => "1380",
        "complement" => "",
        "neighborhood" => "planalto",
        "city" => "Guaporé",
        "state" => "RS",
        "postCode" => "99200000"
    ),
    "bankAccount" => array(
        "bankNumber" => "748",
        "agencyNumber" => "0136",
        "accountNumber" => "81828-3",
        "accountComplementNumber" => "",
        "accountType" => "SAVINGS",
        "accountHolder" => array(
            "name" => "julio cesar benin krinhardt",
            "document" => "02694062040"
        )
    )
    */
    public function account($account)
    {
        $this->isTokenVal();

        curl_setopt($this->curl, CURLOPT_URL, $this->url."digital-accounts");
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$this->token,
            "X-Api-Version: 2",
            "X-Resource-Token: 1230E4ECA4E796BF373DD272FFC5D24482D4DA794EA41A4455EEB95AC7A9A75B",
        ));
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($account));

        $temp = curl_exec($this->curl);
        $resp = json_decode($temp);

        return $resp;
    }

    public function consulta($cliente)
    {
        $this->isTokenVal();

        curl_setopt($this->curl, CURLOPT_URL, $this->url."digital-accounts");
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$this->token,
            "X-Api-Version: 2",
            "X-Resource-Token: ".$cliente,
        ));

        $temp = curl_exec($this->curl);
        $resp = json_decode($temp);

        return $resp;
    }

    public function atualizarContaDigital($data){
        $this->isTokenVal();

        curl_setopt($this->curl, CURLOPT_URL, $this->url."digital-accounts");
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$this->token,
            "X-Api-Version: 2",
            "X-Resource-Token: ".$this->recipientToken,
        ));

        var_dump(
            array(
                "Content-Type: application/json",
                "Authorization: Bearer ".$this->token,
                "X-Api-Version: 2",
                "X-Resource-Token: ".$this->recipientToken,
            )
        );

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($data));

        $temp = curl_exec($this->curl);
        $resp = json_decode($temp);

        return $resp;
    }
    
    public function listDocumentos(){
    		curl_setopt($this->curl, CURLOPT_URL, $this->url."documents");
        curl_setopt($this->curl, CURLOPT_POST, 0);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$this->token,
            "X-Api-Version: 2",
            "X-Resource-Token: ".$this->recipientToken,
        ));

        $temp = curl_exec($this->curl);
        $resp = json_decode($temp);
        
        $ret = array();
        foreach($resp->_embedded->documents as $item){
					$ret[$item->id] = $item->description;
				}
        
        return $ret;
    }
    
    public function enviarDocumentos($img,$docID){
    	curl_setopt($this->curl, CURLOPT_URL, $this->url."documents/".$docID."/files");
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: multipart/form-data",
            "Authorization: Bearer ".$this->token,
            "X-Api-Version: 2",
            "X-Resource-Token: ".$this->recipientToken,
        ));
        
        $files = [
        	'files' => new \CurlFile('img.png', 'img/png',$docID.".png")
        ];
        
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $files);

				#var_dump(curl_getinfo($this->curl));

        $temp = curl_exec($this->curl);
        $resp = json_decode($temp);
        
        return $resp;
    }

    /* ========== Pagamento ========== */

    public function criarCobrancas($pagamento){
        $this->isTokenVal();

        $pagamento['charge']['split'] = array(
            array(
                "recipientToken" => $this->masterToken, #empresa
                "percentage" => $this->taxa,
            ),
            array(
                "recipientToken" => $this->recipientToken, # usuario
                "percentage" => 100-$this->taxa,
                "amountRemainder" => true,
                "chargeFee" => true
            )
        );

        $pagamento['charge']['paymentTypes'] = array(
            "CREDIT_CARD"
        );

        /*$pagamento['charge']['paymentTypes'] = array(
            "BOLETO"
        );*/

        //$pagamento['charge']['installments'] = 2

        curl_setopt($this->curl, CURLOPT_URL, $this->url."charges");
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$this->token,
            "X-Api-Version: 2",
            "X-Resource-Token: ".$this->recipientToken,
        ));

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($pagamento));

        $temp = curl_exec($this->curl);
        $resp = json_decode($temp);

        return $resp;
    }

}

$juno = new Juno();




