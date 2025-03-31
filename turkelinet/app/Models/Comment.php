<?php
// app/Models/Comment.php

// Handles database operations related to comments

require_once dirname(__DIR__) . '/Core/Database.php';

class Comment {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Fetches approved comments for a specific post ID, along with user information.
     * Fetches only top-level comments for now (parent_comment_id IS NULL).
     * Replies can be fetched separately or with a more complex recursive query later.
     *
     * @param int $postId The ID of the post
     * @return array|false Array of comments or false on error
     */
    public function getCommentsByPostId(int $postId) {
        try {
            // Select approved comments for the given post, join with users table
            // Fetch only top-level comments first
            $sql = "SELECT
                        c.comment_id, c.comment, c.created_at, c.parent_comment_id,
                        u.user_id, u.username, u.full_name as author_name, u.profile_picture
                    FROM
                        comments c
                    JOIN
                        users u ON c.user_id = u.user_id
                    WHERE
                        c.post_id = :post_id
                        AND c.is_approved = 1
                        AND c.parent_comment_id IS NULL -- Fetch only top-level comments
                    ORDER BY
                        c.created_at ASC"; // Or DESC for newest first

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $stmt->execute();

            $comments = $stmt->fetchAll();

            // TODO: Optionally fetch replies for each comment here or handle in view/JS

            return $comments;

        } catch (PDOException $e) {
            error_log("Error fetching comments for post ({$postId}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Adds a new comment to the database.
     *
     * @param int $postId The ID of the post being commented on
     * @param int $userId The ID of the user making the comment
     * @param string $commentText The comment content
     * @param int|null $parentId The ID of the parent comment if this is a reply
     * @param bool $isApproved Whether the comment is initially approved
     * @return bool|int The ID of the new comment on success, false on failure
     */
    public function addComment(int $postId, int $userId, string $commentText, ?int $parentId = null, bool $isApproved = true) {
        try {
            $sql = "INSERT INTO comments (post_id, user_id, parent_comment_id, comment, is_approved, created_at)
                    VALUES (:post_id, :user_id, :parent_id, :comment, :is_approved, NOW())";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':parent_id', $parentId, PDO::PARAM_INT); // Allows NULL
            $stmt->bindParam(':comment', $commentText, PDO::PARAM_STR);
            $stmt->bindParam(':is_approved', $isApproved, PDO::PARAM_BOOL);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId(); // Return the new comment's ID
            } else {
                return false;
            }

        } catch (PDOException $e) {
            error_log("Error adding comment: " . $e->getMessage());
            return false;
        }
    }

    // Methods for fetching replies, deleting comments, approving comments etc. can be added here.

}
?>
