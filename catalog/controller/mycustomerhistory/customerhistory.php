<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ControllerMycustomerhistoryCustomerhistory extends Controller
{

    public function index()
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://f44551qi-beget-tech.retailcrm.ru/api/v5/customers/history?apiKey=xxx',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            curl_close($curl);


            $data = json_decode($response, true);

            // Массив для хранения данных клиентов

            $customers = [];


            // Извлечение данных и запись в массив

            if (isset($data['history']) && is_array($data['history'])) {

                foreach ($data['history'] as $entry) {

                    if (isset($entry['customer']) && is_array($entry['customer'])) {

                        $firstName = $entry['customer']['firstName'] ?? '';

                        $lastName = $entry['customer']['lastName'] ?? '';

                        $email = $entry['customer']['email'] ?? '';

                        $operatorComment = $entry['operatorComment'] ?? '';


                        // Извлечение телефона

                        $phone = '';

                        if (isset($entry['customer']['phones']) && is_array($entry['customer']['phones'])) {

                            $phoneArray = array_column($entry['customer']['phones'], 'number');

                            $phone = implode(', ', $phoneArray);
                        }


                        // Извлечение адреса

                        $address = '';

                        if (isset($entry['customer']['address']) && is_array($entry['customer']['address'])) {

                            $address = $entry['customer']['address']['text'] ?? '';
                        }


                        // Заполняем массив только если есть хотя бы одно заполенное поле

                        if (!empty($firstName) || !empty($lastName) || !empty($email) || !empty($phone) || !empty($address) || !empty($operatorComment)) {


                            // Добавляем данные клиента в массив

                            $customers[] = [

                                'firstName' => $firstName,

                                'lastName' => $lastName,

                                'email' => $email,

                                'phone' => $phone,

                                'address' => $address,

                                'operatorComment' => $operatorComment,

                            ];
                        }
                    }
                }
            } else {

                echo "Нет данных для обработки.";
            }


            $this->load->model('myretailcrm/retailcrm');


            $this->model_myretailcrm_retailcrm->updateCustomers($customers);
        } catch (Exception $e) {
            //Обработка исключения
            echo "Произошла ошибка: " . $e->getMessage();
        }      

    }
}
