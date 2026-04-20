CREATE TABLE `users` (
    `id` int(11) PRIMARY KEY NOT NULL,
    `username` varchar(30) NOT NULL,
    `email` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;