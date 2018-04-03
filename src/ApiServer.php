<?php 

namespace Zhouwuxiang\Plusapi;

use GuzzleHttp\Client;

class ApiServer 
{	
    /**
     * [$client_id description]
     * @var [type]
     */
    private $client_id;

    /**
     * [$client_secret 客户端秘钥]
     * @var [type]
     */
    private $client_secret;

    /**
     * [$url 请求地址]
     * @var string
     */
    private $url = 'https://api.hellorf.com/plus/';

    /**
     * [__construct description]
     * This is a cool function
     * @author zhouwuxiang
     * @DateTime 2018-04-02
     * @param    [type]     $client_id     [description]
     * @param    [type]     $client_secret [description]
     */
    public function __construct($client_id, $client_secret)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    /**
     * [publicParams 公共参数]
     * This is a cool function
     * @author zhouwuxiang
     * @DateTime 2018-04-02
     * @return   [type]     [description]
     */
    public function publicParams()
    {
        return [
            'client_id' => $this->client_id,
            'nonce_str' => time(),
        ];
    }

    /**
     * [makeSign 创建签名]
     * This is a cool function
     * @author zhouwuxiang
     * @DateTime 2018-04-02
     * @param    array      $params [description]
     * @return   [type]             [description]
     */
    private function makeSign($params = [])
    {
        $params = array_merge($params, $this->publicParams());

        array_pull($params, 'file');

        ksort($params);

        $str = http_build_query($params);

        return md5($str . '&client_secret='.$this->client_secret);
    }

    /**
     * [mergeParams 合并参数]
     * This is a cool function
     * @author zhouwuxiang
     * @DateTime 2018-04-02
     * @param    array      $params [description]
     * @return   [type]             [description]
     */
    private function mergeParams($params = [])
    {
        $sign = $this->makeSign($params);

        return array_merge($params, $this->publicParams(), ['sign' => $sign]);
    }

    /**
     * [myPurchases 我的购买记录]
     * This is a cool function
     * @author zhouwuxiang
     * @DateTime 2018-04-02
     * @param    string     $begin_at    [description]
     * @param    string     $end_at      [description]
     * @param    integer    $is_download [description]
     * @param    integer    $per_page    [description]
     * @param    integer    $page        [description]
     * @return   [type]                  [description]
     */
    public function myPurchases($begin_at = '', $end_at = '' , $is_download = 1, $per_page = 10, $page = 1)
    {
        $data = [
            'begin_at' => $begin_at,
            'end_at' => $end_at,
            'is_download' => $is_download,
            'per_page' => $per_page,
            'page' => $page,
        ];

        $path = 'image/my-purchases';

        return $this->request('get', $path, $data);
    }

    /**
     * [purchaseDownload 下载图片]
     * This is a cool function
     * @author zhouwuxiang
     * @DateTime 2018-04-02
     * @param    [type]     $purchase_id [description]
     * @return   [type]                  [description]
     */
    public function purchaseDownload($purchase_id)
    {
        $data = [
           'purchase_id' => $purchase_id,
        ];

        $path = 'image/purchase-download';

        return $this->request('get', $path, $data);
    }

    /**
     * [purchaseDownload 下载授权书]
     * This is a cool function
     * @author zhouwuxiang
     * @DateTime 2018-04-02
     * @param    [type]     $purchase_id [description]
     * @return   [type]                  [description]
     */
    public function downloadWarrant($purchase_id)
    {
        $data = [
            'purchase_id' => $purchase_id,
        ];

        $path = 'image/download-warrant';

        return $this->request('get', $path, $data);
    }

    /**
     * [categories 图片分类]
     * This is a cool function
     * @author zhouwuxiang
     * @DateTime 2018-04-02
     * @return   [type]     [description]
     */
    public function categories()
    {
        $path = 'image/categories';

        return $this->request('get', $path, []);
    }

    /**
     * [detail 图片详情]
     * This is a cool function
     * @author zhouwuxiang
     * @DateTime 2018-04-02
     * @param    [type]     $image_id [description]
     * @return   [type]               [description]
     */
    public function detail($image_id)
    {
        $data = [
           'image_id' => $image_id,
        ];

        $path = 'image/detail';

        return $this->request('get', $path, $data);
    }

    /**
     * [request description]
     * This is a cool function
     * @author zhouwuxiang
     * @DateTime 2018-04-02
     * @param    [type]     $method [description]
     * @param    [type]     $path   [description]
     * @param    array      $data   [description]
     * @return   [type]             [description]
     */
    public function request($method, $path, $data=[])
    {
        $client = new Client();

        $response = $client->request($method, $this->url . $path, $this->mergeParams($data));

        return $response->getContent();
    }
}