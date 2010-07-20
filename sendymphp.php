<?php
/*
  Copyright (C) 2010 Sony Arianto Kurniawan <sony@sony-ak.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see http://www.gnu.org/licenses/.

  ---------------------------------------------------------------------

  Script Name: sendymphp.php
  Last Update: July 20, 2010
  Location of Last Update: Bangalore, India
*/

  // your Yahoo! credentials (to send message)
  $yahoo_id = "your_yahoo_username";
  $yahoo_id_password = "your_yahoo_password";

  // Yahoo! ID for receiving your Yahoo! Messenger message
  $yahoo_username = "your_target_yahoo_username";
  $yahoo_message = "Please type your message here to your Yahoo! ID target";

  // get home page of yahoo mobile
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, "http://us.m.yahoo.com/w/bp-messenger");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($curl, CURLOPT_ENCODING, "");
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; EmbeddedWB 14.52 from: http://www.bsalsa.com/ EmbeddedWB 14,52; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729)");
  curl_setopt($curl, CURLOPT_COOKIEJAR, getcwd() . '/cookies_yahoo_messenger.cookie');
  $curlData = curl_exec($curl);
  curl_close($curl);

  // debug: show the returned html
  // echo $curlData; exit;

  // get post url for login to yahoo
  $xml = $curlData;
  $xmlDoc = new DOMDocument();
  @$xmlDoc->loadHTML($xml);

  $urlPostLoginToYahoo = $xmlDoc->getElementsByTagName("form")->item(0)->getAttribute("action");

  foreach ($xmlDoc->getElementsByTagName("input") as $input) {
    if ($input->getAttribute("name") == "_done") {
      $_done = $input->getAttribute("value");
    }
    if ($input->getAttribute("name") == "_ts") {
      $_ts = $input->getAttribute("value");
    }
    if ($input->getAttribute("name") == "_crumb") {
      $_crumb = $input->getAttribute("value");
    }
  }

  // do login to yahoo messenger (mobile version)
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $urlPostLoginToYahoo);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, "_authurl=auth&_done=" . $_done . "&_sig=&_src=&_ts=" . $_ts . "&_crumb=" . $_crumb . "&_pc=&_send_userhash=0&_partner_ts=&id=" . $yahoo_id . "&password=" . $yahoo_id_password . "&__submit=Sign+in");
  curl_setopt($curl, CURLOPT_ENCODING, "");
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; EmbeddedWB 14.52 from: http://www.bsalsa.com/ EmbeddedWB 14,52; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729)");
  curl_setopt($curl, CURLOPT_COOKIEFILE, getcwd() . '/cookies_yahoo_messenger.cookie');
  curl_setopt($curl, CURLOPT_COOKIEJAR, getcwd() . '/cookies_yahoo_messenger.cookie');
  $curlData = curl_exec($curl);
  curl_close($curl);

  // get home page url for sending message
  $urlSendMessage = $curlData;
  $urlSendMessage = substr($urlSendMessage, strpos($urlSendMessage, "<a href=\"/w/bp-messenger/sendmessage") + 9);
  $urlSendMessage = substr($urlSendMessage, 0, strpos($urlSendMessage, "\""));
  $urlSendMessage = str_replace("&amp;", "&", $urlSendMessage);
  $urlSendMessage = "http://us.m.yahoo.com" . $urlSendMessage;

  // get home page of mobile messenger to send message
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $urlSendMessage);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($curl, CURLOPT_ENCODING, "");
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; EmbeddedWB 14.52 from: http://www.bsalsa.com/ EmbeddedWB 14,52; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729)");
  curl_setopt($curl, CURLOPT_COOKIEFILE, getcwd() . '/cookies_yahoo_messenger.cookie');
  curl_setopt($curl, CURLOPT_COOKIEJAR, getcwd() . '/cookies_yahoo_messenger.cookie');
  $curlData = curl_exec($curl);
  curl_close($curl);

  // debug: show the returned html
  // echo $curlData; exit;

  $xml = $curlData;
  $xmlDoc = new DOMDocument();
  @$xmlDoc->loadHTML($xml);

  $urlPostSendMessage = $xmlDoc->getElementsByTagName("form")->item(0)->getAttribute("action");
  $urlPostSendMessage = "http://us.m.yahoo.com" . $urlPostSendMessage;

  // do send message to yahoo messenger
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $urlPostSendMessage);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, "id=" . $yahoo_username . "&message=" . $yahoo_message . "&__submit=Send");
  curl_setopt($curl, CURLOPT_ENCODING, "");
  curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; EmbeddedWB 14.52 from: http://www.bsalsa.com/ EmbeddedWB 14,52; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729)");
  curl_setopt($curl, CURLOPT_COOKIEFILE, getcwd() . '/cookies_yahoo_messenger.cookie');
  curl_setopt($curl, CURLOPT_COOKIEJAR, getcwd() . '/cookies_yahoo_messenger.cookie');
  $curlData = curl_exec($curl);
  curl_close($curl);

  echo "Your message already sent to Yahoo! ID: " . $yahoo_username;
?>