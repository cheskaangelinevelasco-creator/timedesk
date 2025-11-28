-- Migration: create freebies table and sample items
CREATE TABLE IF NOT EXISTS `freebies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type` varchar(100) NOT NULL,
  `item` varchar(255) NOT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample items
INSERT INTO `freebies` (`room_type`,`item`,`quantity`) VALUES
('Mini Convention','Projector',NULL),
('Mini Convention','White Screen',NULL),
('Mini Convention','Speaker',NULL),
('Mini Convention','LED Screen',NULL),
('Ampitheater','300 chairs','300'),
('Ampitheater','Projector',NULL),
('Ampitheater','White Screen',NULL),
('Ampitheater','Speaker',NULL),
('Ampitheater','LED Screen',NULL),
('Nieto Hall','150 Chairs with 30 Tables','150 chairs / 30 tables'),
('Nieto Hall','Projector',NULL),
('Nieto Hall','White Screen',NULL),
('Nieto Hall','Speaker',NULL),
('Nieto Hall','LED Screen',NULL),
('Rico Fajardo','80 chairs with 10 tables','80 chairs / 10 tables'),
('Rico Fajardo','Projector',NULL),
('Rico Fajardo','White Screen',NULL),
('Rico Fajardo','Speaker',NULL),
('Rico Fajardo','LED Screen',NULL);
