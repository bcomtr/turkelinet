<?php
// app/Models/Listing.php

require_once dirname(__DIR__) . '/Core/Database.php';

class Listing {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    /**
     * Fetches published listings, optionally limited.
     * Joins with categories and users for basic info.
     *
     * @param int $limit Number of listings to fetch (0 for all)
     * @return array|false Array of listings or false on error
     */
    public function getPublishedListings(int $limit = 10) {
        try {
            $sql = "SELECT
                        l.listing_id, l.name, l.description, l.address, l.featured_image, l.created_at,
                        c.name as category_name, c.slug as category_slug,
                        u.full_name as added_by_user
                    FROM
                        listings l
                    JOIN
                        categories c ON l.category_id = c.category_id
                    JOIN
                        users u ON l.user_id = u.user_id
                    WHERE
                        l.status = 'published' AND c.type = 'listing'
                    ORDER BY
                        l.created_at DESC";

            if ($limit > 0) {
                $sql .= " LIMIT :limit";
            }

            $stmt = $this->conn->prepare($sql);

            if ($limit > 0) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            }

            $stmt->execute();
            $listings = $stmt->fetchAll();
            return $listings;

        } catch (PDOException $e) {
            error_log("Error fetching listings: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetches a single published listing by its ID.
     * Joins with categories and users for more details.
     *
     * @param int $id The ID of the listing
     * @return array|false The listing data as an associative array, or false if not found/error
     */
    public function getListingById(int $id) {
        try {
            $sql = "SELECT
                        l.listing_id, l.name, l.description, l.address, l.phone, l.website,
                        l.latitude, l.longitude, l.featured_image, l.created_at,
                        c.category_id, c.name as category_name, c.slug as category_slug,
                        u.user_id as user_id, u.username as added_by_username, u.full_name as added_by_user
                    FROM
                        listings l
                    JOIN
                        categories c ON l.category_id = c.category_id
                    JOIN
                        users u ON l.user_id = u.user_id
                    WHERE
                        l.listing_id = :id AND l.status = 'published' AND c.type = 'listing'
                    LIMIT 1"; // Expect only one result

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $listing = $stmt->fetch();

            return $listing; // fetch() returns false if no row is found

        } catch (PDOException $e) {
            error_log("Error fetching listing by ID ({$id}): " . $e->getMessage());
            return false;
        }
    }


    // Methods for getListingsByCategory, addListing, updateListing, etc. can be added here.

}
?>
