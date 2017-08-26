<?php 
namespace App\Apis;

class EverWebinarApi
{
    private $baseUrl;
    private $apiKey;
    private $defaultTimeZone;
    private $error;

    public function __construct($baseUrl, $apiKey, $defaultTimeZone)
    {
        $this->baseUrl          = $baseUrl;
        $this->apiKey           = $apiKey;
        $this->defaultTimeZone  = $defaultTimeZone;

        $this->error = null;
    }

    public function hasError()
    {
        return ! is_null($this->error) ;
    }

    public function resetError()
    {
        $this->error = null;
    }

    public function getError()
    {
        return $this->error ;
    }

    private function buildData(array $special=null)
    {
        $this->resetError();

        $baseData = array('api_key' => $this->apiKey);
        return $special ? array_merge($baseData, $special) : $baseData;
    }

    private function postRequest($segment, array $data, array $headers=array())
    {
        try {
            return \Requests::post($this->baseUrl ."/". $segment, $headers, $data);
        } catch (\Exception $e) {
            $this->error = "something went wrong";
            throw new \Exception("Error Processing Request", 1);
        }
    }

    private function extractByKey($decodedRespons, $key)
    {
        return property_exists($decodedRespons, $key) ? $decodedRespons->$key : $decodedRespons;
    }

    public function processRepsponse($response, $key)
    {
        $decodedRespons = json_decode($response->body);
        if (! $response->success) {
            $this->error = $decodedRespons->message;
        }elseif(! is_null($key)){
            $decodedRespons = $this->extractByKey($decodedRespons, $key);
        }
        return $decodedRespons;
    }

    private function getWebinarsIds($decodedRespons)
    {
        $result = array();
        if (is_array($decodedRespons) ) {
            foreach ($decodedRespons as $webinar)
                $result [] = $webinar->webinar_id;
        }

        return $result;
    }

    public function allWebinars()
    {
        $decodedRespons = $this->webinars();

        $webinarsIds = $this->getWebinarsIds($decodedRespons);

        $webinars = array();

        foreach ($webinarsIds as $webinar_id) {
            $webinars [] = $this->webinar($webinar_id);
        }
        return $webinars;

    }

    public function webinars()
    {
        $data = $this->buildData();
        return $this->processRepsponse($this->postRequest("webinars", $data), "webinars");
    }

    public function webinar($webinar_id)
    {
        $data = $this->buildData(array('webinar_id' => $webinar_id, 
            'timezone' => $this->defaultTimeZone));
        return $this->processRepsponse($this->postRequest("webinar", $data), "webinar");
    }

    public function register($webinar_id, $name, $email, $schedule_id)
    {
        $data = $this->buildData(array(
            'webinar_id' => $webinar_id,
            'name' => $name,
            'email' => $email,
            'schedule' => $schedule_id,
            // 'timezone' => $this->defaultTimeZone //required if you set timezone auto
            ));

        return $this->processRepsponse($this->postRequest("register", $data), "user");
    }
}
