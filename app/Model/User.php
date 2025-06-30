<?php

function get_all_clients($conn)
{
    $sql = "
        SELECT
            clients.name AS client_name,
            clients.client_code,
            COUNT(client_contact.contact_id) AS linked_contacts
        FROM
            clients
        LEFT JOIN
            client_contact ON clients.client_id = client_contact.client_id
        GROUP BY
            clients.client_id, clients.name, clients.client_code
        ORDER BY
            clients.name ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return [];
    }
}
