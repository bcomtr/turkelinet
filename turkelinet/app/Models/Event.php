<?php
// app/Models/Event.php

// Handles database operations related to events

require_once dirname(__DIR__) . '/Core/Database.php';

class Event {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Fetches upcoming or ongoing events.
     * Orders by start time.
     *
     * @param int $limit Number of events to fetch (0 for all upcoming)
     * @return array|false Array of events or false on error
     */
    public function getUpcomingEvents(int $limit = 10) {
        try {
            // Select events where the start_time is in the future or ongoing (end_time is in future or null)
            // Using NOW() to get the current database time
            $sql = "SELECT
                        e.event_id, e.title, e.description, e.start_time, e.end_time,
                        e.location_name, e.location_address, e.featured_image,
                        u.full_name as created_by_user
                        -- Optionally join with categories if needed
                        -- c.name as category_name, c.slug as category_slug
                    FROM
                        events e
                    JOIN
                        users u ON e.user_id = u.user_id
                    -- LEFT JOIN categories c ON e.category_id = c.category_id AND c.type = 'event'
                    WHERE
                        e.start_time >= CURDATE() -- Fetch events starting today or later
                        -- OR (e.end_time IS NOT NULL AND e.end_time >= NOW()) -- Optionally include ongoing events
                    ORDER BY
                        e.start_time ASC"; // Show nearest events first

            if ($limit > 0) {
                $sql .= " LIMIT :limit";
            }

            $stmt = $this->conn->prepare($sql);

            if ($limit > 0) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            }

            $stmt->execute();
            $events = $stmt->fetchAll();
            return $events;

        } catch (PDOException $e) {
            error_log("Error fetching upcoming events: " . $e->getMessage());
            return false;
        }
    }

    // Methods for getEventById/Slug, getAllEvents (past and future), addEvent, etc. can be added here.

}
?>
