# #################### #
# Drop old tables      #
# #################### #

DROP TABLE IF EXISTS <[!PREFIX!]>masterpage;
DROP TABLE IF EXISTS <[!PREFIX!]>page;
DROP TABLE IF EXISTS <[!PREFIX!]>module;
DROP TABLE IF EXISTS <[!PREFIX!]>masterpage_module;
DROP TABLE IF EXISTS <[!PREFIX!]>page_module;

# #################### #
# Create new tables    #
# #################### #

CREATE TABLE IF NOT EXISTS <[!PREFIX!]>masterpage
(
	id INT(11) NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS <[!PREFIX!]>page
(
	id INT(11) NOT NULL AUTO_INCREMENT,
	masterpage INT(11) NOT NULL DEFAULT 1,
	name VARCHAR(48) NOT NULL,
	content BLOB NOT NULL,
	inmenu TINYINT(1) NOT NULL,
	weight INT(11) NOT NULL,
	PRIMARY KEY (id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS <[!PREFIX!]>module
(
	id INT(11) NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	title VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS <[!PREFIX!]>masterpage_module
(
	mp_id INT(11) NOT NULL,
	m_id INT(11) NOT NULL,
	PRIMARY KEY (mp_id, m_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS <[!PREFIX!]>page_module
(
	p_id INT(11) NOT NULL,
	m_id INT(11) NOT NULL,
	location VARCHAR(50) NOT NULL,
	method VARCHAR(50) NOT NULL,
	PRIMARY KEY (p_id, m_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;