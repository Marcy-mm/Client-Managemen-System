<?php

function get_all_clients($conn)
{
    $sql = "
        SELECT
        clients.client_id,
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


function insert_client($conn, $data)
{
    $sql = "
      INSERT INTO clients (name, client_code) VALUES(?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

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
