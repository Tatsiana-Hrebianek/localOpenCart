<?php

// define('API');
// https://f44551qi-beget-tech.retailcrm.ru/api/v5/customers/history?apiKey=dkpkrAfMs7fUs9bGZMkmGwsu1troobpl

class ControllerMycustomerhistoryCustomerhistory extends Controller
{

    public function index()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://f44551qi-beget-tech.retailcrm.ru/api/v5/customers/history?apiKey=dkpkrAfMs7fUs9bGZMkmGwsu1troobpl',
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

// Извлечение данных и вывод

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


            // Формируем вывод только если есть хотя бы одно заполенное поле

            if (!empty($firstName) || !empty($lastName) || !empty($email) || !empty($phone) || !empty($address) || !empty($operatorComment)) {

                $fullName = trim("$firstName $lastName");

                echo "ФИО: $fullName\n";

                echo "Email: $email\n";

                echo "Телефон: $phone\n";

                echo "Адрес: $address\n";

                echo "Комментарий оператора: $operatorComment\n";

                echo str_repeat("-", 40) . "\n<br>"; // Разделитель

            }

        }

    }

} else {

    echo "Нет данных для обработки.";

}



// // Извлечение данных и вывод

// if (isset($data['history']) && is_array($data['history'])) {

//     foreach ($data['history'] as $entry) {

//         if (isset($entry['customer']) && is_array($entry['customer'])) {

//             $firstName = $entry['customer']['firstName'] ?? '';

//             $lastName = $entry['customer']['lastName'] ?? '';

//             $fullName = trim("$firstName $lastName");

//             $email = $entry['customer']['email'] ?? '';

            

//             // Извлечение телефона

//             $phone = '';

//             if (isset($entry['customer']['phones']) && is_array($entry['customer']['phones'])) {

//                 $phoneArray = array_column($entry['customer']['phones'], 'number');

//                 $phone = implode(', ', $phoneArray);

//             }


//             // Извлечение адреса

//             $address = '';

//             if (isset($entry['customer']['address']) && is_array($entry['customer']['address'])) {

//                 $address = $entry['customer']['address']['text'] ?? '';

//             }


//             // Извлечение комментария оператора

//             $operatorComment = $entry['operatorComment'] ?? '';


//             // Вывод данных

//             echo "ФИО: $fullName \n";

//             echo "Email: $email\n";

//             echo "Телефон: $phone\n";

//             echo "Адрес: $address\n";

//             echo "Комментарий оператора: $operatorComment\n";

//             echo str_repeat("-", 40) . "\n<br>"; // Разделитель

//         }

//     }

// } else {

//     echo "Нет данных для обработки.";

// }




        // if (isset($data['history']) && is_array($data['history'])) {

        //     foreach ($data['history'] as $entry) {

        //         if (isset($entry['customer']) && is_array($entry['customer'])) {

        //             if (isset($entry['customer']['firstName'])) {

        //                 // Выводим firstName

        //                 echo '- ' . $entry['customer']['firstName'] . '-' . $entry['customer']['lastName'] . '-' . $entry['customer']['email'] 
        //                 . '-' . $entry['customer']['number'] 
        //                 . '-' . $entry['customer']['adress'] . '-' . $entry['customer']['lastName'] .  PHP_EOL;
        //             }
        //         }
        //     }
        // } else {

        //     echo "Нет данных для обработки.";
        // }
    }
}
