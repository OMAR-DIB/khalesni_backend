<?php
// Include database connection file
require_once '../connection.php'; // Ensure this file contains your database connection setup

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data (order_id)
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['order_id']) || empty($input['order_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Order ID is required']);
        exit;
    }

    $order_id = intval($input['order_id']);

    // Start the transaction
    $conn->begin_transaction();

    try {
        // Step 1: Delete related order_items
        $deleteOrderItemsQuery = "DELETE FROM order_items WHERE order_id = ?";
        $stmtOrderItems = $conn->prepare($deleteOrderItemsQuery);
        $stmtOrderItems->bind_param("i", $order_id);
        $stmtOrderItems->execute();

        if ($stmtOrderItems->affected_rows > 0) {
            // Step 2: Delete the order (Escape `order` table name with backticks
            $deleteOrderQuery = "DELETE FROM `order` WHERE id = ?";
            $stmtOrder = $conn->prepare($deleteOrderQuery);
            $stmtOrder->bind_param("i", $order_id);
            $stmtOrder->execute();

            if ($stmtOrder->affected_rows > 0) {
                // Commit the transaction
                $conn->commit();
                echo json_encode(['status' => 'success', 'message' => 'Order deleted successfully']);
            } else {
                // Rollback if no order was found
                $conn->rollback();
                echo json_encode(['status' => 'error', 'message' => 'Order not found']);
            }
        } else {
            // Rollback if no order_items were found
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'No order items found for the given Order ID']);
        }

        // Close the statements
        $stmtOrderItems->close();
        $stmtOrder->close();
    } catch (Exception $e) {
        // Rollback on exception
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

// Close the connection
$conn->close();
?>
