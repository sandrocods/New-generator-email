<?php

/**
 *
 * https://generator.email/
 * Unofficial API
 * Code by sandroputraa
 *
 */
class GeneratorEmail
{
    public $domain;
    public $user;

    const API = 'https://generator.email/';
    const Header = [
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
        "Connection: keep-alive",
        "Host: generator.email",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.77 Safari/537.36",
    ];
    const ContentType = [
        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    ];

    /**
     * Get New Email Address
     *
     **/
    public function getEmail($username = null, $domains = null)
    {
        if ($username && $domains !== null) {

            $this->user = $username;
            $this->domain = $domains;

            return [
                'Email' => $this->user . '@' . $this->domain,
            ];

        } else {

            $request = $this->request(self::API, 'GET', null, null, self::Header, null, true);
            preg_match('/var gasmurl="\/(.*?)\/(.*?)";/', $request['Body'], $value);
            if ($value[0] && $value[1] !== '') {
                $this->user = $value[2];
                $this->domain = $value[1];
                return [
                    'status' => true,
                    'Email' => $this->user . "@" . $this->domain,
                    'User' => $this->user,
                    'Domain' => $this->domain,
                ];
            } else {
                return [
                    'status' => false,
                ];
            }

        }

    }

    /**
     * Checking Validation Email
     *
     **/
    public function CheckValidation()
    {

        $request = $this->request(
            self::API . 'check_adres_validation3.php',
            'POST',
            'usr=' . $this->user . '&dmn=' . $this->domain . '',
            null,
            array_merge(self::Header, self::ContentType, ['Cookie: surl=' . $this->domain . '/' . $this->user . '/']),
            null,
            true
        );
        if ($request['HttpCode'] == 200) {
            return [
                'status' => true,
                'Status_email' => @json_decode($request['Body'], true)['status'],
                'Uptime' => @json_decode($request['Body'], true)['uptime'],
            ];
        } else {
            return [
                'status' => false,
            ];
        }
    }

    /**
     * Read Email
     *
     **/
    public function ReadEmail()
    {
        $request = $this->request(self::API, 'GET', null, ['Max' => 1], array_merge(self::Header, ['Cookie: surl=' . $this->domain . '/' . $this->user . '/']), null, true);
        preg_match('/<span id="mess_number">(.*?)<\/span>/m', $request['Body'], $countMessage);
        if ($countMessage[1] == 1) {
            $GettingBody = $this->getStr($request['Body'], '<div class="text_swc">Pop-up notification</div></div>', '<script type="text/javaScript">');
            return [
                'status' => true,
                'Count_message' => $countMessage[1],
                'Dell_key' => $this->getStr($request['Body'], '{ delll: "', '"}'),
                'Data' => [
                    'From' => $this->getStr($request['Body'], '<div class="e7m from_div_45g45gg">', '</div>'),
                    'Subject' => $this->getStr($request['Body'], '<div class="e7m subj_div_45g45gg">', '</div>'),
                    'Time' => $this->getStr($request['Body'], '<div class="e7m time_div_45g45gg">', '</div>'),
                    'Body_message' => $this->getStr($request['Body'], '<div dir="ltr">', '</div>'),
                ],
            ];
        } elseif ($countMessage[1] > 1) {
            preg_match_all('/<a href="(.*?)"  class="e7m list-group-item/m', $request['Body'], $matches);
            preg_match_all('/<div class="e7m from_div_45g45gg">(.*?)<\/div>/m', $request['Body'], $matchesFrom);
            preg_match_all('/<div class="e7m subj_div_45g45gg">(.*?)<\/div>/m', $request['Body'], $matchesSubj);
            preg_match_all('/<div class="e7m time_div_45g45gg">(.*?)<\/div>/m', $request['Body'], $matchesTime);

            // Build Message Collections
            for ($i = 0; $i < $countMessage[1]; $i++) {
                $explodeLongAddress = explode("/", $matches[1][$i]);
                $datas[] = [
                    'LongAddress' => $explodeLongAddress[3],
                    'From' => $matchesFrom[1][$i],
                    'Subject' => $matchesSubj[1][$i],
                    'Time' => $matchesTime[1][$i],

                ];
            }
            return [
                'status' => true,
                'Count_message' => $countMessage[1],
                'Dell_key' => $this->getStr($request['Body'], '{ delll: "', '" }'),
                'Data' => $datas,
            ];
        } else {
            return [
                'status' => false,
                'message' => 'No messages',
            ];
        }

    }

