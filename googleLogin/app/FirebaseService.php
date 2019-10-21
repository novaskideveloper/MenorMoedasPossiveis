<?php

namespace App;

use Exception;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExists;

class FirebaseService
{

    /**
     * @var Firebase
     */
    protected $firebase;

    public function __construct()
    {
        $serviceAccount = ServiceAccount::fromArray([
            "type" => "service_account",
            "project_id" => "enviacvfacil",
            "private_key_id" => "742e3dc689cfea21a5186507e5402d1fc803a901",
            "private_key"=> "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDBoFXNXfBvz80w\nL/lX5qluAjTDmuG/ArICMEiy07fBnQShSE0T9qDKnrchbM2lRjAh6/VWSbDAt+TG\nm7zZxcnpWvMax5uSxdnRrgkq9U0W+As2dCS3G01SWJ6JCeELJ7h1I4Q5iVIjJtEg\n5dINstVuj8Y/knFmitutpGJT4Yns55v9SSA0gOW/bUnYFH2cOxWtNNrG6B+a/uau\nwkD1DTtAx9oiZ4yA+WTFDDPAACmClWn+1MHrAYPc5TBy6GEiYXmvd1pNm1gx5y91\nei6KbYqKbLe9CRmfZQn+USG2SW7A8biyNgTD2K8mqrAAkUKIOsjhlpjvUkCperEM\n6sw5NFvdAgMBAAECggEAH0D0qJYRsdKegbk0q7oZPh8QCkTNTIowVocZrPfcsN1Z\n+niTrHXCRYJx9+y6kwNt0IPoUXnKlIj6Xg6UYKh663ZZSEjQ9VOHUb+gTjVTMLJw\nCOWdaYr4I6MXT/Jux2Ctdwb5GFpwjij9V6yRB9IGfnapnuhR8qXWSMSAdJ6ypzEB\ngGmIbb1HZ+gczMIL0I5AUE5JusLM1mUmkFJ6oVt5IR36DRHnWhRvMm2Es/Eq7llZ\nP1BaxB573nSHzmUrQBNkuQ0KjEcRlTJLSTtQxjN25lGlHWQ8ZdwAPiGygUf5ww0a\nLAqtl7GA48dL0KT6w020dyjwBaxmnZV7LGm9F2mOxwKBgQDnfWgxp1QvrXMRx63r\noNqB8PeXx0vuZHF1w0A9ibwTQ/IQJo+BhoeHR0Wu2efREfgYZ+XidIdEgAxfOj2/\nH7gPUF9MVWwvjXv3Hci6hrp4qxBzb7kivZUbJUUi4U5vumX16CdpvwN/m+QxJEbc\nBh7RF1hpPspd2+YpXyiyxyj+qwKBgQDWIKBh0luFzHyRRLyo+0p8UgXd8zgP7u7M\nflcrGV+LHvFGso4jTLrk6XXbdpi6zO5NQPv2kxBWHLgkEmtC9XgjFFueYBvA82kj\nqbqTkiWtwAhLV+Qx1L8l1tWBefC/FqRqUpXLtmLTkz2qlEzuezmhcpSmCYEK3dcc\nMmaTJM5vlwKBgBGbp2ZHfQ7XWa5biu4mYRiLNLoVzb/HYh38CRHlPSoV+/6ggD/w\n9LQkhrdjGc/8Vuu8U28jP/rE5qwDqRi1l/GKQoVy5fTEBU7ptBAEGYD+OhUdxW5Q\nD1xuPFEH5Eh5XDIVQ5I2llSJjhTy1nT7/jIXXYE75na3OE2jUFww6/xbAoGAJL2S\nZ1Z9x7ZvhUmDCYecnzo/sXajHvDDXqkq8cU3xJo5kgTfKKVoyBBa3Z461IqHNRA6\na3OOcQgafG4Ao4uU4ogCtGkPOgtJ9gmQbvO7rVVu1uasy/QSHD8BgWbX7SHIcknb\nVAnmaSExioxJqv3PxKjxYo/s6V1pJHhtaz/8f98CgYEAoPmMOJ/KU1QYYyXLw3pC\n0uMxKjAGc0uRJl+o3AMWD5XL34xq0ragDL/mlOpqTGRK8o6fpPsfdmN0A5SJrUCS\neGsADHdpltRbHScPuWtgPE2Z8tQz/0DHGBDKOcTRJwgTcU6T36RNq8jnG/DcQMHK\nYyB0krcMilZx0fEldd8RitU=\n-----END PRIVATE KEY-----\n",
            "client_email"=> "enviacvfacil@appspot.gserviceaccount.com",
            "client_id"=> "116323012220722470438",
            "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
            "token_uri"=> "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url"=> "https://www.googleapis.com/robot/v1/metadata/x509/enviacvfacil%40appspot.gserviceaccount.com"
        ]);

        $this->firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri(config('services.firebase.database_url'))
            ->create();
    }

    /**
     * Verify password agains firebase
     * @param $email
     * @param $password
     * @return bool|string
     */
    public function verifyPassword($email, $password)
    {
        try {
            $response = $this->firebase->getAuth()->verifyPassword($email, $password);
            return $response->uid;

        } catch(FirebaseEmailExists $e) {
            logger()->info('Error login to firebase: Tried to create an already existent user');
        } catch(Exception $e) {
            logger()->error('Error login to firebase: ' . $e->getMessage());
        }
        return false;
    }
}