<?php
// app/Models/Forum.php - Clean Version

require_once dirname(__DIR__) . '/Core/Database.php';

class Forum {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
        if (!$this->conn) {
            // Optionally throw an exception if connection fails in constructor
            throw new Exception("Forum Model: Database connection failed.");
        }
    }

    /**
     * Fetches forum topics for a specific page.
     * Orders by sticky status and last activity.
     * Includes author info and reply count.
     *
     * @param int $limit Number of topics per page
     * @param int $pageNumber Current page number (starts from 1)
     * @return array|false Array of topics or false on error
     */
    public function getTopics(int $limit = 15, int $pageNumber = 1) {
        $offset = ($pageNumber > 0) ? ($pageNumber - 1) * $limit : 0;
        try {
            $sql = "SELECT
                        t.topic_id, t.title, t.created_at, t.last_reply_at, t.is_sticky, t.is_locked,
                        u.user_id as author_id, u.username as author_username, u.full_name as author_name,
                        (SELECT COUNT(*) FROM forum_posts fp WHERE fp.topic_id = t.topic_id) as reply_count,
                        (SELECT MAX(fp.created_at) FROM forum_posts fp WHERE fp.topic_id = t.topic_id) as actual_last_reply_at
                    FROM
                        forum_topics t
                    JOIN
                        users u ON t.user_id = u.user_id
                    ORDER BY
                        t.is_sticky DESC,
                        COALESCE(actual_last_reply_at, t.created_at) DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $topics = $stmt->fetchAll();
            return $topics;
        } catch (PDOException $e) {
            error_log("Error fetching forum topics (page {$pageNumber}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gets the total count of forum topics.
     *
     * @return int|false Total number of topics or false on error
     */
    public function getTotalTopicCount() {
        try {
            $sql = "SELECT COUNT(*) FROM forum_topics";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error getting total topic count: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetches a single forum topic by its ID.
     * Includes author information.
     *
     * @param int $topicId The ID of the topic
     * @return array|false The topic data or false if not found/error
     */
    public function getTopicById(int $topicId) {
        try {
            $sql = "SELECT
                        t.topic_id, t.title, t.created_at, t.is_sticky, t.is_locked,
                        u.user_id as author_id, u.username as author_username, u.full_name as author_name
                    FROM
                        forum_topics t
                    JOIN
                        users u ON t.user_id = u.user_id
                    WHERE
                        t.topic_id = :topic_id
                    LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':topic_id', $topicId, PDO::PARAM_INT);
            $stmt->execute();
            $topic = $stmt->fetch();
            return $topic; // Returns false if not found
        } catch (PDOException $e) {
            error_log("Error fetching topic by ID ({$topicId}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetches posts (replies) for a specific forum topic.
     * Includes author information. Orders chronologically.
     *
     * @param int $topicId The ID of the topic
     * @param int $limit Number of posts per page
     * @param int $offset For pagination
     * @return array|false Array of posts or false on error
     */
    public function getPostsByTopicId(int $topicId, int $limit = 50, int $offset = 0) { // Increased limit for testing
        try {
            $sql = "SELECT
                        fp.post_id, fp.content, fp.created_at,
                        u.user_id as author_id, u.username as author_username, u.full_name as author_name, u.profile_picture
                    FROM
                        forum_posts fp
                    JOIN
                        users u ON fp.user_id = u.user_id
                    WHERE
                        fp.topic_id = :topic_id
                    ORDER BY
                        fp.created_at ASC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':topic_id', $topicId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $posts = $stmt->fetchAll();
            return $posts;
        } catch (PDOException $e) {
            error_log("Error fetching posts for topic ({$topicId}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Adds a new post (reply) to a forum topic.
     *
     * @param int $topicId
     * @param int $userId
     * @param string $content
     * @return int|false New post ID or false on failure
     */
    public function addPost(int $topicId, int $userId, string $content) {
        try {
            $sql = "INSERT INTO forum_posts (topic_id, user_id, content, created_at) VALUES (:topic_id, :user_id, :content, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':topic_id', $topicId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            if ($stmt->execute()) { return $this->conn->lastInsertId(); } else { return false; }
        } catch (PDOException $e) { error_log("Error adding forum post to topic ({$topicId}): " . $e->getMessage()); return false; }
    }

    /**
     * Updates the last_reply_at timestamp for a given topic.
     *
     * @param int $topicId
     * @return bool True on success, false on failure
     */
    public function updateTopicLastReply(int $topicId) {
        try {
            $sql = "UPDATE forum_topics SET last_reply_at = NOW() WHERE topic_id = :topic_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':topic_id', $topicId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) { error_log("Error updating topic last reply time ({$topicId}): " . $e->getMessage()); return false; }
    }

    /**
     * Adds a new topic and its initial post within a transaction.
     *
     * @param int $userId
     * @param string $title
     * @param string $initialContent
     * @return int|false New topic ID or false on failure
     */
    public function addTopic(int $userId, string $title, string $initialContent) {
        $this->conn->beginTransaction();
        try {
            $sqlTopic = "INSERT INTO forum_topics (user_id, title, created_at, last_reply_at) VALUES (:user_id, :title, NOW(), NOW())";
            $stmtTopic = $this->conn->prepare($sqlTopic);
            $stmtTopic->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmtTopic->bindParam(':title', $title, PDO::PARAM_STR);
            if (!$stmtTopic->execute()) { $this->conn->rollBack(); error_log("Failed to insert new topic."); return false; }
            $newTopicId = $this->conn->lastInsertId();
            $initialPostId = $this->addPost($newTopicId, $userId, $initialContent); // Use existing addPost
            if (!$initialPostId) { $this->conn->rollBack(); error_log("Failed to insert initial post for new topic ID: {$newTopicId}"); return false; }
            $this->conn->commit();
            return $newTopicId;
        } catch (PDOException $e) { $this->conn->rollBack(); error_log("Error adding new topic with initial post: " . $e->getMessage()); return false; }
    }
}
?>
