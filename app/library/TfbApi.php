<?php

namespace App\Library;

class TfbApi
{
    public static function url()
    {
        return [
            'dev'   => [
                'merchant_info'   => "http://api.gctest3.tfb8.com/cgi-bin/v2.0/api_merchant_info_query.cgi",
                'agent_info'      => "http://api.gctest3.tfb8.com/cgi-bin/v2.0/api_agent_info_qry.cgi",
                'merchant_detail' => "http://api.gctest3.tfb8.com/cgi-bin/v2.0/api_merchant_detail_qry.cgi",
                'agent_detail'    => "http://api.gctest3.tfb8.com/cgi-bin/v2.0/api_agent_detail_qry.cgi",
                'ledger'          => "http://192.168.7.80:8082/acc_query",
            ],
            'test'   => [
                'merchant_info'   => "http://api.gctest3.tfb8.com/cgi-bin/v2.0/api_merchant_info_query.cgi",
                'agent_info'      => "http://api.gctest3.tfb8.com/cgi-bin/v2.0/api_agent_info_qry.cgi",
                'merchant_detail' => "http://api.gctest3.tfb8.com/cgi-bin/v2.0/api_merchant_detail_qry.cgi",
                'agent_detail'    => "http://api.gctest3.tfb8.com/cgi-bin/v2.0/api_agent_detail_qry.cgi",
                'ledger'          => "http://192.168.7.83:8082/acc_query",
            ],
            'product'    => [
                'merchant_info'   => "https://api.tfb8.account.com/merchant_info_query.cgi",
                'agent_info'      => "https://api.tfb8.account.com/agent_info_qry.cgi",
                'merchant_detail' => "https://api.tfb8.account.com/merchant_detail_qry.cgi",
                'agent_detail'    => "https://api.tfb8.account.com/agent_detail_qry.cgi",
                'ledger'          => "http://192.168.0.72:8081/acc_query",
            ],
        ];
    }
}
