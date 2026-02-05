<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $destination_id = $_POST['destination_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    if (empty($comment) || $rating < 1 || $rating > 5) {
        header("Location: detail.php?id=$destination_id&error=invalid_input");
        exit;
    }

    try {
        // High-security check: Verify user has an APPROVED booking for this destination
        // Only 'approved' status allows reviewing (meaning they actually visited/completed transaction)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND destination_id = ? AND status = 'approved'");
        $stmt->execute([$user_id, $destination_id]);
        if ($stmt->fetchColumn() == 0) {
            header("Location: detail.php?id=$destination_id&error=unauthorized_review");
            exit;
        }

        // Check if review exists for UPDATE or INSERT
        $stmt = $pdo->prepare("SELECT id, image FROM reviews WHERE user_id = ? AND destination_id = ?");
        $stmt->execute([$user_id, $destination_id]);
        $existing_review = $stmt->fetch();

        $image_path = null;

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "assets/img/reviews/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $allowed_extensions = array("jpg", "jpeg", "png", "webp");

            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = uniqid() . '.' . $file_extension;
                $target_file = $target_dir . $new_filename;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                    // Delete old image if updating
                    if ($existing_review && $existing_review['image'] && file_exists($existing_review['image'])) {
                        unlink($existing_review['image']);
                    }
                }
            }
        } elseif ($existing_review) {
            // Keep existing image if no new upload
            $image_path = $existing_review['image'];
        }

        if ($existing_review) {
             // UPDATE review
             $stmt = $pdo->prepare("UPDATE reviews SET rating = ?, comment = ?, image = ?, created_at = CURRENT_TIMESTAMP WHERE id = ?");
             $stmt->execute([$rating, $comment, $image_path, $existing_review['id']]);
        } else {
             // INSERT review
             $stmt = $pdo->prepare("INSERT INTO reviews (user_id, destination_id, rating, comment, image) VALUES (?, ?, ?, ?, ?)");
             $stmt->execute([$user_id, $destination_id, $rating, $comment, $image_path]);
        }

        // Update destination average rating
        $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE destination_id = ?");
        $stmt->execute([$destination_id]);
        $avg_rating = round($stmt->fetch()['avg_rating'], 1);

        $stmt = $pdo->prepare("UPDATE destinations SET rating = ? WHERE id = ?");
        $stmt->execute([$avg_rating, $destination_id]);

        header("Location: detail.php?id=$destination_id&success=review_submitted#reviews");
        exit;
    } catch (PDOException $e) {
        header("Location: detail.php?id=$destination_id&error=db_error");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
