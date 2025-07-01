<?php
function get_contact_by_id(PDO $conn, int $client_id): array
{
    $sql = "
        SELECT c.contact_id, c.name, c.surname, c.email, cl.client_code
        FROM contacts c
        JOIN client_contact cc ON cc.contact_id = c.contact_id
        JOIN clients cl ON cl.client_id = cc.client_id
        WHERE cc.client_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$client_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function get_all_contacts($conn)
{
    $sql = "
        SELECT
            contacts.contact_id,
            contacts.name,
            contacts.surname,
            contacts.email,
            GROUP_CONCAT(clients.client_code SEPARATOR ', ') AS client_codes,
             GROUP_CONCAT(clients.client_id SEPARATOR ',') AS client_ids
        FROM contacts
        LEFT JOIN client_contact ON contacts.contact_id = client_contact.contact_id
        LEFT JOIN clients ON client_contact.client_id = clients.client_id
        GROUP BY contacts.contact_id, contacts.name, contacts.surname, contacts.email
        ORDER BY contacts.name ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return [];
    }
}
function get_client_by_id($conn, $id)
{
    $sql  = "SELECT * FROM clients WHERE client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result : 0;
}

function unlink_client($conn, $data)
{
    $sql  = "DELETE FROM client_contact WHERE client_id = ? AND contact_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

    return $stmt->rowCount(); // returns number of rows delete
}

function insert_contact(PDO $conn, array $data): void
{
    $sql  = "INSERT INTO contacts (name, surname, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}
