<?php 
/**
 * Client
 * HTTP Client untuk melakukan request ke server bot Telegram
 * php version 8.0.7
 *
 * @category Network
 * @package  Dbot
 * @author   fathurrohman <fathurrohmanrosyadi@gmail.com>
 * @license  https://github.com/fathurrohman26/dbot/blob/main/LICENSE MIT License
 * @version  GIT: @0.1@
 * @link     https://github.com/fathurrohman26/dbot
 */

namespace Fatah\Dbot\Network;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class Client
{
    /**
     * Client instance
     *
     * @var \GuzzleHttp\Client
     */
    private \GuzzleHttp\Client $client;

    /**
     * Opsi untuk client
     *
     * @var array $client_options
     */
    
    /**
     * Client constructor.
     *
     * @param string $token   Token bot
     * @param array  $options Opsi tambahan untuk client
     */
    public function __construct(string $token, array $options = [])
    {
        $this->options = $options;

        $defops = [
            'timeout'  => 5,
            'base_uri' => 'https://api.telegram.org/bot'
        ];

        if (!empty($options) ) {
            $options = array_merge($defops, $options);
        } else {
            $options = $defops;
            unset($defops);
        }

        $options['base_uri'] = rtrim($options['base_uri'], '/') . $token . '/';

        $this->options = $options;

        $this->init();
    }

    /**
     * Init
     * 
     * @return void
     */
    private function init()
    {
        $this->client = self::client($this->getOptions());
    }

    /**
     * uploadFile
     * 
     * @param  string $method [description]
     * @param  array  $file   [description]
     * @param  array  $params [description]
     * @return array
     */
    public function uploadFile(string $method, array $file, array $params = []): array
    {
        $data = [
            'ok'     => true,
            'result' => []
        ];

        $error = [
            'ok'          => false,
            'error_code'  => null,
            'description' => null
        ];
        
        try {
            $post = array(
                [
                    'name'     => $file['file_name'],
                    'contents' => Psr7\Utils::tryFopen($file['file_path'], 'r')
                ]
            );

            foreach ($params as $key => $val) {
                $post []= [
                    'name'     => $key,
                    'contents' => $val
                ];
            }

            $request = $this->client->request('POST', $method, [
                'multipart' => $post
            ]);

            $data['result'] = json_decode($request->getBody(), true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $d = json_decode((string)$e->getResponse()->getBody(), true);
                $error['error_code']  = $d['error_code'];
                $error['description'] = $d['description'];
            } else {
                unset($error['error_code']);
                $error['description'] = Psr7\Message::toString($e->getRequest());
            }
        } catch (ConnectException $e) {
            unset($error['error_code']);
            $error['description'] = Psr7\Message::toString($e->getRequest());
        }

        return (!is_null($error['description'])) ? $error : $data;
    }

    /**
     * Method untuk melakukan request ke server bot.
     * 
     * @param string $method Telegram Bot method
     * @param array  $params Array untuk parameter dari Method
     * 
     * @return array        error|result
     */
    public function callApi(string $method, array $params = []): array
    {
        $data = [
            'ok'     => true,
            'result' => []
        ];

        $error = [
            'ok'          => false,
            'error_code'  => null,
            'description' => null
        ];

        try {
            $request = $this->client->request(
                'GET', $method, [
                    'query' => $params
                ]
            );

            $data['result'] = json_decode($request->getBody(), true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $d = json_decode((string)$e->getResponse()->getBody(), true);
                $error['error_code']  = $d['error_code'];
                $error['description'] = $d['description'];
            } else {
                unset($error['error_code']);
                $error['description'] = Psr7\Message::toString($e->getRequest());
            }
        } catch (ConnectException $e) {
            unset($error['error_code']);
            $error['description'] = Psr7\Message::toString($e->getRequest());
        }

        return (!is_null($error['description'])) ? $error : $data;
    }

    /**
     * Method untuk mendapatkan opsi client.
     * 
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Method untuk membuat instansi dari \GuzzleHttp\Cliet
     *
     * @param array $config
     * 
     * @return \GuzzleHttp\Client
     */
    public static function client(array $config = []): \GuzzleHttp\Client
    {
        return new \GuzzleHttp\Client($config);
    }
}