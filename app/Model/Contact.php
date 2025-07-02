<?php
function get_contact_by_id(PDO $conn, int $contact_id): array
{
    $sql  = "SELECT * FROM contacts WHERE contact_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$contact_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result : [];
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
function get_all_clients($conn)
{
    $stmt = $conn->prepare("SELECT client_id, name, client_code FROM clients ORDER BY name ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function is_contact_linked($conn, $contact_id)
{
    $stmt = $conn->prepare("SELECT COUNT(*) FROM client_contact WHERE contact_id = ?");
    $stmt->execute([$contact_id]);
    return $stmt->fetchColumn() > 0;
}
function get_unlinked_contacts($conn)
{
    $sql = "
        SELECT * FROM contacts
        WHERE contact_id NOT IN (
            SELECT contact_id FROM client_contact
        )
        ORDER BY name ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function link_contact_to_client($conn, $client_id, $contact_id)
{
    $stmt = $conn->prepare("INSERT INTO client_contact (client_id, contact_id) VALUES (?, ?)");
    return $stmt->execute([$client_id, $contact_id]);
}
