<?php
/**
 * Copyright 2014 LUXSTACK, Inc.
 *
 */

if (!function_exists('curl_init')) {
  throw new Exception('Razrbit needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Razrbit needs the JSON PHP extension.');
}

/**
 * Provides access to the Razrbit Service.
 */
class Razrbit
{
  /**
   * Version of this API code.
   */
  const VERSION = '0.1';

  /**
   * curl requests are sent to this URL.
   */
  const ENDPOINT = 'https://api.razrbit.com';

  /**
   * Version of API to which we send requests.
   * This is combined with ENDPOINT to make the URLs
   */
  const ENDPOINT_VERSION = 'v1';

  /**
   * 
   * If TRUE, test addresses can be used to test the API without sending bitcoins.
   * See https://razrbit.com/dashboard for examples of test addresses.
   * 
   * If set to false, the test addresses will fail validation.
   * 
   */
  const ALLOW_TEST_ADDRESSES = TRUE;
  
  /**
   * Default options for curl.
   *
   */
  public static $CURL_OPTS = array(
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_USERAGENT      => 'razrbit-php-0.1',
  );

  /**
   * The Razrbit Application ID.
   *
   * @var String
   */
  protected $appId;

  /**
   * The Razrbit Application Secret.
   *
   * @var String
   */
  protected $appSecret;

  /**
   *
   * @var Array  Holds the default parameters needed for all Razrbit requests
   */
  protected $baseCurlParams;
  
