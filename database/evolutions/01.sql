use voucher_pool;

DROP TABLE IF EXISTS voucher;
DROP TABLE IF EXISTS special_offer;
DROP TABLE IF EXISTS recipient;

CREATE TABLE recipient (
    recipient_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,

    PRIMARY KEY (recipient_id)
);

CREATE TABLE special_offer (
    special_offer_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(128) NOT NULL,
    discount FLOAT NOT NULL,

    PRIMARY KEY (special_offer_id)
);

CREATE TABLE voucher (
    voucher_id INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(10) NOT NULL UNIQUE,
    recipient_id INT NOT NULL,
    special_offer_id INT NOT NULL,
    used_at DATETIME NULL,

    PRIMARY KEY (voucher_id),
    FOREIGN KEY (recipient_id) REFERENCES recipient(recipient_id),
    FOREIGN KEY (special_offer_id) REFERENCES special_offer(special_offer_id)
);