    /**
     * Read Specific Email
     *
     *
     **/
    public function ReadSpecific($longAddress = null)
    {
        if ($longAddress !== null) {
            $request = $this->request(self::API, 'GET', null, ['Max' => 1], array_merge(self::Header, ['Cookie: surl=' . $this->domain . '/' . $this->user . '/' . $longAddress . '; embx=%5B%22' . $this->user . '%40' . $this->domain . '%22%5D']), null, true);
            $GettingBody = $this->getStr($request['Body'], '<div class="text_swc">Pop-up notification</div></div>', '<script type="text/javaScript">');
            return [
                'status' => true,
                'Long_address' => $longAddress,
                'Dell_key' => $this->getStr($request['Body'], '{ delll: "', '" }'),
                'Data' => [
                    'From' => $this->getStr($request['Body'], '<div class="e7m from_div_45g45gg">', '</div>'),
                    'Subject' => $this->getStr($request['Body'], '<div class="e7m subj_div_45g45gg">', '</div>'),
                    'Time' => $this->getStr($request['Body'], '<div class="e7m time_div_45g45gg">', '</div>'),
                    'Body_message' => $this->getStr($request['Body'], '<div dir="ltr">', '</div>'),
                ],
            ];
        } else {
            throw new Exception('Long Address Empty !');
        }

    }

    /**
     * Delete Message by specific Message
     *
     **/
    public function DeleteMessage($dellKey = null, $longAddress = null)
    {
        if ($dellKey && $longAddress !== null) {
            $request = $this->request(self::API . 'del_mail.php', 'POST', 'delll=' . $dellKey, null, array_merge(self::Header, self::ContentType, ['Cookie: surl=' . $this->domain . '/' . $this->user . '/' . $longAddress . '; embx=%5B%22' . $this->user . '%40' . $this->domain . '%22%5D']), null, true);
            if ($request['Body'] == 'Message deleted successfully') {
                return [
                    'status' => true,
                    'message' => 'Message deleted successfully',
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Unkown Error',
                ];
            }
        } else {
            throw new Exception('Dellkey & Long Address Empty !');
        }
    }

    /**
     * Delete All Message
     *
     **/
    public function DeleteAll($dellKey = null)
    {
        if ($dellKey !== null) {
            $request = $this->request(self::API . 'del_mail.php', 'POST', 'dellall=' . $dellKey, null, array_merge(self::Header, self::ContentType, ['Cookie: surl=' . $this->domain . '/' . $this->user . '; embx=%5B%22' . $this->user . '%40' . $this->domain . '%22%5D']), null, true);
            if ($request['Body'] == 'Messages deleted successfully') {
                return [
                    'status' => true,
                    'message' => 'Messages deleted successfully',
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Unkown Error',
                ];
            }
        } else {
            throw new Exception('Dellkey Empty !');
        }
    }

    public function request($url, $method = null, $postfields = null, $followlocation = null, $headers = null, $proxy = null, $debug = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if ($proxy) {
            $parts = parse_url($proxy);
            if (!$parts || !isset($parts['scheme'], $parts['host']) || ($parts['scheme'] !== 'http' && $parts['scheme'] !== 'https')) {
                echo "Invalid proxy URL " . $proxy . "";
                die();
            }
            if (isset($parts['user'])) {
                $proxyAuth = $parts['user'] . ':' . $parts['pass'];
            } else {
                $proxyAuth = false;
            }
            curl_setopt($ch, CURLOPT_PROXY, $parts['host']);
            curl_setopt($ch, CURLOPT_PROXYPORT, $parts['port']);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLOPT_HTTPPROXYTUNNEL);
            curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            if ($proxyAuth) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyAuth);
            }
        }
        if ($followlocation !== null) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, $followlocation['Max']);
        }
        if ($method == "PUT") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        }
        if ($method == "GET") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }
        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        }
        if ($headers !== null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $result = curl_exec($ch);
        $header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
        $cookies = array();
        foreach ($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        if ($debug) {
            return [
                'HttpCode' => $httpcode,
                'Header' => $header,
                'Body' => $body,
                'Cookie' => $cookies,
                'Cookiev2' => http_build_query($cookies, '', '; '),
                'Requests Config' => [
                    'Url' => $url,
                    'Header' => $headers,
                    'Method' => $method,
                    'Post' => $postfields,
                ],
            ];
        } else {
            return [
                'HttpCode' => $httpcode,
                'Header' => $header,
                'Body' => $body,
                'Cookie' => $cookies,
                'Cookiev2' => http_build_query($cookies, '', '; '),
            ];
        }

    }

    public function getStr($string, $start, $end)
    {
        $str = explode($start, $string);
        $str = explode($end, ($str[1]));
        return $str[0];
    }

}
