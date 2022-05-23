<?php

class MailChimpException extends Exception { };

/**
 * Class MailChimp
 */

// Taken from the MailChimp API Example code - still in PR state.

class MailChimp {

    private $api_uri;
    private $api_user = 'api_v3';
    private $api_key = BOX_MAILCHIMP_NEWSLETTER_API_KEY; 
    private $user_agent = 'Zen Cart Integration'; 

    public function __construct($api_key)
    {
        $domain = explode('-', $api_key)[1];
        $this->api_uri = 'https://' . $domain . '.api.mailchimp.com/3.0';
        $this->api_key = $api_key;
    }

    private function _execute_request($method, $endpoint, Array $payload = [])
    {
        $endpoint = $this->api_uri.$endpoint;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, $this->api_user.':'.$this->api_key);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);

        switch ($method)
        {
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                break;
            case 'PATCH':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                break;
            case 'GET':
                $endpoint .= '?' . http_build_query($payload);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                break;
        }

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        $result = curl_exec($ch);

        return json_decode($result);
    }

    /**
     * List: Add a new subscriber
     * @docs http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/
     * @param array $options
     * @return mixed
     * @throws MailChimpException
     *
     * Usage Example:
     *         $obj->list_add_subscriber([
     *             'id_list' => 'ID_LIST',    // Required
     *             'email' => 'EMAIL',        // Required
     *             'status' => 'STATUS',    // Optional
     *             'merge_fields' => [        // Optional
     *                 'FNAME' => 'NAME',
     *                 ...
     *             ],
      *         ]);
     *
     */
    public function list_add_subscriber(Array $options)
    {
        // Check required params
        if ( ! isset($options['id_list']) OR ! isset($options['email']))
        {
            throw new MailChimpException('Parameters not set on '.__METHOD__);
        }

        $endpoint = '/lists/' . $options['id_list'] . '/members/';
        $payload = [
            'email_address' => $options['email'],           
            'status' => 'pending', 
        ];
        if (isset($options['merge_fields']) AND $options['merge_fields'])
        {
            $payload['merge_fields'] = $options['merge_fields'];
        }

        return $this->_execute_request('POST', $endpoint, $payload);
    }
    public function list_update_subscriber(Array $options)
    {
        // Check required params
        if ( ! isset($options['id_list']) OR ! isset($options['email']))
        {
            throw new MailChimpException('Parameters not set on '.__METHOD__);
        }
        $subscriber_hash = md5(strtolower($options['email'])); 
        $endpoint = '/lists/' . $options['id_list'] . '/members/' . $subscriber_hash;

        $payload = [
            'email_address' => $options['email'],
        ];
        if (isset($options['merge_fields']) AND $options['merge_fields'])
        {
            $payload['merge_fields'] = $options['merge_fields'];
        }

        return $this->_execute_request('PATCH', $endpoint, $payload);
    }

    /**
     * List: Delete a subscriber
     * @docs http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/
     * @param array $options
     * @return mixed
     * @throws MailChimpException
     *
     * Usage Example:
     *         $obj->list_del_subscriber([
     *             'id_list' => 'ID_LIST',    // Required
     *             'email' => 'EMAIL'         // Required
      *         ]);
     *
     */
    public function list_del_subscriber(Array $options)
    {
        // Check required params
        if ( ! isset($options['id_list']) OR ! isset($options['email']))
        {
            throw new MailChimpException('Parameters not set on '.__METHOD__);
        }

        $subscriber_hash = md5(strtolower($options['email']));
        $payload = array(); 
        $endpoint = '/lists/' . $options['id_list'] . '/members/'.$subscriber_hash;
        return $this->_execute_request('DELETE', $endpoint, $payload);
    }

    /**
     * List: Check if email address is a subscriber
     * @docs http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#read-get_lists_list_id_members_subscriber_hash
     * @param array $options
     * @return mixed
     * @throws MailChimpException
     *
     * Usage Example:
     *         $obj->list_check_subscriber([
     *             'id_list' => 'ID_LIST',    // Required
     *             'email' => 'EMAIL'         // Required
      *         ]);
     *
     */
    public function list_check_subscriber(Array $options)
    {
        // Check required params
        if ( ! isset($options['id_list']) OR ! isset($options['email']))
        {
            throw new MailChimpException('Parameters not set on '.__METHOD__);
        }
        $subscriber_hash = md5(strtolower($options['email'])); 
        $endpoint = '/lists/' . $options['id_list'] . '/members/' . $subscriber_hash;
        $payload = array(); 
        return $this->_execute_request('GET', $endpoint, $payload);
    }
}