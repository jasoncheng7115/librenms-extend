<?php
/**
 * chtsmsapi.php
 *
 * Load includes and initialize needed things
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    LibreNMS CHTSMSAPI
 * @link       https://github.com/jasoncheng7115
 * @copyright  2017 Jason cheng
 * @author     Jason Cheng <sanyu3u@gmail.com>
 */

/**
 * @param array $modules Which modules to initialize
 */

// CHTSMS API 相關帳號密碼等設定或讀取
// $account = "12345";
// $assword = "password";
// $to_addr = "yourphonenumber";
$account = $_POST["account"];
$password = $_POST["password"];
$to_addr = $_POST["to_addr"];

// 注意：需安裝 mbstring 模組 apt-get install php-mbstring
// 原編碼為 UTF-8，需轉 BIG5 才可以讓 CHTSMS API 使用
$msg = $_POST["msg"];
$msg = mb_convert_encoding($msg, "BIG5", "UTF-8"); 

// 依據實際的 CHTSMS API 設定
$url = "https://imsp.emome.net:4443/imsp/sms/servlet/SubmitSM";

$post = array(
     "account"=>$account,
     "password"=>$password,
     "to_addr"=>$to_addr, 
     "msg"=>$msg
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query($post) );
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($curl); 
curl_close($curl);
//echo $result;

// 將結果寫入 log 檔案
$file = fopen("cht.log","a+"); 
$outlog = "";
$outlog = $outlog."url=".$url."\n";
$outlog = $outlog."msg=".$msg."\n";
$outlog = $outlog."result=".$result."\n";

fwrite($file,$outlog);
fclose($file);
