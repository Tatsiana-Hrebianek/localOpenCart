<?php
class ModelMyretailcrmRetailcrm extends Model
{
    public function updateCustomers($customerData)
    {
        foreach ($customerData as $customer) {
            // Получаем данные из retailCRM
            $email = isset($customer['email']) ? $this->db->escape($customer['email']) : '';
            $firstName = isset($customer['firstName']) ? $this->db->escape($customer['firstName']) : '';
            $lastName = isset($customer['lastName']) ? $this->db->escape($customer['lastName']) : '';
            $phone = isset($customer['phone']) ? $this->db->escape($customer['phone']) : '';
            // Проверка корректной структуры адреса
            $address = isset($customer['address']) && is_array($customer['address']) && isset($customer['address']['text'])
                ? $this->db->escape($customer['address']['text'])
                : '';
            $operatorComment = isset($customer['operatorComment']) ? $this->db->escape($customer['operatorComment']) : '';

            // Отладочные сообщения
            error_log("Email: $email, First Name: $firstName, Last Name: $lastName, Phone: $phone");

            // Проверяем, существует ли клиент в базе данных по email
            $query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "customer` WHERE email = '$email'");

            if ($query->num_rows) {
                // Обновляем существующего клиента
                $customer_id = $query->row['customer_id'];
                $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET 
                    firstname = '$firstName', 
                    lastname = '$lastName', 
                    telephone = '$phone', 
                    custom_field = '$operatorComment'
                    WHERE customer_id = '" . (int)$customer_id . "'");

                // Проверяем, есть ли адрес
                if (!empty($address)) {
                    // Предполагаем, что у клиента только один адрес (для упрощения)
                    $address_query = $this->db->query("SELECT address_id FROM `" . DB_PREFIX . "address` WHERE customer_id = '" . (int)$customer_id . "'");

                    if ($address_query->num_rows) {
                        // Обновляем существующий адрес
                        $this->db->query("UPDATE `" . DB_PREFIX . "address` SET
                            address_1 = '$address'
                            WHERE address_id = '" . (int)$address_query->row['address_id'] . "'");
                    } else {
                        // Если адрес не найден, добавляем его
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET
                            customer_id = '" . (int)$customer_id . "',
                            address_1 = '$address'");
                    }
                }
            } else {
                // Создаем нового клиента, если не найден
                $this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET 
                    firstname = '$firstName', 
                    lastname = '$lastName', 
                    email = '$email', 
                    telephone = '$phone', 
                    custom_field = '$operatorComment',                 
                    date_added = NOW()");

                // Добавляем адрес для нового клиента
                if (!empty($address)) {
                    $customer_id = $this->db->getLastId(); // Получаем новый ID клиента
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET
                        customer_id = '" . (int)$customer_id . "',
                        address_1 = '$address'");
                }
            }
        }
    }
}
