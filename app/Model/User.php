<?php

function get_all_clients($conn)
{
    $sql = "
      SELECT
    clients.client_id,
    clients.name,
    clients.client_code,
    COUNT(cc.contact_id) AS linked_contacts,
    GROUP_CONCAT(contacts.contact_id) AS contact_ids,
    GROUP_CONCAT(contacts.email SEPARATOR ', ') AS contact_emails
FROM clients
LEFT JOIN client_contact cc ON clients.client_id = cc.client_id
LEFT JOIN contacts ON contacts.contact_id = cc.contact_id
GROUP BY clients.client_id
ORDER BY clients.name ASC;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return [];
    }
}

function client_exists($conn, $clientName)
{
    $stmt = $conn->prepare("SELECT COUNT(*) FROM clients WHERE name = ?");
    $stmt->execute([$clientName]);
    return $stmt->fetchColumn() > 0;
}
function insert_client($conn, $data)
{
    $sql = "
      INSERT INTO clients (name, client_code) VALUES(?,?)";

    $stmt = $conn->prepare($sql);
    return $stmt->execute($data);

}
function update_client($conn, $data)
{
    $sql = "
      UPDATE clients SET name=? WHERE client_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

}

function delete_client($conn, $data)
{
    $sql = "
      DELETE FROM clients WHERE client_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

}
function get_client_by_id($conn, $id)
{
    $sql  = "SELECT * FROM clients WHERE client_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $client = $stmt->fetch();

    } else {
        $client = 0;
    }

    return $client;

}
