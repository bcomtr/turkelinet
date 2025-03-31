<?php
// app/Models/Post.php

require_once dirname(__DIR__) . '/Core/Database.php';

class Post {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Fetches the latest published posts.
     * @param int $limit
     * @return array|false
     */
    public function getLatestPosts(int $limit = 5) {
        try {
            $sql = "SELECT p.post_id, p.title, p.slug, p.content, p.featured_image, p.created_at, c.name as category_name, c.slug as category_slug, u.full_name as author_name, (SELECT COUNT(*) FROM comments WHERE post_id = p.post_id AND is_approved = 1) as comment_count
                    FROM posts p JOIN categories c ON p.category_id = c.category_id JOIN users u ON p.user_id = u.user_id
                    WHERE p.status = 'published' AND c.type = 'post' ORDER BY p.created_at DESC LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) { error_log("Error fetching latest posts: " . $e->getMessage()); return false; }
    }

    /**
     * Fetches a single published post by its slug.
     * @param string $slug
     * @return array|false
     */
    public function getPostBySlug(string $slug) {
        try {
            $sql = "SELECT p.post_id, p.title, p.slug, p.content, p.featured_image, p.created_at, p.views, c.category_id, c.name as category_name, c.slug as category_slug, u.user_id as author_id, u.username as author_username, u.full_name as author_name
                    FROM posts p JOIN categories c ON p.category_id = c.category_id JOIN users u ON p.user_id = u.user_id
                    WHERE p.slug = :slug AND p.status = 'published' AND c.type = 'post' LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) { error_log("Error fetching post by slug ({$slug}): " . $e->getMessage()); return false; }
    }

    /**
     * Fetches all published posts.
     * TODO: Add pagination parameters ($limit, $offset) later.
     *
     * @return array|false Array of posts or false on error
     */
    public function getAllPublishedPosts() {
        try {
            $sql = "SELECT
                        p.post_id, p.title, p.slug, p.content, p.featured_image, p.created_at,
                        c.name as category_name, c.slug as category_slug,
                        u.full_name as author_name,
                        (SELECT COUNT(*) FROM comments WHERE post_id = p.post_id AND is_approved = 1) as comment_count
                    FROM
                        posts p
                    JOIN
                        categories c ON p.category_id = c.category_id
                    JOIN
                        users u ON p.user_id = u.user_id
                    WHERE
                        p.status = 'published' AND c.type = 'post'
                    ORDER BY
                        p.created_at DESC";
            // No LIMIT for now, fetch all

            $stmt = $this->conn->query($sql); // Use query() for simple select without parameters
            return $stmt->fetchAll();

        } catch (PDOException $e) {
            error_log("Error fetching all published posts: " . $e->getMessage());
            return false;
        }
    }

    // incrementPostViews, createPost, updatePost, deletePost etc.
}
?>
