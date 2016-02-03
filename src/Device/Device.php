<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Device.php.
 *
 * @author    overtrue <i@overtrue.me>
 * @copyright 2015 overtrue <i@overtrue.me>
 *
 * @link      https://github.com/overtrue
 * @link      http://overtrue.me
 */
namespace EasyWeChat\Device;

use EasyWeChat\Core\AbstractAPI;
use EasyWeChat\Device\DeviceHttpException;

/**
 * Class Device.
 */
class Device extends AbstractAPI
{
    const API_APPLY_DEVICEID = 'https://api.weixin.qq.com/device/getqrcode';
    const API_AUTHORIZE = 'https://api.weixin.qq.com/device/authorize_device';
    const API_SEND_MSG  = 'https://api.weixin.qq.com/device/transmsg';

    /**
     * Use NEW authorize method to apply a device_id and a qrticket.
     *
     * @return array|bool
     */
    public function getDevID()
    {
        $params = [
            'product_id' => '5805',
        ];

        $http = $this->getHttp();

        $rsp = $http->parseJSON($http->get(self::API_APPLY_DEVICEID, $params));

        if (empty($rsp['deviceid'])) {
            throw new DeviceHttpException('Request device_id fail.'.json_encode($rsp['device_id'], JSON_UNESCAPED_UNICODE));
        }

        return $rsp['deviceid'];
    }
	
    /**
     * Use NEW authorize method to apply a device_id and a qrticket.
     *
     * @return array|bool
     */
    public function authorize_new()
    {
        $params = [
            'device_num'  => '1',
            'device_list' => [
             //   json_encode([
                [
                    'id'  => 'gh_6b6d1badc057_fbbc638e21005d5d14630c93d6241892',
                    'mac' => '087CBE018645',
                    'connect_protocol' => '3',
                    'auth_key'         => '1234567890ABCDEF1234567890ABCDEF',
                    'close_strategy'   => '1',
                    'conn_strategy'    => '5',
                    'crypt_method'     => '0',
                    'auth_ver'         => '0',
                    'manu_mac_pos'     => '-1',
                    'ser_mac_pos'      => '-2',
                ]
            //    ]),
            ],
            'op_type' => '1',
        ];

        $rsp = $this->parseJSON('json', [self::API_AUTHORIZE, $params]);

        if (empty($rsp['errcode'])) {
//            throw new DeviceHttpException('authorize device_id fail.'.json_encode($rsp['errcode'], JSON_UNESCAPED_UNICODE));
        }

        return $rsp['errcode'];
    }

    public function sendMsgToDev()
    {
        $msg = base64_encode('qn9080');
        $params = [
            'device_type' => 'gh_6b6d1badc057',
            'device_id' => 'gh_6b6d1badc057_fbbc638e21005d5d14630c93d6241892',
            'open_id' => 'oYzoav76--pSnPGBaFdJ9mba96FM',
            'content' => $msg,  // base64 format
        ];

        $rsp = $this->parseJSON('post', [self::API_SEND_MSG, $params]);
    }
}

?>