  /**
   * Initialize a Razrbit Application.
   *
   * @param String $appId the application ID
   * @param String $appSecret the application secret
   */
  public function __construct($appId,$appSecret) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
    $this->baseCurlParams = array("appId"=>$this->appId,"appSecret"=>$this->appSecret);
  }


  /**
   * Makes an HTTP request.
   *
   * @param String $url  full path to which request should be sent
   * @param Array $params to be sent with the request
   * @param CurlHandler $ch (optional) initialized curl handle
   * @return String The response text
   */
  protected function makeRequest($url, $params, $ch=null) {
    if (!$ch) {
      $ch = curl_init();
    }
    
    $opts = self::$CURL_OPTS;

    $opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
    $opts[CURLOPT_URL] = $url;

    curl_setopt_array($ch, $opts);
    $result = curl_exec($ch);

    if ($result === false) {
      $curl_error = curl_error($ch);
      curl_close($ch);
      throw new Exception('CurlException' . $curl_error);
    }
    curl_close($ch);
    return $result;
  }

  /**
   * Make a Razrbit specific request
   * 
   * @param String      $apiPath full path to which request should be sent
   * @param Array       $params to be sent with the request (optional)  
   * @param CurlHandler $ch   (optional) initialized curl handle
   * @return Array of json_decoded response
   * @throws Exception
   */
  protected function makeRazrbitRequest($apiPath, $params=null, $ch=null) {
      $path = self::ENDPOINT . "/api/".self::ENDPOINT_VERSION.$apiPath;
      $params = !empty($params) ? $params : $this->baseCurlParams;

      $result_array = json_decode($this->makeRequest($path, $params, $ch),TRUE); // TRUE = json_decode return array
      
      if(!empty($result_array['result']) && $result_array['result'] == "error") {
        throw new Exception($result_array['message']);
      } else {
        return $result_array;
      }      
  }
  /**
   * Creates a new bitcoin address in your wallet
   * 
   * @return String new address
   * @throws Exception
   */
  public function walletCreateNewAddress() {
      $result_array = $this->makeRazrbitRequest("/wallet/createNewAddress");
      
      if(!empty($result_array['address'])) {
          return $result_array['address'];
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
  
  /**
   * Sends bitcoin from one of your addresses to the destination addresses. 
   * 
   * @param String $fromAddress must be one of your Razrbit addresses
   * @param String $toAddress
   * @param Integer $amount
   * @return Boolean success
   * @throws Exception
   */
  public function walletSendAmount($fromAddressPrivateKey,$toAddress,$amount) {
      if(!self::couldBeAPrivateAddress($fromAddressPrivateKey)) {
          throw new Exception("Invalid fromAddressPrivateKey ". $fromAddressPrivateKey);
      }
      if(!self::couldBeAnAddress($toAddress)) {
          throw new Exception("Invalid toAddress ". $toAddress);
      }
      $amount = intval($amount);
      if(!is_numeric($amount) || !($amount >= 0)) {
          throw new Exception("Invalid amount ". $amount);
      }
      $params = $this->baseCurlParams;
      $params['fromAddressPrivateKey'] = $fromAddressPrivateKey;
      $params['toAddress'] = $toAddress;
      $params['amount'] = $amount;
      $result_array = $this->makeRazrbitRequest("/wallet/sendAmount",$params);
      
      if(!empty($result_array['result'])) {
          return $result_array['result'] == "OK";
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
  
  /**
   * Returns the balance of the given address in bits.
   * 
   * @param String $address
   * @return Float number of bits in address
   * @throws Exception
   */
  public function walletGetBalanceFromAddress($address) {
      if(!self::couldBeAnAddress($address)) {
          throw new Exception("Invalid address ". $address);
      }
      $params = $this->baseCurlParams;
      $params['address'] = $address;
      $result_array = $this->makeRazrbitRequest("/wallet/getBalanceFromAddress",$params);
      
      print_r($result_array); 
      if(is_float($result_array['balance'])) {
          return $result_array['balance'];
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
  
  /**
   * Retrieve details about a given block
   * 
   * @param String $blockHash
   * @return Mixed Array of details about given block or String error message
   * @throws Exception
   */
  public function explorerBlock($blockHash) {
      if(!self::couldBeABlockHash($blockHash)) {
          throw new Exception("Invalid block hash " . $blockHash);
      }
      $params = $this->baseCurlParams;
      $params['blockHash'] = $blockHash;
      $result_array = $this->makeRazrbitRequest("/explorer/block",$params);

      // If this key is there, the data is valid
      if(!empty($result_array['difficulty'])) {
          return $result_array;
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
  
  /**
   * Retrieve details about a given transaction
   * 
   * @param String $transaction
   * @return Array details about transaction
   * @throws Exception
   */
  public function explorerTransaction($transaction) {
      if(!self::couldBeATransaction($transaction)) {
          throw new Exception("Invalid transaction " . $transaction);
      }
      $params = $this->baseCurlParams;
      $params['transactionHash'] = $transaction;
      $result_array = $this->makeRazrbitRequest("/explorer/transaction",$params);

      // If this key is there, the data is valid
      if(!empty($result_array['txid'])) {
          return $result_array;
      } else {
          throw new Exception("Unable to parse result.");
      }
      
  }
  
  
  /**
   * Retrieve details about a given address
   * 
   * @param String $address
   * @return Array of information about address
   * @throws Exception
   */
  public function explorerAddress($address) {
      if(!self::couldBeAnAddress($address)) {
          throw new Exception("Invalid address " . $address);
      }
      $params = $this->baseCurlParams;
      $params['address'] = $address;
      $result_array = $this->makeRazrbitRequest("/explorer/address",$params);

      // If this key is there, the data is valid
      if(!empty($result_array['addrStr'])) {
          return $result_array;
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
  
   
  
  /**
   * Returns the list of unspent outputs for a given address
   * 
   * @param String $address
   * @return Array of unspent outputs
   * @throws Exception
   */
  public function explorerAddressUnspentOutputs($address) {
      if(!self::couldBeAnAddress($address)) {
          throw new Exception("Invalid address " . $address);
      }
      $params = $this->baseCurlParams;
      $params['address'] = $address;
      $result_array = $this->makeRazrbitRequest("/explorer/addressUnspentOutputs",$params);

      // If this key is there, the data is valid
      if(!empty($result_array['unspentOutputs'])) {
          return $result_array['unspentOutputs'];
      } else {
          throw new Exception("Unable to parse result.");
      }
  }

  /**
   * Retrieve the current network difficulty
   * 
   * @return String representing difficulty
   * @throws Exception
   */
  public function networkGetDifficulty() {
      $result_array = $this->makeRazrbitRequest("/network/getDifficulty");
      
      // If this key is there, the data is valid
      if(!empty($result_array['difficulty'])) {
          return $result_array['difficulty'];
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
  
  /**
   * Push a transaction onto the network
   * 
   * @param String $transaction
   * @return Boolean success
   * @throws Exception
   */
  public function networkPushTransaction($transaction) {
      if(!self::couldBeATransaction($transaction)) {
          throw new Exception("Invalid transaction " . $transaction);
      }
      $params = $this->baseCurlParams;
      $params['transaction'] = $transaction;
      $result_array = $this->makeRazrbitRequest("/network/pushTransaction",$params);

      if(!empty($result_array['result'])) {
          return $result_array['result'] == "OK";
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
  
  /**
   * Market price
   * 
   * @param String $currencyCode = valid ISO 4217 code such as USD or EUR. 
   * @return String representing price per Bitcoin
   * @throws Exception
   */
  public function marketsPrice($currencyCode) {
      if(!self::couldBeACurrencyCode($currencyCode)) {
          throw new Exception("Invalid currencyCode " . $currencyCode);
      }
      $params = $this->baseCurlParams;
      $params['currencyCode'] = $currencyCode;
      $result_array = $this->makeRazrbitRequest("/markets/price",$params);

      if(!empty($result_array['price'])) {
          return $result_array['price'];
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
    
  /**
   * Return day price
   * 
   * @param String $currencyCode = valid ISO 4217 code such as USD or EUR. 
   * @return String representing Bitcoin price
   * @throws Exception
   */
  public function marketsDayPrice($currencyCode) {
      if(!self::couldBeACurrencyCode($currencyCode)) {
          throw new Exception("Invalid currencyCode " . $currencyCode);
      }
      $params = $this->baseCurlParams;
      $params['currencyCode'] = $currencyCode;
      $result_array = $this->makeRazrbitRequest("/markets/dayPrice",$params);

      if(!empty($result_array['dayPrice'])) {
          return $result_array['dayPrice'];
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
    
    
  /**
   * Return historical price
   * 
   * @param String $currencyCode = valid ISO 4217 code such as USD or EUR. 
   * @param String $yyyymmdd date in yyyy-mm-dd format
   * @return String representation of asking price per Bitcoin on the given date
   * @throws Exception
   */
  public function marketsHistoricalPrice($currencyCode,$yyyymmdd) {
      if(!self::couldBeACurrencyCode($currencyCode)) {
          throw new Exception("Invalid currencyCode " . $currencyCode);
      }
      if(!self::isHyphenatedYYYYMMDDFormat($yyyymmdd)) {
          throw new Exception("Date must be in yyyy-mm-dd format " . $yyyymmdd);
      }
      $params = $this->baseCurlParams;
      $params['currencyCode'] = $currencyCode;
      $params['date'] = $yyyymmdd;
      $result_array = $this->makeRazrbitRequest("/markets/historicalPrice",$params);

      if(!empty($result_array['price'])) {
          return $result_array['price'];
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
    
    
  /**
   * Set up a notification email for a given address
   * 
   * @param String $address
   * @param String $email
   * @return Boolean
   * @throws Exception
   */
  public function notificationsAddress($address,$email) {
      if(!self::couldBeAnAddress($address)) {
          throw new Exception("Invalid address " . $address);
      }
      if(!self::couldBeAnEmail($email)) {
          throw new Exception("Invalid email " . $email);
      }
      $params = $this->baseCurlParams;
      $params['address'] = $address;
      $params['email'] = $email;
      $result_array = $this->makeRazrbitRequest("/notifications/address",$params);

      if(!empty($result_array['result'])) {
          return $result_array['result'] == "OK";
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
  /**
   * Set up a notification email for a given block
   *  
   * @param String $blockHash
   * @param String $email
   * @return Boolean
   * @throws Exception
   */
  public function notificationsBlock($blockHash,$email) {
      if(!self::couldBeABlockHash($blockHash)) {
          throw new Exception("Invalid blockHash " . $blockHash);
      }
      if(!self::couldBeAnEmail($email)) {
          throw new Exception("Invalid email " . $email);
      }
      $params = $this->baseCurlParams;
      $params['blockHash'] = $blockHash;
      $params['email'] = $email;
      $result_array = $this->makeRazrbitRequest("/notifications/block",$params);

      if(!empty($result_array['result'])) {
          return $result_array['result'] == "OK";
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
      
  /**
   * Set up a notification email for a given transaction
   * 
   * @param String $transactionHash
   * @param String $email
   * @return Boolean
   * @throws Exception
   */
  public function notificationsTransaction($transactionHash,$email) {
      if(!self::couldBeABlockHash($transactionHash)) {
          throw new Exception("Invalid transactionHash " . $transactionHash);
      }
      if(!self::couldBeAnEmail($email)) {
          throw new Exception("Invalid email " . $email);
      }
      $params = $this->baseCurlParams;
      $params['transactionHash'] = $transactionHash;
      $params['email'] = $email;
      $result_array = $this->makeRazrbitRequest("/notifications/transaction",$params);

      if(!empty($result_array['result'])) {
          return $result_array['result'] == "OK";
      } else {
          throw new Exception("Unable to parse result.");
      }
  }
  
  /**
   * 
   * Validation functions all accept one String parameter and return Boolean.
   * Some of the regex patterns may be wrong.
   * 
   * When testing, consider setting ALLOW_TEST_ADDRESSES = TRUE at the top of this class
   * 
   * @param String 
   * @return Boolean
   */
  private static function couldBeAnAddress($address) {
      return self::ALLOW_TEST_ADDRESSES || 
              preg_match("/^1[1-9A-Za-z][^OIl]{20,40}$/", $address);
  }
  private static function couldBeAPrivateAddress($address) {
      return self::ALLOW_TEST_ADDRESSES || 
              preg_match("/^5[1-9A-Za-z][^OIl]{20,40}$/", $address);
  }
  private static function couldBeAPublicKey($bip32key) {
      return self::ALLOW_TEST_ADDRESSES || 
              preg_match("/[0-9a-f]{10,65}",$bip32key);
  }
  private static function couldBeABip32Wallet($bip32wallet) {
      return self::ALLOW_TEST_ADDRESSES || 
              preg_match("/^m\//",$bip32wallet);
  }
  private static function couldBeABlockHash($blockHash) {
      return self::ALLOW_TEST_ADDRESSES || 
              preg_match("/[0-9a-f]{10,65}",$blockHash);
  }
  private static function couldBeATransaction($transaction) {
      return self::ALLOW_TEST_ADDRESSES || 
              preg_match("/[0-9a-f]{10,65}",$transaction);
  }
  private static function couldBeACurrencyCode($currencyCode) {
      return preg_match("/[A-Z]{3}/", $currencyCode);
  }
  private static function isHyphenatedYYYYMMDDFormat($yyyymmdd) {
      return preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $yyyymmdd);
  }
  private static function couldBeAnEmail($email) {
      return filter_var($email, FILTER_VALIDATE_EMAIL);
  }
}
