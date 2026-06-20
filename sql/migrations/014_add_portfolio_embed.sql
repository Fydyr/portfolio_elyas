-- Allow portfolio media to be an embedded link (YouTube / direct video) in
-- addition to an uploaded file. An item has either `filename` OR `embed_url`.

ALTER TABLE portfolio_images MODIFY filename VARCHAR(255) NULL;
ALTER TABLE portfolio_images ADD COLUMN embed_url VARCHAR(500) NULL AFTER filename;
