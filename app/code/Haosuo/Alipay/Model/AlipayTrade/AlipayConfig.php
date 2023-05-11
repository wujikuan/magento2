<?php

namespace Haosuo\Alipay\Model\AlipayTrade;

class AlipayConfig
{
    /**
     * @return string[]
     */
    public static function getConfig(){

        return array (
            //应用ID,您的APPID。
            'app_id' => "2016092400585786",

            //商户私钥
            'merchant_private_key' => "MIIEpAIBAAKCAQEApf5pGGMIrW+qZJg5JA2u3ZuHPmIsaVqJCPeVfAdb0JOGIiqWHa/Vgis+L+x4dCzffM6OrvJAYW2Y7RRBnfIstaRqGkP3R/7qZpv0EMjUffKDLGeDIeDpmK9gT5kbJoPveygSRzBERNtMGz+Dvm1p10v3pgGoPmX8pYgJ5mov+4EnpR7/EfBkJ3panyJkPgbVtR9QiOTM+qXXrmtlmllR+Bh1D2W52fZkWdKk+djvEDYXD56yalK0KdZzun3VsPBhd1sR4Sb5IKhq3D8OcvPMnKFy8Lf1iQhkI+GvJfmhKWmavkdMWHVPe8yOfhV4Lj/7DQoyE5qXnjrANzth6UU/vwIDAQABAoIBAQCDeVI8YpRI6p0HOBpqF/xcwgcIvjF28KKBW7gmmJ18QpyHCzwDH3X4t891ndJ6EeChtekFYBLmGCx7+wNN2rbW1/bB/vspJxr5UqSpdqf3adHDpekTOFVM/ftGkHoUs4NHBKIViIFKGHfbTR9IANlIEX+BGObtJVJKuck9mvWOOaIZdfgRpJH8Olpxsvi46kd5XI2expeRyafNy3hVFq4rI+mSktcuVDArvTrFcrmkvMVbe/ARgdO1bNnhPtS0m99yJ0KWgamjQh/GWH+5SIP/i1BAcP020C1h7mwP1va0nM1N+KVwoMSR3TAxROkCIaWHo7hrx9bvTujQdonZiWshAoGBAOFPNNUjWCmHK2oQUCJgDnlXmyBq0QeLmKrkFcn/zlKY5RjFMC7gi1RBgAW3jNKCdfc9vUedZ0f2AH/kOjIFWqvupQaBbDwisQRkCMhRg/54isizNoE4DLcg8WrfktVIr0mvOdNkc59lg1B6BrEO0M4VmXPL77ZJkEptBgwxZm5FAoGBALyazUGCCFw8yG0nmgDJndm2uQCyU3+O4piF+7NQIwTi8soyl/kV93L32EgDFX3KshaB4Vw3Bd7wiyFvma4HoQ1PY+WMVNi0SVUHRbrZBFhFRemlDDUXc6LSZA47JGHOhaKyL+EveSZgzoxwafIc9I2QelmiWimsswVoNO3LgKgzAoGBANl3se7nJnku1mCRTkdfn5jPThOEBi3aN4j6jW1jUqm2manG6jLU2KaCcicSewHUmjgBh2hQLRfwAZtvlhwhvtqkhPQ6h1WjxmgOhcuTomtDvfDpyXn/t4YqbdpR4U+r0ES2tIGtO0xmltBed4b+XdwMTQjPlpfDeVnc0TGDnPJhAoGABJz4tkh68d6evGTClJBZ5xBcGBNEjZMuIbLtRCaKpLz0WVPBeds6sTo0tRY2OVxy3LASlhHWd+yESq4TITmYi93MQLoc/SvzuzuxTe+u9oXDPwLFeKr5LhtdTSfPLQFDLYaUOzrRCMpHYxIA1WY16RyGkZiyAeDyhAhXexWGMRcCgYB2UbfBzZRR7uV4oJjpPhKJTanoo4EpVSCy4RaIWp7qZklMY70pCVJe+IVaq1sNt3wvMmV8ObyLNeX6AubxJtTNnwZ82KuMNALfXw8vcWTufxBUnTOcBOYHgMhN3ENGPVGfiSDwD+FKuOwoK91SgmR1Me9Ny80f8q7XNNapUa9yHw==",


            //异步通知地址
            'notify_url' => "http://localhost/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",

            //同步跳转
            'return_url' => "checkout/onepage/success",

            //编码格式
            'charset' => "UTF-8",

            //签名方式
            'sign_type'=>"RSA2",

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApDbExVPrzKsspaHxd5wCKoB9qzHjcx0uaCg3qR9SDgdmKXtU4vhLltXMblROU4v9N96lJxGflmuDBsyXGuFnmm5lrOBA/Mx3v/NakmeP17VXPvobWJd5Uk4LZR1k2RtGn9dl78KPFT40iIICf+oYm+oPjojK5pUxUNC6HHCHtxc9GgXoztsYb6McElz9ZgKBV08OCtxshiwNL6wn6Wk/Rh2WrfcCa+3qRq8kvYNubPdwMX2CfcQkP5QeXssGy4EvpJBbn5EHaCcODtmMIvO/C3WbjJ+5Bwnvq9m8X16+wVij2v/jPwsZ8S+9VIJ/ANVaT42WpSf/ItKYG8JtLMZW2wIDAQAB",

            //日志路径
            'log_path' => "",
        );
    }
}
