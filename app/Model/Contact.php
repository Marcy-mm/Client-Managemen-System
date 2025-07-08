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
    $sql = "SELECT
                c.contact_id,
                c.name,
                c.surname,
                c.email,
                COUNT(cc.client_id) AS client_count,
                GROUP_CONCAT(cl.name SEPARATOR ', ') AS client_names,
                GROUP_CONCAT(cl.client_code SEPARATOR ', ') AS client_codes,
                GROUP_CONCAT(cl.client_id SEPARATOR ',') AS client_ids
            FROM contacts c
            LEFT JOIN client_contact cc ON c.contact_id = cc.contact_id
            LEFT JOIN clients cl ON cc.client_id = cl.client_id
            GROUP BY c.contact_id
            ORDER BY c.name";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}
function get_clients($conn)
{
    $stmt = $conn->prepare("SELECT client_id, name, client_code FROM clients ORDER BY name ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_clientid($conn, $id)
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


function is_contact_linked($conn, $contact_id, $client_id)
{
    $sql = "SELECT * FROM client_contact
            WHERE contact_id = ? AND client_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$contact_id, $client_id]);

    return $stmt->rowCount() > 0;
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
