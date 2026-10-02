UPDATE reports SET status = '0', updated_at = CURRENT_TIMESTAMP
SELECT user_id FROM reports WHERE id = 1