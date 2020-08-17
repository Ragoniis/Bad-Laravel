USE PSI;
CREATE OR REPLACE TABLE user (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(100) NOT NULL,
    `email` varchar(100) UNIQUE NOT NULL,
    `password` varchar(100) NOT NULL

) ENGINE=InnoDB;


CREATE OR REPLACE TABLE authors (
    `aid` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(100) NOT NULL,
    `surname` varchar(100) NOT NULL

) ENGINE=InnoDB;

CREATE OR REPLACE TABLE books (
    `ISBN` char(13) PRIMARY KEY,
    `title` varchar(100) NOT NULL,
    `pages` INT NOT NULL,
    `aid` INT UNSIGNED, 
    CONSTRAINT `aid` FOREIGN KEY (aid) REFERENCES authors (aid) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE OR REPLACE TABLE oauth_access_tokens (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `jwt` varchar(1000) NOT NULL,
    `revoked` BOOLEAN DEFAULT FALSE,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `user_id` INT UNSIGNED, 
    CONSTRAINT `user_id` FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
) ENGINE=InnoDB;