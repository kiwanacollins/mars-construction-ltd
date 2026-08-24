USE mars_estate;

-- Drop agent linkage before dropping the agents table
ALTER TABLE properties DROP FOREIGN KEY properties_ibfk_1;

-- Drop real-estate-only fields, add house-plan fields
ALTER TABLE properties
    DROP COLUMN agent_id,
    DROP COLUMN status,
    DROP COLUMN price_period,
    DROP COLUMN address,
    DROP COLUMN zip_code,
    CHANGE COLUMN property_type plan_number VARCHAR(80) DEFAULT NULL,
    CHANGE COLUMN bathrooms bathrooms DECIMAL(3,1) DEFAULT 0,
    ADD COLUMN stories INT DEFAULT 1 AFTER bathrooms,
    ADD COLUMN garage_bays INT DEFAULT 0 AFTER stories,
    ADD COLUMN width_ft DECIMAL(6,2) DEFAULT NULL AFTER area_sqft,
    ADD COLUMN depth_ft DECIMAL(6,2) DEFAULT NULL AFTER width_ft,
    ADD COLUMN foundation_type VARCHAR(80) DEFAULT NULL AFTER depth_ft,
    ADD COLUMN roof_type VARCHAR(80) DEFAULT NULL AFTER foundation_type,
    ADD COLUMN roof_pitch VARCHAR(20) DEFAULT NULL AFTER roof_type,
    ADD COLUMN exterior_material VARCHAR(120) DEFAULT NULL AFTER roof_pitch;

-- Multiple purchase options per plan (PDF set, CAD file, print set, etc.)
CREATE TABLE IF NOT EXISTS plan_pricing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    tier_name VARCHAR(120) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    description VARCHAR(255) DEFAULT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS agents;
