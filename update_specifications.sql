-- Add specifications column to products table
ALTER TABLE `products` ADD COLUMN `specifications` TEXT NULL AFTER `description`;

-- Update specifications from existing product_specifications table
UPDATE products p 
SET specifications = (
    SELECT GROUP_CONCAT(
        CONCAT('<tr><td>', spec_name, '</td><td>', spec_value, '</td></tr>')
        ORDER BY sort_order
        SEPARATOR ''
    )
    FROM product_specifications ps 
    WHERE ps.product_id = p.id
);

-- Wrap in table if has data
UPDATE products 
SET specifications = CONCAT('<table><tbody>', specifications, '</tbody></table>')
WHERE specifications IS NOT NULL AND specifications != '';
